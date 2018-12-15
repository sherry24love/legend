<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\User ;

class MemberController extends Controller
{
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('用户管理');
			$content->description('列表');

			$content->body($this->grid());
		});
	}
	
	public function checkreg() {
		return Admin::content(function (Content $content) {
		
			$content->header('用户查询');
			$content->description('注册查询');
			$keyword = request()->input('keyword');
			$data = [] ;
			$count = 0 ;
			if( $keyword ) {
				$count = User::where('name' , 'like' , "%$keyword%")->count();
			}
			$data['count'] = $count ;
			$content->body( view('admin.checkreg' , $data )->render());
		});
	}

	

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		return Admin::grid( User::class, function (Grid $grid) {
			$grid->model()->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->name( '用户名' );
			$grid->email('邮箱');
			$grid->money('钱包');
			$grid->contact('联系人');
			$grid->qq("QQ");
			$grid->created_at('时间');
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->disableCreation() ;
			$grid->filter( function( $filter ){
				$filter->like('name' , '手机号');
				$filter->between('created_at' , '时间')->datetime();
			});
			
			$grid->actions( function( $action ){
				$action->disableDelete();
			});
		});
	}
	
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {

			$content->header('用户编辑');
			$content->description('列表');

			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	protected function form() {
		return Admin::Form( User::class , function( Form $form ){
			$form->display('id' , '推广码' );
			$form->text('name' , '用户名' )->rules('required')->help('请不要随意修改用户名');
			$form->password('pwd' , '密码' );
			$form->text('contact' , '联系人');
			$form->text('qq' , 'QQ' );
			$form->text('email' , '邮箱' );
			$form->text('rec_id' , '推荐人编号');
			$form->display('money' , '余额');
			$form->saving( function( $form ){
				$pwd = request()->input('pwd');
				if( $pwd ) {
					$form->model()->password = encrypt( $pwd );
				}
				
			});
		});
	}
}
