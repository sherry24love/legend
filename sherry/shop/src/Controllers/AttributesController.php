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

use Sherry\Shop\Models\Attributes ;
use Sherry\Shop\Models\GoodsType ;

use Encore\Admin\Controllers\ModelForm ;

class AttributesController extends Controller {
	use ModelForm;

	public function index( $id = 0 ) {
		return Admin::content( function( Content $content ) use( $id ) {
			$content->header(trans('shop::type.type'));
			$content->description(trans('admin::lang.list'));
			$content->body($this->grid( $id )->render());
				
		});
	}
	
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid( $typeId ) {
		return Admin::grid( Attributes::class, function (Grid $grid)  use( $typeId ) {
			$grid->resource('/admin/shop/attributes' );
			if( $typeId ) {
				$grid->model()->where('type_id' , $typeId );
			}
			$grid->id('ID')->sortable();
			$grid->attr_name(trans('shop::attr.name'));
			$grid->column('type.name' , trans('shop::type.name'));
			$grid->attr_input_type(trans('shop::attr.attr_input_type'))->display( function( $v ){
				$attrInputType = [
						0 => trans('shop::attr.attr_input_type_input' ) ,
						1 => trans('shop::attr.input_type_select' ) ,
						2 => trans('shop::attr.attr_input_type_textarea' ) ,
				] ;
				return data_get( $attrInputType , $v );
			});
			$grid->attr_values( trans( 'shop::attr.attr_values' ) )->display( function( $v ){
				$v = trim( $v );
				$v = explode("\r" , $v );
				return implode(',' , $v );
			} );
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$grid->sort_order( trans('shop::brand.sort_order' ) )->editable()->sortable();
			$grid->filter(function ($filter) {
				$filter->like('attr_name', trans('shop::attr.name'));
			});
			$grid->actions(function( $actions ) {
				if( $actions->row->attr_name == '颜色' ) {
					$url = admin_url('shop/attributes/' . $actions->getKey() . '/color') ;
					$actions->append( '<a href="'. $url .'">'. trans("shop::lang.color") .'</a>&nbsp;' );
				}
			});
			$grid->disableExport();
		});
	}
	
	
	/**
	 * 创建
	 */
	public function create() {
		return Admin::content(function (Content $content) {
			$content->header(trans('shop::attr.attr'));
			$content->description(trans('admin::lang.create'));
			$content->body($this->form());
		});
	}
	
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
			$content->header(trans('shop::attr.attr'));
			$content->description(trans('admin::lang.edit'));
			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	protected function form() {
		return Admin::form( Attributes::class, function ( Form $form) {
			$form->display('id', 'ID');
			$form->text('attr_name', trans('shop::attr.name'))->rules('required');
			$form->select('type_id', trans('shop::attr.type'))->options(function(){
				return GoodsType::pluck('name' , 'id');
			});
			$optAttrIndex = [
					0 => trans( 'shop::attr.no_index' ) ,
					1 => trans( 'shop::attr.keyword_index' ) ,
					2 => trans( 'shop::attr.range_index' ) ,
			];
			$form->radio('attr_index' , trans('shop::attr.attr_index') )->default( 0 )->options( $optAttrIndex )->help( trans('shop::attr.attr_index_help' ) );
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$form->switch('is_linked' , trans('shop::attr.is_linked' ) )->states( $states )->default( 0 );
			$attrType = [
					0 => trans('shop::attr.attr_type_unique' ) ,
					1 => trans('shop::attr.attr_type_radio' ) ,
					2 => trans('shop::attr.attr_type_checkbox' ) ,
			] ;
			$form->radio( 'attr_type' , trans('shop::attr.attr_type') )->default(0)->options( $attrType )->help( trans('shop::attr.attr_type_help' ) );
			$attrInputType = [
					0 => trans('shop::attr.attr_input_type_input' ) ,
					1 => trans('shop::attr.attr_input_type_select' ) ,
					2 => trans('shop::attr.attr_input_type_textarea' ) ,
			] ;
			$form->radio( 'attr_input_type' , trans('shop::attr.attr_input_type') )->default(0)->options( $attrInputType )->help( trans('shop::attr.attr_input_type_help' ) );
			$form->textarea('attr_values' , trans('shop::attr.attr_values') )->default('');

			$form->switch( 'attr_txm' , trans('shop::attr.attr_txm' ) )->states($states)->default(0);
			$form->switch( 'is_attr_gallery' , trans('shop::attr.is_attr_gallery' ) )->states($states)->default(0)->help( trans('shop::attr.is_attr_gallery_help') );
			$form->hidden('sort_order')->default(50 );
			$form->display('created_at', trans('admin::lang.created_at'));
			$form->display('updated_at', trans('admin::lang.updated_at'));
		});
	}
}