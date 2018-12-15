<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Setting ;

class SettingController extends Controller
{
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('系统设置');
			$content->description('列表');

			$content->body($this->grid());
		});
	}
	

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		return Admin::grid( Setting::class, function (Grid $grid) {
			$grid->model()->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->name( '配置名称' );
			$grid->key('配置别名');
			$grid->val('配置值');
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->filter( function( $filter ){
				$filter->disableIdFilter();
				$filter->like('name' , '名称');
				$filter->between('created_at' , '时间')->datetime();
			});
			
			$grid->actions( function( $action ){
				$action->disableDelete();
			});
		});
	}
	


	public function create( ) {
		return Admin::content(function (Content $content) {
	
			$content->header('配置');
			$content->description('新增');
	
			$content->body($this->form() );
		});
	}
	
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {

			$content->header('配置');
			$content->description('编辑');

			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	protected function form() {
		return Admin::Form( Setting::class , function( Form $form ){
			$form->text('name' , '配置名称' )->rules('required');
			$form->text('key' , '配置别名' )->rules('required');
			$form->textarea('val' , '配置值');
		});
	}
}
