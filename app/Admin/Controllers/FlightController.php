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
use App\Models\Flight ;
use App\Models\Port;
use App\Models\Ship;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Box;
use App\Models\FlightDate;
use App\Models\FlightPrice;
use App\Models\FlightPortPrice;
use App\Models\Company;

class FlightController extends Controller {
	use ModelForm ;
	
	public function index( $id = 0 ) {
		return Admin::content(function (Content $content) use( $id ) {
		
			$content->header('航次管理');
			$content->description('航次管理');
		
			$content->body($this->grid( $id ));
		});
	}
	
	public function show( $id ) {
		return $this->index( $id );
	}
	
	protected function grid( $id ) {
		return Admin::grid( Flight::class, function (Grid $grid) use ( $id ) {
			if( $id > 0  ) {
				$grid->model()->where('ship_id' , $id );
			}
			$grid->model()->with('ship' , 'ship.company');
			$grid->model()->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->column('ship.company' , '船名称')->display( function( $v ){
				return data_get( $v , 'name' );
			});
			$grid->column('ship.name' , '船名称');
			$grid->no('航次名称');
			$grid->from( '起始港');
			$grid->to( '目的港');
			$grid->from_time( '预计开船时间')->display( function( $v ){
				return date('Y-m-d' , $v );
			});
			$grid->to_time( '预计到港时间')->display( function( $v ){
				return date('Y-m-d' , $v );
			});
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->filter( function( $filter ){
				$filter->disableIdFilter();
				$filter->where(function( $query ){
					$input = $this->input ;
					return $query->whereIn('ship_id' , function( $query ) use( $input ) {
						return $query->from('ship')->where('company_id' , $input )->select('id');
					});
				} , '船公司' )->select( Company::pluck('name' , 'id' ) );
				$filter->is('ship_id' , '船名')->select( Ship::pluck('name' , 'id' ) );
				$filter->like('no' ,'航次');
				$filter->equal( 'from' , '起运港')->select(Port::where('parent_id' , '>' , 0 )->pluck('name', 'name'));
				$filter->equal( 'to' , '目的港')->select(Port::where('parent_id' , '>' , 0 )->pluck('name' , 'name'));
				$filter->where( function( $query ){
					$input = $this->input ;
					$query->whereIn( 'id' , function( $query ) use ( $input ) {
						$query->from('flight_port_time')->where( 'leave_plan_date' , '>=' , strtotime( $input ) )->select('flight_id');
					} );
				} , '发船日期' )->dateTime();
				
			});
			$grid->resource('/admin/flight');
			$grid->actions( function( $actions ){
				$url = route('admin.flight.price' , ['id'=> $actions->row->id ] );
				$actions->append("<a href='{$url}'>价格管理</a>");
			});
			//$grid->disableCreation();
		});
	}
	
	public function create() {
		$css = [
				'/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
		];
		
		$js = [
				'/packages/admin/moment/min/moment-with-locales.min.js',
				'/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
		];
		
		Admin::css( $css );
		Admin::js($js);
		Admin::script( view('admin.flight.flightformscript' )->render() );
		return Admin::content(function (Content $content) {
		
			$content->header('航次');
			$content->description('新增');
			
			$ship = Ship::pluck('name' , 'id' );
			if( $ship ) {
				$ship = $ship->toArray();
			}
			
			$ports = Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' );
			if( $ports ) {
				$ports = $ports->toArray();
			}
			
			$data = [
					'ship' => $ship ,
					'ports' => $ports ,
			] ;
			
			$content->body( view('admin.flight.flightform' ,  $data ) );
		});
	}
	
	
	
	public function store( Request $request ) {
		$shipId = $request->input('ship_id');
		if( !$shipId ) {
			admin_toastr( '请选择船只信息' );
			return back()->withInput();
		}
		$no = $request->input('no');
		if( !$no ) {
			admin_toastr( '请填写航次名称' );
			return back()->withInput();
		}
		$flight = Flight::where('ship_id' , $shipId )->where('no' , $no )->first();
		if( $flight ) {
			admin_toastr( '同航次的信息已经存在' );
			return back()->withInput();
		}
		
		$fromPortId = $request->input('from_port_id') ;
		$toPortId = $request->input('to_port_id') ;
		$fromPortPlanDate = $request->input('from_port_plan_date') ;
		$fromPortActualDate = $request->input('from_port_actual_date') ;
		$toPortPlanDate = $request->input('to_port_plan_date');
		$toPortActualDate = $request->input('to_port_actual_date');
		//这里OK了后 添加航次信息
		$fromPort = Port::findOrFail( $fromPortId );
		$toPort = Port::findOrFail( $toPortId );
		$portId = $request->input( 'port_id') ;
		$arrive_plan_date = $request->input('arrive_plan_date');
		$arrive_actual_date = $request->input('arrive_actual_date');
		$leave_plan_date = $request->input('leave_plan_date');
		$leave_actual_date = $request->input('leave_actual_date');
		$ports = [] ;
		//起运港
		$from = [
				'port_id' => $fromPortId ,
				'ship_id' => $shipId ,
				'port_name' => $fromPort->name ,
				'arrive_plan_date' => 0 ,
				'arrive_actual_date' => 0 ,
				'leave_plan_date' => strtotime( $fromPortPlanDate ) ,
				'leave_actual_date' => strtotime( $fromPortActualDate ) ,
				
		] ;
		//目的港
		$to = [
				'port_id' => $toPortId ,
				'ship_id' => $shipId ,
				'port_name' => $toPort->name ,
				'arrive_plan_date' => strtotime( $toPortPlanDate ) ,
				'arrive_actual_date' => strtotime( $toPortActualDate ) ,
				'leave_plan_date' => 0 ,
				'leave_actual_date' => 0 ,
		] ;
		if( is_array( $portId ) && !empty( $portId ) ) {
			foreach( $portId as $k => $val ) {
				$port = Port::findOrFail( $val );
				$ports[] = [
						'ship_id' => $shipId ,
						'port_id' => $val ,
						'port_name' => $port->name ,
						'arrive_plan_date' => data_get( $arrive_plan_date , $k ) ? strtotime( data_get( $arrive_plan_date , $k ) ) : 0 ,
						'arrive_actual_date' => data_get( $arrive_actual_date , $k ) ? strtotime( data_get( $arrive_actual_date , $k ) ) : 0 ,
						'leave_plan_date' => data_get( $leave_plan_date , $k ) ? strtotime( data_get( $leave_plan_date , $k ) ) : 0 ,
						'leave_actual_date' => data_get( $leave_actual_date , $k ) ? strtotime( data_get( $leave_actual_date , $k ) ) : 0 ,
				] ;
			}
		}
		//创建航线
		array_push($ports, $to) ;
		array_unshift( $ports, $from);
		$flightTime = [] ;
		foreach( $ports as $p ) {
			$flightTime[] = new FlightDate( $p );
		}
		
		$flight = Flight::create([
				'ship_id' => $shipId ,
				'no' => $no ,
				'from' => $fromPort->name ,
				'to' => $toPort->name ,
				'from_time' => strtotime( $fromPortPlanDate ) ,
				'to_time' => strtotime( $toPortPlanDate )
		]);
		if( $flight ) {
			$flight->dates()->saveMany( $flightTime );
			admin_toastr( '创建成功' );
			return redirect( route( 'admin.flight.price' , ['id' => $flight->id ] ) );
		}
		admin_toastr( '创建失败' );
		return back()->withInput();
		
	}
	
	
	public function edit( $id ) {
		$css = [
				'/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
		];
		
		$js = [
				'/packages/admin/moment/min/moment-with-locales.min.js',
				'/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
		];
		
		Admin::css( $css );
		Admin::js($js);
		Admin::script( view('admin.flight.flightformscript' )->render() );
		return Admin::content(function (Content $content) use( $id ) {
		
			$content->header('航次');
			$content->description('新增');
				
			$ship = Ship::pluck('name' , 'id' );
			if( $ship ) {
				$ship = $ship->toArray();
			}
				
			$ports = Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' );
			if( $ports ) {
				$ports = $ports->toArray();
			}
			
			$flight = Flight::with('dates')->findOrFail( $id );
			
			$dates = $flight->dates()->get();
			if( $dates ) {
				$dates = $dates->toArray();
			} else {
				$dates = [] ;
			}
			
			$data = [
					'ship' => $ship ,
					'ports' => $ports ,
					'from' => array_shift( $dates ) ,
					'to' => array_pop( $dates ) ,
					'dates' => $dates ,
					'flight' => $flight ,
			] ;
				
			$content->body( view('admin.flight.flightedit' ,  $data ) );
		});
	}
	
	public function update( $id , Request $request ) {
		$flight = Flight::findOrFail( $id );
		$shipId = $request->input('ship_id');
		if( !$shipId ) {
			admin_toastr( '请选择船只信息' );
			return back()->withInput();
		}
		$no = $request->input('no');
		if( !$no ) {
			admin_toastr( '请填写航次名称' );
			return back()->withInput();
		}
		$flightCheck = Flight::where('ship_id' , $shipId )->where('no' , $no )->first();
		if( $flightCheck ) {
			if( $flightCheck->id != $id ) {
				admin_toastr( '同航次的信息已经存在' );
				return back()->withInput();
			}
		}
		$flight->ship_id = $shipId ;
		$no = $request->input('no');
		if( !$no ) {
			admin_toastr( '请填写航次名称' );
			return back()->withInput();
		}
		$fromPortId = $request->input('from_port_id') ;
		$toPortId = $request->input('to_port_id') ;
		$fromPortPlanDate = $request->input('from_port_plan_date') ;
		$fromPortActualDate = $request->input('from_port_actual_date') ;
		$toPortPlanDate = $request->input('to_port_plan_date');
		$toPortActualDate = $request->input('to_port_actual_date');
		//这里OK了后 添加航次信息
		$fromPort = Port::findOrFail( $fromPortId );
		$toPort = Port::findOrFail( $toPortId );
		$portId = $request->input( 'port_id') ;
		$arrive_plan_date = $request->input('arrive_plan_date');
		$arrive_actual_date = $request->input('arrive_actual_date');
		$leave_plan_date = $request->input('leave_plan_date');
		$leave_actual_date = $request->input('leave_actual_date');
		$ports = [] ;
		$tmpId = [] ;
		//起运港
		$from = [
				'port_id' => $fromPortId ,
				'ship_id' => $flight->ship_id ,
				'port_name' => $fromPort->name ,
				'arrive_plan_date' => 0 ,
				'arrive_actual_date' => 0 ,
				'leave_plan_date' => strtotime( $fromPortPlanDate ) ,
				'leave_actual_date' => strtotime( $fromPortActualDate ) ,
		
		] ;
		$tmpId[] = $fromPortId ;
		//目的港
		$to = [
				'port_id' => $toPortId ,
				'ship_id' =>  $flight->ship_id ,
				'port_name' => $toPort->name ,
				'arrive_plan_date' => strtotime( $toPortPlanDate ) ,
				'arrive_actual_date' => strtotime( $toPortActualDate ) ,
				'leave_plan_date' => 0 ,
				'leave_actual_date' => 0 ,
		] ;
		$tmpId[] = $toPortId ;
		if( is_array( $portId ) && !empty( $portId ) ) {
			foreach( $portId as $k => $val ) {
				$port = Port::findOrFail( $val );
				$tmpId[] = $val ;
				$ports[] = [
						'ship_id' =>  $flight->ship_id ,
						'port_id' => $val ,
						'port_name' => $port->name ,
						'arrive_plan_date' => data_get( $arrive_plan_date , $k ) ? strtotime( data_get( $arrive_plan_date , $k ) ) : 0 ,
						'arrive_actual_date' => data_get( $arrive_actual_date , $k ) ? strtotime( data_get( $arrive_actual_date , $k ) ) : 0 ,
						'leave_plan_date' => data_get( $leave_plan_date , $k ) ? strtotime( data_get( $leave_plan_date , $k ) ) : 0 ,
						'leave_actual_date' => data_get( $leave_actual_date , $k ) ? strtotime( data_get( $leave_actual_date , $k ) ) : 0 ,
				] ;
			}
		}
		//创建航线
		array_push($ports, $to) ;
		array_unshift( $ports, $from);
		$flightTime = [] ;
		foreach( $ports as $p ) {
			$flightTime[] = new FlightDate( $p );
		}
		
		$result = $flight->update([
				'ship_id' =>  $flight->ship_id ,
				'no' => $no ,
				'from' => $fromPort->name ,
				'to' => $toPort->name ,
				'from_time' => strtotime( $fromPortPlanDate ) ,
				'to_time' => strtotime( $toPortPlanDate )
		]);
		if( $result ) {
			//需要删除 没有用的价格
			/**
			FlightPortPrice::where('flight_id' , $flight->id )->where(function( $query ) use( $tmpId ) {
				return $query->whereNotIn('from_barge_port_id' , $tmpId )->orWhereNotIn( 'to_barge_port_id' , $tmpId );
			})->delete();
			**/
			
			$flight->dates()->delete();
			$flight->dates()->saveMany( $flightTime );
			admin_toastr( '修改成功' );
			return back();
			//return redirect( route( 'admin.flight.index' ) );
		}
		admin_toastr( '修改失败' );
		return back()->withInput();
	}
	
	public function priceform( $id , Request $request ) {
		Admin::css('/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css');
		Admin::js([
	        '/packages/admin/moment/min/moment-with-locales.min.js',
	        '/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
	    ]);
		Admin::script( view('admin.script.priceform')->render() );
		return Admin::content(function (Content $content) use( $id ) {
			$content->header('航班价格设置');
			$content->description('价格设置');
			$box = new Box();
			$box->title("航班价格设置");
			$flight = Flight::findOrFail( $id );
			$flightDate = FlightDate::where('flight_id' , $id )->orderBy('id' , 'asc')->pluck('port_id');
			$ports = Port::whereIn('id' , $flightDate )->pluck('name' , 'id' )->toArray();
			$prices = FlightPortPrice::where('flight_id' , $id )->get();
			
			$allPorts = Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' )->toArray();

			$data = [
					'all_port' => $allPorts ,
					'ports' => $ports ,
					'port_id' => $flightDate ,
					'id' => $id ,
					'flight' => $flight ,
					'prices' => $prices ,
			] ;
			
			$box->content( view('admin.flight.priceform' , $data )->render() );
			$content->body( $box );
			
		});
	}
	
	public function pricestore( $id , Request $request ) {
		$price = $request->input('price');
		$promotion = $request->input('promotion');
		$fromPort = $request->input('from_port_id');
		$fromBargePort = $request->input('from_barge_port_id');
		$toPort = $request->input('to_port_id');
		$toBargePort = $request->input('to_barge_port_id');
		$from_port_leave_time = $request->input('from_port_leave_time') ;
		$from_port_barge_arrive_time = $request->input( 'from_barge_port_arrive_time' ) ;
		$to_port_leave_time = $request->input('to_port_leave_time' );
		$to_barge_port_arrive_time = $request->input( 'to_barge_port_arrive_time' ) ;
		$flight = Flight::findOrFail( $id );
		if( is_array( $fromPort ) ) {
			$flight->prices()->delete();
			$base = [] ;
			foreach( $fromPort as $k => $val ) {
				$base[] = new FlightPortPrice([
						'flight_id' => $id ,
						'from_port_id' => $val ,
						'from_barge_port_id' => data_get( $fromBargePort , $k ) ,
						'to_port_id' => data_get( $toPort , $k ) ,
						'to_barge_port_id' => data_get( $toBargePort , $k ) ,
						'price_20gp' => (int) data_get( data_get( $price , '20GP') , $k , 0 ) ,
						'price_20hp' => (int) data_get( data_get( $price , '20HP') , $k , 0 ) ,
						'price_40gp' => (int) data_get( data_get( $price , '40GP') , $k , 0 ) ,
						'price_40hq' => (int) data_get( data_get( $price , '40HQ') , $k , 0 ) ,
						'is_promotion_20gp' => (int) data_get( data_get( $promotion , '20GP' )  , $k , 0 ) ,
						'is_promotion_20hp' => (int) data_get( data_get( $promotion , '20HP' )  , $k , 0 ) ,
						'is_promotion_40gp' => (int) data_get( data_get( $promotion , '40GP' )  , $k , 0 ) ,
						'is_promotion_40hq' => (int) data_get( data_get( $promotion , '40HQ' )  , $k , 0 ) ,
						'from_port_leave_time' => data_get( $from_port_leave_time , $k ) ,
						'from_barge_port_arrive_time' => data_get( $from_port_barge_arrive_time , $k ) ,
						'to_port_leave_time' => data_get( $to_port_leave_time , $k ) ,
						'to_barge_port_arrive_time' => data_get( $to_barge_port_arrive_time , $k )  ,
				]) ;
			}
			if( count( $base ) ) {
				
				$result = $flight->prices()->saveMany( $base );
				admin_toastr( '保存成功' );
				return back();
			}
			admin_toastr( '没有要保存的数据' , 'error' );
			return back()->withInput();
		}
		admin_toastr( '参数错误' , 'error' );
		return back()->withInput();
	}
	
	public function destroy( $id ) {
		$flight = Flight::findOrFail( $id );
		if( $flight->dates ) {
			$flight->dates()->delete();
		}
		if( $flight->prices ) {
			$flight->prices()->delete();
		}
		
		if (Flight::destroy($id)) {
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