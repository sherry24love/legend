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

use Sherry\Shop\Models\Products ;
use Sherry\Shop\Models\Category ;
use Sherry\Shop\Models\Brand ;

use Encore\Admin\Controllers\ModelForm ;
use Sherry\Shop\Models\GoodsType;

class ProductsController extends Controller {
	
	public function index() {
		return Admin::content(function(Content $content ){
			$content->header(trans('shop::lang.product'));
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
		return Admin::grid( Products::class, function (Grid $grid) {
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
	
	public function create() {
		return Admin::content(function (Content $content) {
			$content->header(trans('shop::goods.goods'));
			$content->description(trans('admin::lang.create'));
			$content->body($this->form());
		});
	}
	
	
	protected function form() {
		return Admin::form( Products::class, function ( Form $form) {
			$form->tab( trans('shop::goods.base_info') , function ($form) {
			    $form->text('goods_name' , trans('shop::goods.goods_name') )->rules('required');
			    $form->text('goods_sn' , trans('shop::goods.goods_sn') )->help( trans('shop::goods.goods_sn_help') );
			    $form->select('category_id' , trans('shop::goods.category_name') )->options( Category::selectCategoryOptions() ) ;
			    $form->multipleSelect('other_cate', trans('shop::goods.other_cate'))->options( Category::selectCategoryOptions() );
			    $form->select('brand_id' , trans('shop::goods.brand_name') )->options( Brand::pluck('brand_name' , 'id') ) ;
			    $form->currency('shop_price' , trans('shop::goods.shop_price') )->symbol('￥')->rules('required');
			    $form->currency('market_price' , trans('shop::goods.market_price') )->symbol('￥')->rules('required');
			    $form->number('give_integral' , trans('shop::goods.give_integral') )->default( -1 )->help( trans( 'shop::goods.give_integral_help') );
			    $form->number('rank_integral' , trans('shop::goods.rank_integral') )->default( -1 )->help( trans( 'shop::goods.rank_integral_help') );
			    $form->number('integral' , trans('shop::goods.integral') )->default( 0 )->help( trans( 'shop::goods.integral_help') );
			    $states = [
			    		'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
			    		'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			    ];
			    //促销信息
			    $form->switch('is_promote' , trans( 'shop::goods.is_promote') )->states( $states );
			    $form->currency('promote_price' , trans('shop::goods.promote_price') )->symbol('￥')->rules('required');
			    $form->dateRange('promote_start_date' , 'promote_end_date' , trans('shop::goods.promote_time') );
			    
			    //商品图片
			    $form->image('goods_img' , trans('shop::goods.img'));
			    $form->image('goods_thumb' , trans('shop::goods.img_thumb'));
			    
			    $form->switch('is_catindex' ,  trans( 'shop::goods.is_catindex') )->states( $states );
			    
			})->tab( trans('shop::goods.detail_info') , function ($form) {
			     $form->editor('goods_desc' , trans('shop::goods.goods_desc'));
			   
			})->tab( trans('shop::goods.other_info') , function( $form ) {
				//商品重量
				$form->text('goods_weight' , trans('shop::goods.goods_weight'))->append('kg');
				$form->hidden('weight_unit')->default( 1 );
				$form->text('goods_number' , trans('shop::goods.goods_number') )->default( 1 )->help( trans('shop::goods.goods_number_help')) ;
				$form->text('warn_number' , trans('shop::goods.warn_number' ) )->default( 1 ) ;
				$states = [
			    		'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
			    		'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			    ];
				$form->switch('is_best' , trans( 'shop::goods.is_best') )->states( $states );
				$form->switch('is_new' , trans( 'shop::goods.is_new') )->states( $states );
				$form->switch('is_hot' , trans( 'shop::goods.is_hot') )->states( $states );
				
				$form->switch('is_on_sale' , trans( 'shop::goods.is_on_sale') )->states( $states )->default( 1 )->help( trans( 'shop::goods.is_on_sale_help') );
				$form->switch('is_alone_sale' , trans( 'shop::goods.is_alone_sale') )->states( $states )->default( 1 )->help( trans( 'shop::goods.is_alone_sale_help') );
				
				$form->switch('is_shipping' , trans( 'shop::goods.is_shipping') )->states( $states )->default( 0 )->help( trans( 'shop::goods.is_shipping_help') );
				$form->text('keywords' , trans('shop::goods.keywords') )->help( trans('shop::goods.keywords_help') );
				$form->textarea('goods_brief' , trans('shop::goods.goods_brief'));
				$form->textarea('seller_note' , trans('shop::goods.seller_note') )->help( trans('shop::goods.seller_note_help') );
				//分销商代理佣金
				$form->hidden('user_rebate_price')->default( 0 );
				
				
			} )->tab( trans('shop::goods.attr_info') , function( $form ){
				$form->select('goods_type' , trans('shop::goods.goods_type') )->options( GoodsType::selectTypeOptions() ) ;
				$form->html('' , '')->setWidth();
				$this->attrScript();
			})->tab( trans('shop::goods.relation_goods') , function( $form ){
				
				
			});
		});
		
	}
	
	protected function attrScript() {
		$script = <<<EOT
$(document).on('change', ".goods_type", function () {
    console.log('----- ' );
});
EOT;
		
		Admin::script($script);
	}
}