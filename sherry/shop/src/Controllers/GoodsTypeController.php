<?php
namespace Sherry\Shop\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Displayers\Link ;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

use Illuminate\Routing\Controller;

use Sherry\Shop\Models\GoodsType ;

use Encore\Admin\Controllers\ModelForm ;

class GoodsTypeController extends Controller
{
	use ModelForm;

	public function index() {
		return Admin::content( function( Content $content ){
			$content->header(trans('shop::type.type'));
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
		return Admin::grid( GoodsType::class, function (Grid $grid) {
			$grid->id('ID')->sortable();
			$grid->name(trans('shop::type.name'));
			$grid->attr_group(trans('shop::type.attr_group'));
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$grid->enabled( trans('shop::type.enable' ) )->switch($states);
			$grid->filter(function ($filter) {
				$filter->like('name', trans('shop::type.name'));
			});
			$grid->disableExport();
			$grid->actions(function( $actions ) {
				$url = admin_url('shop/attributes/' . $actions->getKey() ) ;
				$actions->prepend( '<a href="'. $url .'"><i class="fa fa-paper-plane"></i>'. trans("shop::lang.attr_list") .'</a>&nbsp;' );
			});
		});
	}
	
	/**
	 * 创建
	 */
	public function create() {
		return Admin::content(function (Content $content) {
			$content->header(trans('shop::type.type'));
			$content->description(trans('admin::lang.create'));
			$content->body($this->form());
		});
	}
	
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
			$content->header(trans('shop::type.type'));
			$content->description(trans('admin::lang.edit'));
			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	protected function form() {
		return Admin::form( GoodsType::class, function ( Form $form) {
			$form->display('id', 'ID');
			$form->text('name', trans('shop::type.type'))->rules('required');
			$form->textarea('attr_group' , trans('shop::type.attr_group') );
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$form->switch( 'enabled' , trans('shop::type.enabled' ) )->states($states)->default(1)->help('当品牌下还没有商品的时候，首页及分类页的品牌区将不会显示该品牌。');
			$form->display('created_at', trans('admin::lang.created_at'));
			$form->display('updated_at', trans('admin::lang.updated_at'));
		});
	}
}