<?php
namespace Sherry\Shop\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

use Illuminate\Routing\Controller;

use Sherry\Shop\Models\Brand ;

use Encore\Admin\Controllers\ModelForm ;

class BrandController extends Controller
{
	use ModelForm;

	public function index() {
		return Admin::content( function( Content $content ){
			$content->header(trans('shop::brand.brand'));
			$content->description(trans('admin::lang.list'));
			$content->body($this->grid()->render());
					
		});
	}
	
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		return Admin::grid( Brand::class, function (Grid $grid) {
			$grid->id('ID')->sortable();
			$grid->brand_name(trans('shop::brand.brand_name'));
			$grid->site_url(trans('shop::brand.site_url'));
			$grid->brand_desc(trans('shop::brand.brand_desc'));
			$grid->sort_order(trans('shop::brand.sort_order'))->sortable()->editable();
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$grid->is_show( trans('cms::lang.display' ) )->switch($states);
			$grid->filter(function ($filter) {
				$filter->like('brand_name', trans('shop::brand.brand_name'));
			});
				$grid->disableExport();
		});
	}
	
	/**
	 * 创建
	 */
	public function create() {
		return Admin::content(function (Content $content) {
			$content->header(trans('shop::brand.brand'));
			$content->description(trans('admin::lang.create'));
			$content->body($this->form());
		});
	}
	
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
			$content->header(trans('shop::brand.brand'));
			$content->description(trans('admin::lang.edit'));
			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	protected function form() {
		return Admin::form( Brand::class, function ( Form $form) {
			$form->display('id', 'ID');
			$form->text('brand_name', trans('shop::brand.brand_name'))->rules('required');
			$form->url('site_url' , trans('shop::brand.site_url'))->help( '' );
			$form->image('brand_logo', trans('shop::brand.brand_logo'));
			$form->textarea('brand_desc' , trans('shop::brand.brand_desc') );
			$form->number('sort_order', trans('cms::lang.sort'))->default( 0 )->help('序号不能为负数，且序号越大越靠前')->default( 50 );
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$form->switch( 'is_show' , trans('shop::brand.is_show' ) )->states($states)->default(1)->help('当品牌下还没有商品的时候，首页及分类页的品牌区将不会显示该品牌。');
			$form->display('created_at', trans('admin::lang.created_at'));
			$form->display('updated_at', trans('admin::lang.updated_at'));
		});
	}
}