<?php
namespace Sherry\Cms\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

use Illuminate\Routing\Controller;

use Sherry\Cms\Models\Advtarget ;

use Encore\Admin\Controllers\ModelForm ;

class AdvtargetController extends Controller {
	use ModelForm;
	
	public function index() {
		
		return Admin::content(function (Content $content) {
			$content->header(trans('cms::lang.advtarget'));
			$content->description(trans('admin::lang.list'));
			$content->body($this->grid()->render());
		});
	}
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		return Admin::grid( Advtarget::class, function (Grid $grid) {
			$grid->id('ID')->sortable();
			$grid->name(trans('cms::lang.adv_target_title'));
			$grid->slug(trans('cms::lang.slug'));
	
			$grid->created_at(trans('admin::lang.created_at'));
			$grid->updated_at(trans('admin::lang.updated_at'));
			$grid->filter(function ($filter) {
				$filter->like('title', trans('cms::lang.adv_target_title'));
			});
			$grid->disableExport();
		});
	}
	
	/**
	 * 新增
	 */
	public function create() {
		return Admin::content(function (Content $content) {
			$content->header(trans('cms::lang.advtarget'));
			$content->description(trans('admin::lang.create'));
			$content->body($this->form());
		});
	}
	
	/**
	 * 修改单页面的内容
	 */
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
			$content->header(trans('cms::lang.advtarget'));
			$content->description(trans('admin::lang.edit'));
			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	protected function form() {
		return Admin::form( Advtarget::class, function ( Form $form) {
			$form->display('id', 'ID');
		
			$form->text('name', trans('cms::lang.title'))->rules('required');
			$form->text('slug', trans('cms::lang.slug'))->rules('required');
			$form->textarea('description', trans('cms::lang.description'))->placeholder( trans('cms::lang.advtarget_description') );
			$form->display('created_at', trans('admin::lang.created_at'));
			$form->display('updated_at', trans('admin::lang.updated_at'));
		});
	}
}