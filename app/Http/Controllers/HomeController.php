<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Port;
use App\Models\OrderGoods;
use Illuminate\Support\Facades\DB;
use App\Models\Order ;
use App\Models\OrderEntrust;
use App\Models\OrderSender;
use App\Models\OrderRecevier;
use Sherry\Cms\Models\Category;
use Sherry\Cms\Models\Advertisement;
use Sherry\Cms\Models\Posts;
use Sherry\Cms\Models\Single;
use App\Models\UserEntrust;
use App\Models\UserSender;
use App\Models\UserRecevier;
use App\User;
use Illuminate\Support\Facades\Cache;
use App\Support\Sms;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\OrderChangeLog;

use App\Models\Reward;
use App\Models\Finance;
use App\Models\FlightPortPrice;
use App\Models\FlightPrice;
use App\Models\Ship;
use App\Models\Flight;
use App\Models\FlightDate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();

    	$postsA = Posts::where('category_id' , 7 )->orderBy('updated_at' , 'asc')->orderBy('sort' , 'desc' )->orderBy('id' , 'desc')->take( 8 )->get();
    	$postsB = Posts::where('category_id' , 5 )->orderBy('updated_at' , 'asc')->orderBy('sort' , 'desc' )->orderBy('id' , 'desc')->take( 8 )->get();
    	$postsC = Posts::where('category_id' , 6 )->orderBy('updated_at' , 'asc')->orderBy('sort' , 'desc' )->orderBy('id' , 'desc')->take( 8 )->get();
    	$recId = request()->input('rec_id');
    	if( $recId ) {
    		session(['rec_id' => $recId ]) ;
    	}
    	$adv = Advertisement::whereIn('target_id' , function( $query ){
    		return $query->from('cms_adv_target')->where('slug' , 'PC_INDEX_BANNER' )->select(['id']);
    	})->orderBy('sort' , 'desc' )->take( 6 )->get();
    	$data = [
    			'ports' => $ports ,
    			'posts_A'  => $postsA ,
    			'posts_B'  => $postsB ,
    			'posts_C'  => $postsC ,
    			'adv' => $adv ,
    	] ;
        return view('home.index' , $data );
    }

    public function test() {
    	Reward::where('status' , 0 )->chunk( 100 , function( $reward ) {
    		foreach( $reward as $v ) {
	    		$cash = $v->cash ;
	    		$order = Order::findOrFail( $v->order_id );
	    		if( strtotime( $v->expect ) < time() ) {
	    			$v->status = 1 ;
	    			$v->save();
	    			$recUser = User::findOrFail( $v->user_id );
	    			$finance = new Finance() ;
	    			$finance->user_id = $v->user_id ;
	    			$finance->cash = $cash ;
	    			$finance->act = 'in' ;
	    			$finance->orgin_cash = $recUser->money ;
	    			$finance->result_cash = $recUser->money + $cash  ;
	    			$finance->type = 'reward' ;
	    			$finance->target_id = $v->id ;
	    			$finance->save();
	    			User::where('id' , $recUser->id )->where('money' , $recUser->money )->update(['money' => $finance->result_cash ] ) ;
	    		}
    		}
    	});
    }

    /**
     * 下单
     */
    public function checkin( Request $request ) {
    	$from = $request->input('shipment');
    	$to = $request->input('destinationport');

    	$user = auth()->guard()->user();
    	$entrust = UserEntrust::where('user_id' , $user->id )->orderBy('id' , 'desc')->get();
    	$sender = UserSender::where('user_id' , $user->id )->orderBy('id' , 'desc' )->get();
    	$recevier = UserRecevier::where('user_id' , $user->id )->orderBy('id' , 'desc' )->get();
    	$company = Company::pluck('name' , 'id' )->toArray();
    	$orderId = $request->input('order_id');
    	$order = [] ;
    	if( $orderId ) {
    		$order = Order::where('user_id' , $user->id )->where('id' , $orderId )->first();
    	}
    	$companyId = $request->input('company_id');
    	$ship = [] ;
    	if( $companyId ) {
    		$ship = Ship::where('company_id' , $companyId )->pluck( 'name' , 'id' )->toArray();
    	}

    	$shipId = $request->input('ship_id' );
    	$flight = [] ;
    	if( $shipId ) {
			$flight = Flight::where('ship_id' , $shipId )->pluck('no' , 'id')->toArray();
    	}

    	$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();
    	$data = [
    			'ports' => $ports ,
    			'entrust' => $entrust ,
    			'sender' => $sender ,
    			'recevier' => $recevier ,
    			'order' => $order ,
    			'company' => $company ,
    			'ship' => $ship ,
    			'flight' => $flight ,
    	] ;
    	return view('home.checkin' , $data ) ;
    }

    /**
     * 获取船信息
     */
    public function getship( Request $request ) {
    	$companyId = $request->input( 'id') ;
    	if( $companyId ) {
    		$ship = Ship::where('company_id' , $companyId )->pluck('name' , 'id')->toArray();
    		return response()->json(['errcode' => 0 , 'data' => $ship ]) ;
    	}
    	return response()->json(['errcode' => 0 , 'data' => [] ]) ;
    }

    /**
     * 获取航次信息
     */
    public function getflight( Request $request ) {
    	$shipId = $request->input( 'id') ;
    	if( $shipId ) {
    		$from = $request->input('from');
    		$to = $request->input('to');
    		$query = Flight::where('ship_id' , $shipId );
    		if( $from && $to ) {
    			$query->whereIn('id' , function( $query) use ( $from , $to , $shipId ) {
    				$query->from('flight_port_time');
    				$query->where('ship_id' , $shipId );
    				//开船时间大于今天
    				$query->where('leave_plan_date' , '>=' , time() );
    				return $query->select('flight_id');
    			}) ;
    		}
    		$flight = $query->pluck('no' , 'id' );
    		if( $flight ) {
    			$flight = $flight->toArray();
    		} else {
    			$flight = [] ;
    		}
    		return response()->json(['errcode' => 0 , 'data' => $flight ]) ;
    	}

    	return response()->json(['errcode' => 0 , 'data' => [] ]) ;
    }

    /**
     * 计算价格
     * @param unknown $id
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function checkprice( Request $request ) {
    	$from = $request->input('from');
    	$to = $request->input('to');
    	$companyId = $request->input('company_id');
    	$shipId = $request->input('ship_id');
    	$flightId = $request->input('flight_id');
    	$boxType = $request->input('box_type');
    	$boxNum = $request->input('box_num');
    	if( $from && $to && $flightId ) {
    		$price = FlightPortPrice::where('flight_id' , $flightId )
    		->where( 'from_port_id' , $from )
    		->where('to_port_id' , $to )
    		->first();
    		if( $price ) {
    			switch ( $boxType ) {
    				case 1:
    					$shipPrice = data_get( $price , 'price_20gp' , 0 );
    					break ;
    				case 2 :
    					$shipPrice = data_get( $price , 'price_20hp' , 0 );
    					break ;
    				case 3:
    					$shipPrice = data_get( $price , 'price_40gp' , 0 );
    					break ;
    				case 4:
    					$shipPrice = data_get( $price , 'price_40hq' , 0 );
    					break ;
    			}
    			if( $shipPrice > 0 ) {
    				return response()->json(['errcode' => 0 , 'data' => $shipPrice * $boxNum , 'msg' => 'OK' ]) ;
    			}
    			return response()->json(['errcode' => 0 , 'data' => 0 , 'msg' => 'price is 0' ]) ;
    		}
    		return response()->json(['errcode' => 0 , 'data' => 0 , 'msg' => 'bad record' ]) ;
    	}
    	return response()->json(['errcode' => 0 , 'data' => 0 , 'msg' => 'bad params']) ;
    }

    public function checkchange( $id ,  Request $request ) {
    	$from = $request->input('shipment');
    	$to = $request->input('destinationport');

    	$user = auth()->guard()->user();
    	$entrust = UserEntrust::where('user_id' , $user->id )->orderBy('id' , 'desc')->get();
    	$sender = UserSender::where('user_id' , $user->id )->orderBy('id' , 'desc' )->get();
    	$recevier = UserRecevier::where('user_id' , $user->id )->orderBy('id' , 'desc' )->get();
    	$company = Company::pluck('name' , 'id' )->toArray();
    	$orderId = $request->input('order_id');
    	$order = [] ;

    	$order = Order::where('user_id' , $user->id )->where('id' , $id )->first();
    	if( !$order ) {
    		redirect()->back();
    	}

    	$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();
    	$data = [
    			'ports' => $ports ,
    			'entrust' => $entrust ,
    			'sender' => $sender ,
    			'recevier' => $recevier ,
    			'order' => $order ,
    			'company' => $company ,
    	] ;
    	return view('home.checkinchange' , $data ) ;
    }

    public function updatecheckchange( $id ,  Request $request ) {
    	$user = auth()->guard()->user();
    	$order = Order::where('user_id' , $user->id )->where('id' , $id )->first();
    	if( !$order ) {
    		redirect()->back()->withInput();
    	}
    	$input = $request->all();
    	$goods = $request->input('goods');
    	$oldGoods = data_get( $order , 'goods' );



    	//对比变更的数据
    	$changeValue = [] ;
    	//对比商品
    	foreach( $goods as $k => $v ) {
    		if( data_get( $oldGoods , $k ) != data_get( $goods ,  $k ) ) {
    			$changeValue['goods.' . $k ] = [
    					'from' => data_get( $oldGoods , $k) ,
    					'to' => data_get( $goods , $k )
    			] ;
    		}
    	}
    	//对比委托人
    	$entrust = $request->input('entrust');
    	$oldEntrust = data_get( $order , 'entrust');
    	foreach( $entrust as $k => $v ) {
    		if( data_get( $oldEntrust , $k ) != data_get( $entrust ,  $k ) ) {
    			$changeValue['entrust.' . $k ] = [
    					'from' => data_get( $oldEntrust , $k) ,
    					'to' => data_get( $entrust , $k )
    			] ;
    		}
    	}
    	//对比发货信息
    	$sender = $request->input('sender');
    	$oldSender = data_get( $order , 'sender');
    	foreach( $sender as $k => $v ) {
    		if( data_get( $oldSender , $k ) != data_get( $sender ,  $k ) ) {
    			$changeValue['sender.' . $k ] = [
    					'from' => data_get( $oldSender , $k) ,
    					'to' => data_get( $sender , $k )
    			] ;
    		}
    	}
    	//对比收货人信息
    	$receiver = $request->input('recevier');
    	$oldReceiver = data_get( $order , 'recevier');
    	foreach( $receiver as $k => $v ) {
    		if( data_get( $oldReceiver , $k ) != data_get( $receiver ,  $k ) ) {
    			$changeValue['recevier.' . $k ] = [
    					'from' => data_get( $oldReceiver , $k) ,
    					'to' => data_get( $receiver , $k )
    			] ;
    		}
    	}
    	//对比基本信息
    	$baseKey = [
    			'shipment' , 'destinationport' , 'company_id' , 'transport_protocol' , 'goods_kind' , 'enable_ensure' ,
    			'ensure_name' , 'insure_goods_worth'
    	] ;

    	foreach( $baseKey as $k ) {
    		if( data_get( $order , $k ) != data_get( $input ,  $k ) ) {
    			$changeValue['order.' . $k ] = [
    					'from' => data_get( $order , $k) ,
    					'to' => data_get( $input , $k )
    			] ;
    		}
    	}
    	if( empty( $changeValue ) ) {
    		return response()->json(['errcode' => 0 , 'msg' => '您没有修改任何信息']) ;
    	}

    	if( $order->state == 0 || $order->state == 8 ) {
    		//如果客服没有接单 则直接更新订单信息
    		$input['state'] = 0 ;
    		$order->update( $input );
    		$order->goods->update( $goods );
    		$order->sender->update( $sender );
    		$order->recevier->update( $receiver );
    		$order->entrust->update( $entrust );
    		return response()->json(['errcode' => 0 , 'msg' => '变更完成']) ;
    	}

    	$changeLog = OrderChangeLog::create( [
    			'order_id' => $id ,
    			'user_id' => $user->id ,
    			'status' => 0 ,
    			'content' => json_encode( $changeValue ) ,
    			'admin_id' => 0 ,
    			'mark' => ''
    	] );
    	if( $changeLog ) {
    		return response()->json(['errcode' => 0 , 'msg' => '修改申请已提交']) ;
    	}
    	return response()->json(['errcode' => 10001 , 'msg' => '修改申请已提交']) ;

    }


    /**
     * 下单存储
     */
    public function check( Request $request ) {
    	$user = auth()->guard()->user();
    	$goods = $request->input('goods');
    	$iGoods = [] ;
    	if( !empty( $goods ) ) {
    		foreach( $goods["'name'"] as $k=> $val ) {
    			$iGoods[] = new OrderGoods([
    					'name' => $val ,
    					'box_type' =>  (int) data_get( data_get( $goods , 'box_type') , $k ) ,
    					'box_num' =>  (int) data_get( data_get( $goods , 'box_num') , $k ),
    					'total_num' =>  (int) data_get( data_get( $goods , 'total_num') , $k ),
    					'weight' =>  (int) data_get( data_get( $goods , 'weight') , $k ),
    					'cubage' => (int) data_get( data_get( $goods , 'cubage'), $k ) ,
    					'package' =>  (int) data_get( data_get( $goods , 'package_type') , $k ),
    			]);
    		}
    	}
    	$sender = [
    			'name' => $request->input('sender_fullname') ,
    			'contact_name' => $request->input('sender_contactname') ,
    			'mobile' => $request->input('sender_mobile') ,
    			'email' => $request->input('sender_email') ,
    			'address' => $request->input('sender_address') ,
    			'load_date' => $request->input('sender_date') ,
    	];
    	$entrust = [
    			'name' => $request->input('entrust_fullname') ,
    			'contact' => $request->input('entrust_contactname') ,
    			'mobile' => $request->input('entrust_mobile') ,
    	] ;
    	$recevier = [
    			'name' => $request->input('recevier_fullname') ,
    			'contact_name' => $request->input('recevier_contactname') ,
    			'mobile' => $request->input('recevier_mobile') ,
    			'email' => $request->input('recevier_email') ,
    			'address' => $request->input('recevier_address') ,
    			'id_no' => $request->input('recevier_id_no') ,
    	] ;

    	$order = new Order();
    	$order->user_id = $user->id ;
    	$order->flight_id = (int) $request->input('flight_id') ;
    	$order->remark = $request->input('remark');
    	$order->shipment = $request->input('shipment') ;
    	$order->destinationport = $request->input('destinationport') ;
    	$order->transport_protocol = $request->input('transport_protocol') ;
    	$order->goods_kind = $request->input('goods_kind') ;
    	$order->company_id = $request->input('company_id') ;
    	//$order->company_id = 0 ;
    	$order->ship_id = (int) $request->input('ship_id') ;
    	$order->voyage = '' ;
    	if( $order->flight_id ) {
    		$flight = Flight::findOrFail( $order->flight_id );
    		$order->voyage = $flight->no ;
    	}
    	$order->cabinet = 0 ;
    	$order->cabinet_num = 0 ;
    	$order->departure = '' ;
    	$order->destination = '' ;
    	$order->trailer_cost = 0 ;
    	$order->ship_cost = $request->input('ship_cost' , 0 ) ;
    	$order->other_cost = 0 ;
    	$order->costinfo = '' ;
    	$order->state = 0 ;
    	//$order->barge_time = 0 ;
    	//$order->start_time = '' ;
    	//$order->end_time = '' ;
    	$order->admin_id = 0 ;
    	$order->rebate = 0 ;
    	$order->rebate_status = 0 ;
    	$order->seal_num = '' ;
    	$order->cabinet_no = '' ;
    	$order->order_sn = '' ;
    	$order->file = '' ;
    	$order->enable_ensure = $request->input('need_insure');
    	$order->ensure_name = $request->input('insure_name');
    	$order->insure_goods_worth = (float) $request->input('insure_goods_worth');
    	$setMode = $request->input('is_default') ? 1 : 0 ;
    	DB::beginTransaction();
    	try {
    		if( !$order->save() ) {
    			throw new \Exception("订单保存失败");
    		}
    		if( !$order->goods()->saveMany( $iGoods ) ) {
    			throw new \Exception('商品添加失败') ;
    		}
    		if( !$order->entrust()->save( new OrderEntrust( $entrust ) ) ) {
    			throw new \Exception('商品添加失败') ;
    		}
    		if( !$order->sender()->save( new OrderSender( $sender ) ) ) {
    			throw new \Exception('商品添加失败') ;
    		}
    		if( !$order->recevier()->save( new OrderRecevier( $recevier ) ) ) {
    			throw new \Exception('商品添加失败') ;
    		}
    		if( $setMode ) {
    			$entrust['user_id'] = $user->id ;
    			$userEntrust = UserEntrust::firstOrCreate( $entrust ) ;
    			if( !$userEntrust ) {
    				throw new \Exception("添加委托人模板失败") ;
    			}
    			$sender['user_id'] = $user->id ;
    			unset( $sender['load_date'] ) ;
    			$userSender = UserSender::firstOrCreate( $sender );
    			if( !$userSender ) {
    				throw new \Exception("添加发货人模板失败") ;
    			}
    			$recevier['user_id'] = $user->id ;
    			$userRecevier = UserRecevier::firstOrCreate( $recevier );
    			if( !$userRecevier ) {
    				throw new \Exception("添加收货人模板失败") ;
    			}
    		}

    		DB::commit();
    		return response()->json(['errcode' => 0 , 'msg' => '下单成功']) ;
    	} catch( \Exception $e ) {
    		DB::rollback();
    		return response()->json(['errcode' => 10001 , 'msg' => '下单失败' . $e->__toString()]) ;
    	}

    }


    public function posts( $id ) {


    	//获取分类
    	$category = Category::where('parent_id' , 0 )->pluck('name' , 'id');
    	$leftSlug = 'PC_POST_LEFT_' . $id ;
    	$slug = 'PC_ADV_' . $id ;

    	//获取广告
    	$leftadv = Advertisement::whereIn('target_id' , function( $query ) use( $leftSlug ) {
    		return $query->from('cms_adv_target')->where('slug' , $leftSlug )->select(['id'] );
    	})->where('display' , 1 )->orderBy('id' , 'desc' )->first();

    	$adv = Advertisement::whereIn('target_id' , function( $query ) use( $slug ) {
    		return $query->from('cms_adv_target')->where('slug' , $slug )->select(['id'] );
    	})->where('display' , 1 )->orderBy('id' , 'desc' )->first();

    	$posts = Posts::where('category_id' , $id )->orderBy('updated_at' , 'desc' )->orderBy('id' , 'desc')->paginate( 10 );
    	$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();
    	$data = [
    			'id' => $id ,
    			'category' => $category ,
    			'posts' => $posts ,
    			'adv' => $adv ,
    			'leftadv' => $leftadv ,
    			'ports' => $ports ,
    			'menu' => 'news' ,
    	] ;
    	return view('home.posts' , $data );
    }

    public function postsdetail( $id ) {
    	$posts = Posts::findOrFail( $id );
    	if( $posts->link ) {
    		return redirect( $posts->link );
    	}
    	$category = Category::where('parent_id' , 0 )->pluck('name' , 'id');
    	$slug = 'PC_ADV_' . $posts->category_id ;

    	//获取广告
    	$adv = Advertisement::whereIn('target_id' , function( $query ) use( $slug ) {
    		return $query->from('cms_adv_target')->where('slug' , $slug )->select(['id'] );
    	})->where('display' , 1 )->orderBy('id' , 'desc' )->first();
    	$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();
    	$data = [
    			'category' => $category ,
    			'posts' => $posts ,
    			'ports' => $ports ,
    			'adv' => $adv ,
    	] ;
    	return view( 'home.postsdetail' , $data );
    }


    public function page( $id ) {
    	$page = Single::findOrFail( $id );
    	$leftSlug = 'PC_ADV_SINGLE_LEFT_' . $id ;
    	$slug = 'PC_ADV_SINGLE_' . $id ;

    	//获取广告
    	$leftadv = Advertisement::whereIn('target_id' , function( $query ) use( $leftSlug ) {
    		return $query->from('cms_adv_target')->where('slug' , $leftSlug )->select(['id'] );
    	})->where('display' , 1 )->orderBy('id' , 'desc' )->first();

    	$adv = Advertisement::whereIn('target_id' , function( $query ) use( $slug ) {
    		return $query->from('cms_adv_target')->where('slug' , $slug )->select(['id'] );
    	})->where('display' , 1 )->orderBy('id' , 'desc' )->first();
    	$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();
    	$category = Category::where('parent_id' , 0 )->pluck('name' , 'id');
    	$data = [
    			'category' => $category ,
    			'page' => $page ,
    			'ports' => $ports ,
    			'adv' => $adv ,
    			'leftadv' => $leftadv ,
    	] ;
    	return view( 'home.page' , $data );
    }

    public function track( Request $request ) {
    	$queryString = $request->input('waybill') ;
    	$order = [] ;
    	$from = [] ;
    	$to = [] ;
    	if( $queryString ) {
    		$order = Order::with('flight' , 'flight.dates' )->where('order_sn' , $queryString )->orWhere('waybill' , $queryString )
    	->orWhere('cabinet_no' , 'like' , "%$queryString%" )->first();

    		//获取这个订单的起止港 口信息
    		if( $order &&  $order->flight && $order->flight->dates ) {

    			foreach( $order->flight->dates as $v ) {
    				if( $order->barge_port > 0 ) {
	    				if( $v->port_id == $order->barge_port ) {
	    					$from = $v ;
	    				}
    				} else {
    					if( $v->port_id == $order->shipment ) {
    						$from = $v ;
    					}
    				}
    				if( $order->barge_to_port > 0 ) {
    					if( $v->port_id == $order->barge_to_port ) {
    						$to = $v ;
    					}
    				} else {
	    				if( $v->port_id == $order->destinationport ) {
	    					$to = $v ;
	    				}
    				}
    			}
    		}
    	}

    	return view('home.track' , ['order' => $order , 'from' => $from , 'to' => $to ]);
    }

    public function sendsms( Request $request ) {
    	$type = $request->input('type') ;
    	if( 'reg' == $type ) {
    		return $this->sendReg( $request );
    	}
    	if( 'findpwd' == $type ) {
    		return $this->sendFindpwd( $request );
    	}
    	return response()->json(['errcode' => 10001 , 'msg' => '不合法的短信发送类型']) ;

    }

    protected function sendReg( Request $request ) {
    	$mobile = $request->input('mobile');
    	if( !$mobile ) {
    		return response()->json(['errcode' => 10001 , 'msg' => '参数不正确']) ;
    	}
    	$count = User::where('name' , $mobile )->count();
    	if( $count ) {
    		return response()->json(['errcode' => 10002 , 'msg' => '手机号已经注册']) ;
    	}
    	$key = "verify_" . $mobile . ":reg" ;
    	$verify = Cache::get( $key );
    	if( $verify ) {
    		return response()->json( ['errcode' => 10002 , 'msg' => '你发送的太快'] ) ;
    	}
    	$random = rand( 100000, 999999 );
    	$sms = new Sms( config( 'global.sms_app_id') , config( 'global.sms_app_key') , config( 'global.sms_sign') ) ;
    	$tpl = config('global.sms_reg_tpl') ;
    	$param = ['code' => "{$random}" , 'product' => config('app.name') ] ;

    	$result = $sms->sdkSend( $mobile , $param , $tpl );
    	if( $result ) {
    		Cache::put( $key , $random , 3 );
    		return response()->json( ['errcode' => 0 , 'left' => 60 , 'msg' => '发送成功'] );
    	}
    	return response()->json( ['errcode' => 10001 , 'left' => 60 , 'msg' => '发送失败' ] );
    }

    protected function sendFindpwd( Request $request ) {
    	$mobile = $request->input('mobile');
    	if( !$mobile ) {
    		return response()->json(['errcode' => 10001 , 'msg' => '参数不正确']) ;
    	}
    	$count = User::where('name' , $mobile )->count();
    	if( !$count ) {
    		return response()->json(['errcode' => 10002 , 'msg' => '手机号还没有注册']) ;
    	}
    	$key = "verify_" . $mobile . ":findpwd" ;
    	$verify = Cache::get( $key );
    	if( $verify ) {
    		return response()->json( ['errcode' => 10002 , 'msg' => '你发送的太快'] ) ;
    	}
    	$random = rand( 100000, 999999 );
    	$sms = new Sms( config( 'global.sms_app_id') , config( 'global.sms_app_key') , config( 'global.sms_sign') ) ;
    	$tpl = config('global.sms_findpwd_tpl') ;
    	$param = ['code' => "{$random}" , 'product' => config('app.name') ] ;
    	$result = $sms->sdkSend( $mobile , $param , $tpl );
    	if( $result ) {
    		Cache::put( $key , $random , 3 );
    		return response()->json( ['errcode' => 0 , 'left' => 60 , 'msg' => '发送成功'] );
    	}
    	return response()->json( ['errcode' => 10001 , 'left' => 60 , 'msg' => '发送失败' ] );
    }



    public function findpwd( Request $request ) {
    	$phone = $request->get('mob');
    	$code = $request->get('vcode');
    	$password = $request->get('pwd');
    	//TODO 检查code是不是可以用
    	if( config('app.supper_verify') != $code ) {
    		$key = "verify_" . $phone . ":findpwd";
    		$verify = Cache::get( $key );
    		if( !$verify ) {
    			return response()->json(['errcode' => 10002 , 'msg' => '验证码失效']);
    		}
    		if( $verify != $code ) {
    			return response()->json(['errcode' => 10003 , 'msg' => '验证码不正确']);
    		}
    	}

    	$user = User::where('name' , $phone )->first();
    	if( empty( $user ) ) {
    		return response()->json( ['errcode' => 20001 , 'msg' => '手机号还没有注册' ]);
    	}

    	if( $user->forceFill([
    			'password' => bcrypt($password),
    			'remember_token' => Str::random(60),
    	])->save() ) {
    		auth()->guard('web')->loginUsingId( $user->id );
    		return response()->json( ['errcode' => 0 , 'msg'=> '修改成功' ]);
    	}
    	return response()->json( ['errcode' => 10004 , 'msg'=> '修改失败' ]);
    }


    public function portprice( Request $request ) {
    	$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();
    	$port = [] ;
    	foreach( $ports as $p ) {
    		$port[ $p['id'] ] = $p ;
    	}
    	/*
    	$query = FlightPortPrice::with( 'flight' , 'flight.dates' , 'flight.ship')
    	//->join('flight_port_time' , 'flight_port_time.port_id' , '=' , 'flight_port_to_port_price.from_port_id')
    	//->on('flight_port_time.flight_id', '=', 'flight_port_to_port_price.flight_id')
    	->leftJoin('flight_port_time' ,function( $join ){
    		return $join->on( 'flight_port_time.port_id' , '=' , 'flight_port_to_port_price.from_port_id' )
    		->on('flight_port_time.flight_id', '=', 'flight_port_to_port_price.flight_id');
    	})
    	->orderBy('flight_port_time.leave_plan_date' , 'desc')
    	->orderBy('flight_port_to_port_price.id' , 'asc' );
    	*/
    	$query = FlightPortPrice::with( 'flight' , 'flight.dates' , 'flight.ship')
    	//->join('flight_port_time' , 'flight_port_time.port_id' , '=' , 'flight_port_to_port_price.from_port_id')
    	->orderBy('from_port_leave_time' , 'asc')
    	->orderBy('id' , 'asc' );
    	$fromPort = $request->input('fromport');
    	$toPort = $request->input('toport');
    	$date = $request->input('date');
    	$flightId = [] ;
    	$query->where( function( $query) {
    		return $query->where('price_20gp' , '>' , 0 )
    		->orWhere('price_20hp' , '>' , 0 )
    		->orWhere('price_40gp' , '>' , 0 )
    		->orWhere('price_40hq' , '>' , 0 );
    	});
    	if( $fromPort && $toPort ) {
    		$query->where('from_port_id' , $fromPort )->where( 'to_port_id' , $toPort );
    	}
    	$query->where('from_port_leave_time' , '>' , time() );
    	if( $date ) {
    		$date = strtotime( $date );
    		$query->where('from_port_leave_time' , '>' , $date );
    	}

    	/**
    	$query->whereIn('flight_id' , function( $query) use( $date , $fromPort ) {
			$date = strtotime( $date );
			$query->from('flight_port_time')
			->where( 'leave_plan_date' , '>' , time() );
			if( $date ) {
				$query->where('leave_plan_date' , '>=' , $date );
			}
			if( $fromPort ) {
				$query->where('port_id' , $fromPort );
			}
			return $query->select('flight_id');
		});
		**/
    	$page = $query->paginate( 10 );

    	//获取最新推荐的5条航线
    	$recommend = FlightPrice::where('display' , 1 )->where('is_recommend' , 1)->take( 10 )->get(); ;
    	$hot = FlightPrice::where('display' , 1 )->where('is_hot' , 1)->take( 20 )->get(); ;

    	//获取船公司信息
    	$c = Company::all();
    	$company = [] ;
    	foreach( $c as $v ) {
    		$company[ $v->id ] = $v ;
    	}

    	//dd( $page );
    	$data = [
    			'page' => $page ,
    			'ports' => $port ,
    			'recommend' => $recommend ,
    			'company' => $company ,
    			'hot' => $hot ,
    	] ;
    	return view('home.portprice' , $data );
    }

    public function flightlist( Request $request ) {
    	$id = $request->input('id');
    	$query = FlightPrice::where('display' , 1 )
    	->orderBy('is_recommend' , 'desc' ) ;
    	if( $id ) {
    		$query->where('is_promotion' , $id );
    	}
    	$flight = $query ->orderBy('id' , 'desc' )->paginate( 25 );

    	$data = [
    			'page' => $flight
    	] ;
    	return view('home.flight' , $data ) ;
    }
}
