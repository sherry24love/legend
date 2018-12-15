<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Company ;

class CompanyController extends Controller
{
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('船公司管理');
			$content->description('列表');

			$content->body($this->grid());
		});
	}

	/**
	 * Edit interface.
	 *
	 * @param $id
	 * @return Content
	 */
	public function edit($id)
	{
		return Admin::content(function (Content $content) use ($id) {

			$content->header('船公司管理');
			$content->description('编辑');

			$content->body($this->form()->edit($id));
		});
	}

	/**
	 * Create interface.
	 *
	 * @return Content
	 */
	public function create()
	{
		return Admin::content(function (Content $content) {

			$content->header('船公司管理');
			
			$content->description('创建');

			$content->body($this->form());
		});
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		return Admin::grid(Company::class, function (Grid $grid) {
			$grid->model()->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->name('船公司简称');
			$grid->short_name('船公司全称');
			
			$grid->sort('排序')->sortable();
			$grid->created_at('创建时间');
			$grid->updated_at('修改时间');
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->filter( function( $filter ){
				$filter->like('name' , '船公司简称');
				
			});
		});
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		return Admin::form(Company::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('name' , '船公司简称' )->rules('required');
			$form->text('short_name' , '船公司全称')->rules('required');
			$form->image('cover' , '船公司标识' );
			$form->number('sort' , '排序')->default( 50 )->help('序号越大排序越靠前');
			$form->display('created_at', 'Created At');
			$form->display('updated_at', 'Updated At');
		});
	}
}
