<?php
/**
 * 航推荐
 */
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\FlightPrice ;
use App\Models\Port;

class FlightPriceController extends Controller {
	
	use ModelForm ;
	
	
	public function index() {
		return Admin::content(function (Content $content) {
		
			$content->header('航线管理');
			$content->description('推荐一些热门航线与价格优势航线');
		
			$content->body($this->grid());
		});
	}
	
	protected function grid() {
		return Admin::grid( FlightPrice::class, function (Grid $grid) {
			$grid->model()->with('fromPort' , 'toPort')->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->column( 'fromPort.name' , '起运港' );
			$grid->column('toPort.name' , '目的港');
			$grid->cover('封面')->image();
			$grid->price('参考价格');
			$grid->link_type('链接类型')->display( function($v){
				return data_get( config('global.link_type') , $v );
			});
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$grid->is_hot('热门')->switch($states);
			$grid->is_promotion('所属区域')->display( function( $v ){
				
				return data_get( config('global.flight_type' ) , $v , '');
			});
			$grid->is_recommend('推荐')->switch($states);
			$grid->display('是否显示')->switch($states);
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->filter( function( $filter ){
				$filter->disableIdFilter();
				$filter->is('from_port' , '起运港')->select( Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id') );
				$filter->is('to_port' , '目的港')->select( Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id') );
				$filter->between('available_to' , '时间')->datetime();
			});
					
				$grid->actions( function( $action ){
					//$action->disableDelete();
				});
		});
	}
	
	public function create() {
		return Admin::content(function (Content $content) {
		
			$content->header('航线推荐');
			$content->description('新增');
		
			$content->body($this->form() );
		});
	}
	
	public function edit( $id ) {
		return Admin::content(function (Content $content) use( $id ) {
	
			$content->header('航线推荐');
			$content->description('新增');
	
			$content->body($this->form()->edit( $id ) );
		});
	}
	
	
	
	protected function form() {
		return Admin::Form( FlightPrice::class , function( Form $form ){
			$form->select('from_port' , '起运港')->options( Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' ) )->rules('required');
			$form->select('to_port' , '目的港')->options( Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' ) )->rules('required');
			$form->image('cover' , '封面图' );
			$form->currency('price' , '参考海运费' )->symbol("￥")->rules('required');
			$form->dateRange('available_from' , 'available_to', '价格生效时间')->help("如果不填写则表示不限制");
			$form->radio('link_type' , '链接类型')->options( config('global.link_type') );
			$form->text('link' , '链接地址')->help("如果是指定链接，则必须填写此项");
			$states = [
					'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
					'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
			];
			$form->switch('display' , '是否显示')->states($states)->default( 1 );
			$form->switch('is_hot' , '是否热门')->states($states)->default( 0 );
			$form->switch('is_recommend' , '是否推荐')->states($states)->default( 0 );
			$form->select('is_promotion' , '所属区域')->options( config('global.flight_type') )->default( 1 )->rules('required');
		});
		
	}
	
	public function update($id)
	{
		$form = $this->form() ;
		$form->saving( function( $form ) use( $id ) {
			$fromPort = $form->input('from_port');
			$toPort = $form->input('to_port');
			$count = FlightPrice::where('from_port' , $fromPort )->where('to_port' , $toPort )->where('id' , '<>' , $id )->count();
			if( $count ) {
				admin_toastr('本条航线已经存在了，请更换港口' , 'error');
				return back()->withInput();
			}
			
		});
		return $form->update($id);
	}
	
	public function store()
	{
		$form = $this->form() ;
		$form->saving( function( $form ){
			$fromPort = $form->input('from_port');
			$toPort = $form->input('to_port');
			$count = FlightPrice::where('from_port' , $fromPort )->where('to_port' , $toPort )->count();
			if( $count ) {
				admin_toastr('本条航线已经存在了，请更换港口' , 'error');
				return back()->withInput();
			}	
		});
		return $form->store();
	}
}