<?php

namespace App\Admin\Controllers;

use DB ;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Company ;
use App\Models\Ship ;
use App\Models\Flight ;
use Illuminate\Http\Request;
use App\Models\Port;
use App\Models\FlightDate;

class ShipController extends Controller
{
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('船只管理');
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

			$content->header('船只管理');
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

			$content->header('船只管理');
			
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
		return Admin::grid(Ship::class, function (Grid $grid) {
			$grid->model()->with('company')->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->name('船只名称');
			$grid->column('company.name' , '船公司简称');
			
			$grid->sort('排序');
			$grid->created_at('创建时间');
			$grid->updated_at('修改时间');
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->tools( function( $tools ){
				$import = route('admin.flight.create');
				$tools->append('<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="'. $import .'" class="btn btn-sm btn-success">
        <i class="fa fa-save"></i>&nbsp;&nbsp;导入航次
    </a>
</div>');
			});
			$grid->actions( function( $actions ){
				$url = route('admin.flight.show' , ['id' => $actions->row->id ]);
				$actions->append("&nbsp;<a href='{$url}'>航次管理</a>");
			});
			$grid->filter( function( $filter ){
				$filter->like('name' , '船名称') ;
				$filter->is('company_id' , '船公司名称')->select( Company::pluck('name' , 'id' ) );
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
		return Admin::form(Ship::class, function (Form $form) {

			$form->display('id', 'ID');
			$form->text('name' , '船名称' )->rules('required');
			$form->select('company_id' , '船公司' )->options( function(){
				$company = new Company();
				return $company->selectOption();
			} );
			$form->number('sort' , '排序')->default( 50 )->help('序号越大排序越靠前');
			$form->display('created_at', 'Created At');
			$form->display('updated_at', 'Updated At');
		});
	}
	
	public function flight( $id ) {
		return Admin::content(function (Content $content) use ($id) {
			$ship = Ship::findOrFail( $id );
			$content->header('航次管理');
			$content->description( $ship->name );
		
			$content->body( $this->_flightgrid( $id ) );
		});
	}
	
	protected function _flightgrid( $id ) {
		return Admin::grid( Flight::class, function (Grid $grid) use( $id ) {
			
			$grid->model()->where('ship_id' , $id )->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->no('航次名称');
			$grid->from( '起始港');
			$grid->to( '目的港');
			$grid->from_time( '开船时间')->display( function( $v ){
				return date('Y-m-d' , $v );
			});
			$grid->to_time( '到港时间')->display( function( $v ){
				return date('Y-m-d' , $v );
			});
			$grid->column('dates.port_name' , '途径港')->display( function(){
				$dates =  $this->dates ;
				$v = '' ;
				foreach( $dates as $d ) {
					$v .= data_get( $d , 'port_name' ) . '/' . date('m-d' , data_get( $d , 'date' ) ) . "<br/>" ;
				}
				return $v ;
			} );
			$grid->created_at('创建时间');
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->disableCreation();
			//$grid->disableActions() ;
		});
	}
	
	public function flightcreate(  Request $request ) {
		session( ['previous' => \URL::previous() ]);
		return Admin::content(function( Content $content ){
			$content->header('导入船期');
		
			$content->description('导入船期');
				
			$content->body($this->excelform());
				
		});
	}
	
	/**
	 * 导入船期
	 */
	public function flightimport() {
		$form = $this->excelform();
		$data = request()->all();
		$file = $form->builder()->field('excel')->prepare( data_get( $data , 'excel')) ;
		$excel = storage_path( 'app' ) . DIRECTORY_SEPARATOR . $file ;
		
		include_once app_path('Support/PHPExcel.php');
		DB::beginTransaction();
		try {
			$objPHPExcel = \PHPExcel_IOFactory::load( $excel );
			$sheet = $objPHPExcel->setActiveSheetIndex(0);
			foreach($sheet->getRowIterator() AS $k => $row) {
				if( $k == 1 ) {
					continue ;
				}
				
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				//这里是一个航班
				$ship = null ;
				$flight = [] ;
				$flightDate = [] ;
				$company = null ;
				$first = [] ;
				$end = [] ;
				foreach($cellIterator AS $key => $cell) {
					//表格的值
					$cv= trim($cell->getValue());
					if( $key === 0 ) {
						//船名
						$company = Company::where('name' , $cv )->first();
						if( !$company ) {
							throw new \Exception("船公司{$key}{$cv}不存在系统中");
						}
					}
					if( $key === 1 ) {
						//船名
						$ship = Ship::where('company_id' , $company->id )->where('name' , $cv )->first();
						if( !$ship ) {
							throw new \Exception("船名{$cv}不存在系统中");
						}
					}
					if( $key === 2 ) {
						//班次
						$flight['no'] = $cv ;
						$flight['ship_id'] = $ship->id ;
					}
					if( $key > 2 && $cv ) {
						$cvArr = explode('-' , $cv ); 
						$p = data_get( $cvArr , 0 );
						$d = data_get( $cvArr , 1 );
						$prefix = data_get( $cvArr , 2 , '' );
						//list( $p , $d , $prefix ) = explode('-' , $cv );
						$port = Port::where('name' , $p )->firstOrFail();
						if( !$port ) {
							throw new \Exception("港口{$p}不存在系统中");
						}
						$d = strtotime( date('Y') . $d );
						
						if( $key === 3 ) {
							$first = [
									'port_name' => $p ,
									'date' => $d 
							] ;	
						}
						$end = [
								'port_name' => $p ,
								'date' => $d
						] ;
						$flightDate[] = new FlightDate([
								'ship_id' => $ship->id ,
								'port_id' => $port->id ,
								'port_name' => $p ,
								'date' => $d ,
								'prefix' => $prefix
						]);
					}
				}
				if( empty( $first ) ) {
					continue ;
				}
				$flightRow = Flight::firstOrCreate( $flight , [
						'from' => data_get( $first , 'port_name' ) , 
						'to' => data_get( $end , 'port_name' ) ,
						'from_time' => data_get( $first , 'date' )   ,
						'to_time' => data_get( $end , 'date' )
				] );
				if( !$flightRow ) {
					throw new \Exception("班次写入出错") ;
				}
				$flightRow->dates()->delete();
				$row = $flightRow->dates()->saveMany( $flightDate );
				if( !$row ) {
					throw new \Exception("港口到达日期写入出错") ;
				}
			}
			DB::commit();
			//导入成功
			admin_toastr('导入完成' );
			return redirect( session( 'previous') );
		} catch( \Exception $e ) {
			DB::rollback();
			//导入失败
			admin_toastr('导入失败' . $e->getMessage(), 'error' );
			return redirect()->back();
		}
	}
	
	protected function excelform() {
		return Admin::form( Ship::class, function (Form $form) {
			$form->setAction( request()->url() );
			$form->display('id', 'ID');
			$form->file('excel' , '船期导入' );
		});
	}
	
	public function destroy($id)
	{
		$count = Flight::where('ship_id' , $id )->count();
		if( $count ) {
			return response()->json([
					'status'  => false,
					'message' => '该船还有在使用的航班记录',
			]);
		}
		if ($this->form()->destroy($id)) {
			return response()->json([
					'status'  => true,
					'message' => trans('admin::lang.delete_succeeded'),
			]);
		} else {
			return response()->json([
					'status'  => false,
					'message' => trans('admin::lang.delete_failed'),
			]);
		}
	}
}
