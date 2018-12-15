<?php
namespace App\Http\Controllers\Wap ;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use Cache ;
use App\Models\UserRsync ;
use Sherry\Cms\Models\Posts ;
use Sherry\Cms\Models\Advertisement ;
use Sherry\Cms\Models\Single ;
use App\Models\UserEntrust;
use App\Models\UserSender;
use App\Models\UserRecevier;
use App\Models\Order ;
use App\Models\OrderEntrust;
use App\Models\OrderSender;
use App\Models\OrderRecevier;
use App\Models\Port ;
use App\Models\Company ;
use App\Models\OrderGoods;
use App\Models\FlightPortPrice;
use App\Models\FlightPrice;
use Illuminate\Support\Facades\DB;
use EasyWeChat ;
use App\Models\Flight;
use App\Models\Ship;

class HomeController extends Controller {

	public function __construct() {
		$js = EasyWeChat::js();
		view()->share('js' , $js );
	}

	public function index() {
		$recId = request()->input('rec_id');
		if( $recId ) {
			session(['rec_id' => $recId ]) ;
		}
		$subscribe = false ;
		$isWexin = false ;
		if( session('wechat') ) {
			$isWexin = true ;
			$wechatInfo = session('wechat');
			$wechat = EasyWechat::user()->get( $wechatInfo ['oauth_user']->id ) ;
			$subscribe = data_get( $wechat , 'subscribe') == 1 ? false : true ;
		}
		$postsA = Posts::where('category_id' , 7 )->orderBy('updated_at' , 'asc')->orderBy('sort' , 'desc' )->orderBy('id' , 'desc')->take( 8 )->get();
		$postsB = Posts::where('category_id' , 5 )->orderBy('updated_at' , 'asc')->orderBy('sort' , 'desc' )->orderBy('id' , 'desc')->take( 8 )->get();
		$postsC = Posts::where('category_id' , 6 )->orderBy('updated_at' , 'asc')->orderBy('sort' , 'desc' )->orderBy('id' , 'desc')->take( 8 )->get();
		$adv = Advertisement::whereIn('target_id' , function( $query ){
			return $query->from('cms_adv_target')->where('slug' , 'WAP_INDEX_BANNER' )->select(['id']);
		})->orderBy('sort' , 'desc' )->take( 6 )->get();
		$data = [
				'posts_A'  => $postsA ,
				'posts_B'  => $postsB ,
				'posts_C'  => $postsC ,
				'adv' => $adv ,
				'is_weixin' => $isWexin ,
				'subscribe' => $subscribe
		] ;
		return view('wap.home.index' , $data ) ;
	}

	public function portprice( Request $request ) {
		$hotPort = Port::where('parent_id' , '>' , 0 )->where('is_recommend' , 1 )
		->select(['name' , 'short_py' , 'id' ] )
		->orderBy('sort' , 'desc')
		->get();
		$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->orderBy('short_py' , 'asc' )->get();

		//获取最新推荐的5条航线
		$flightType = config('global.flight_type') ;
		$recommend = [] ;
		foreach( $flightType as $k => $val ) {
			$recommend[$k] = FlightPrice::where('display' , 1 )->where('is_promotion' , $k )->where('is_recommend' , 1)->take( 3 )->get();
		}

		$hot = FlightPrice::where('display' , 1 )->where('is_hot' , 1 )->take( 99 )->get();
		$fromPortId = $request->input('fromport');
		$toPortId = $request->input('toport');
		$fromPort = null ;
		if( $fromPortId ) {
			$fromPort = Port::findOrFail( $fromPortId );
		}
		$toPort = null ;
		if( $toPortId ) {
			$toPort = Port::findOrFail( $toPortId );
		}
		//获取船公司信息
		$c = Company::all();
		$company = [] ;
		foreach( $c as $v ) {
			$company[ $v->id ] = $v ;
		}

		$data = [
				'ports' => $ports ,
				'recommend' => $recommend ,
				'hot_port' => $hotPort ,
				'fromPort' => $fromPort ,
				'toPort' => $toPort ,
				'hot' => $hot ,
		] ;
		return view('wap.home.portprice' , $data ) ;
	}

	public function flighthot( Request $request ) {
		$type = $request->input('id');
		$hot = [] ;
		if( $type ) {
			$hot = FlightPrice::where('display' , 1 )->where('is_hot' , 1 )->where('is_promotion' , $type )->take( 99 )->get();
		}
		return view('wap.home.flighthot' , ['hot' => $hot ]) ;
	}

	public function flightlist( $id , Request $request ) {
		$flight = FlightPrice::where('display' , 1 )->where('is_promotion' , $id )->paginate( 5 );

		$data = [
				'list' => $flight ,
				'id' => $id ,
				'key' => $id ,
		] ;
		if( $request->ajax() ) {
			return view('wap.home.flightitem' , $data );
		}
		return view('wap.home.flight' , $data ) ;
	}

	public function searchport(  Request $request  ) {
		$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->get()->toArray();
		$port = [] ;
		foreach( $ports as $p ) {
			$port[ $p['id'] ] = $p ;
		}

		/**
		$query = FlightPortPrice::with( 'flight' , 'flight.dates' , 'flight.ship')
    	//->join('flight_port_time' , 'flight_port_time.port_id' , '=' , 'flight_port_to_port_price.from_port_id')
    	->leftJoin('flight_port_time' ,function( $join ){
    		return $join->on( 'flight_port_time.port_id' , '=' , 'flight_port_to_port_price.from_port_id' )
    		->on('flight_port_time.flight_id', '=', 'flight_port_to_port_price.flight_id');
    	})
    	->orderBy('flight_port_time.leave_plan_date' , 'asc')
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
		if( $fromPort && $toPort ) {
			$query->where('from_port_id' , $fromPort )->where( 'to_port_id' , $toPort );
		}
		$query->where( function( $query) {
			return $query->where('price_20gp' , '>' , 0 )
			->orWhere('price_20hp' , '>' , 0 )
			->orWhere('price_40gp' , '>' , 0 )
			->orWhere('price_40hq' , '>' , 0 );
		});
		$query->where('from_port_leave_time' , '>' , time() );
		if( $date ) {
			$date = strtotime( $date );
			$query->where('from_port_leave_time' , '>' , $date );
		}
		/**
		$query->whereIn('flight_id' , function( $query ) use( $date , $fromPort ) {
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
		$recommend = FlightPrice::where('display' , 1 )->where('is_recommend' , 1)->take( 5 )->get();

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
		] ;

		return view('wap.home.searchprice' , $data );
	}


	public function posts( $id , Request $request ) {
		$posts = Posts::where('category_id' , $id )->orderBy('updated_at' , 'desc')->orderBy('id' , 'desc' )->paginate( 10 );
		$data = [
				'posts' => $posts ,
		] ;
		if( $request->ajax() ) {
			return view('wap.home.postsitem' , $data );
		}

		return view('wap.home.posts' , $data );
	}

	/**
	 * posts detail
	 */
	public function show( $id ) {
		$posts = Posts::findOrFail( $id );
		if( $posts->link ) {
			return redirect( $posts->link );
		}
		$data = [
				'posts' => $posts ,
		] ;
		return view('wap.home.show' , $data );
	}


	public function page( $id ) {
		$posts = Single::findOrFail( $id );
		$data = [
				'posts' => $posts ,
		] ;
		return view('wap.home.page' , $data );
	}


	public function checkin( Request $request ) {
		$ports = Port::where('parent_id' , '>' , 0 )->select(['name' , 'short_py' , 'id' ])->orderBy('short_py' , 'asc' )->get()->toArray();
		$dPorts = [] ;
		if( $ports ) {
			foreach( $ports as $val ) {

				$a = substr( data_get( $val , 'short_py') , 0 , 1 );
				if( !$a ) {
					$a = '#' ;
				}
				$a = strtoupper( $a );
				if( !isset( $dPorts[ $a ] ) ) {
					$dPorts[ $a ] = [] ;
				}
				$dPorts[ $a ][] = $val ;
			}
		}
		$company = Company::pluck('name' , 'id' )->toArray();
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
		$from = $request->input('shipment');
		if( $from ) {
			$from = Port::findOrFail( $from ) ;
		}
		$to = $request->input('destinationport');
		if( $to ) {
			$to = Port::findOrFail( $to ) ;
		}
		$data = [
				'ports' => $dPorts ,
				'company' => $company ,
				'ship' => $ship ,
				'flight' => $flight ,
				'from' => $from ,
				'to' => $to ,
		] ;

		return view('wap.home.checkin' , $data );
	}

	public function checkinstore( Request $request ) {
		$input = $request->all();
		session(['goods' => $input ]) ;
		return response()->json(['errcode' => 0 , 'url' => route('wap.checkin.entrust')]) ;
	}

	public function getentrust() {
		if( !session( 'goods' ) ) {
			return redirect()->back()->with('error' , '你还没有设置货物信息') ;
		}
		$user = auth()->guard('wap')->user();
		$entrust = UserEntrust::where('user_id' , $user->id )->orderBy('id' , 'desc')->get();
		$data = [
				'entrust' => $entrust
		] ;
		return view('wap.home.entrust' , $data ) ;
	}

	public function setentrust( Request $request ) {
		$input = $request->all();
		session(['entrust' => $input ]) ;
		return response()->json(['errcode' => 0 , 'url' => route('wap.checkin.sender')]) ;
	}

	public function getsender() {
		if( !session( 'entrust' ) ) {
			return redirect()->back()->with('error' , '你还没有设置委托人信息') ;
		}
		$user = auth()->guard('wap')->user();
		$sender = UserSender::where('user_id' , $user->id )->orderBy('id' , 'desc' )->get();
		$data = [
				'sender' => $sender
		] ;
		return view('wap.home.sender' , $data ) ;
	}

	public function setsender( Request $request ) {
		$input = $request->all();
		session(['sender' => $input ]) ;
		return response()->json(['errcode' => 0 , 'url' => route('wap.checkin.recevier')]) ;
	}

	public function getrecevier() {
		if( !session( 'sender' ) ) {
			return redirect()->back()->with('error' , '你还没有设置发货人信息') ;
		}
		$user = auth()->guard('wap')->user();
		$recevier = UserRecevier::where('user_id' , $user->id )->orderBy('id' , 'desc' )->get();
		$data = [
				'recevier' => $recevier
		] ;
		return view('wap.home.recevier' , $data ) ;
	}

	public function setrecevier( Request $request ) {
		$input = $request->all();
		session(['recevier' => $input ]) ;
		return response()->json(['errcode' => 0 , 'url' => route('wap.checkin.ensure')]) ;
	}

	public function ensure() {
		if( !session( 'recevier' ) ) {
			return redirect()->back()->with('error' , '你还没有设置收货人信息') ;
		}
		return view('wap.home.ensure' );
	}

	public function submit( Request $request ) {
		$ensure = $request->all();
		$user = auth()->guard('wap')->user();
		$goods = session('goods');
		$iGoods = [] ;
		$iGoods[] = new OrderGoods([
				'name' =>  data_get( $goods , 'goods_name') ,
				'box_type' => data_get( $goods , 'goods_box_type')  ,
				'box_num' =>  data_get( $goods , 'goods_box_num') ,
				'total_num' =>  data_get( $goods , 'goods_total_num' ) ? (int) data_get( $goods , 'goods_total_num' ) : 0 ,
				'weight' =>  data_get( $goods , 'goods_weight') ? (int) data_get( $goods , 'goods_weight')  : 0 ,
				'cubage' =>  data_get( $goods , 'goods_cubage' , '0' ) ? (int) data_get( $goods , 'goods_cubage' , '0' ) : 0 ,
				'package' =>  data_get( $goods , 'goods_package_type') ,
		]);
		$sessionSender = session('sender') ;
		$sender = [
				'name' => data_get( $sessionSender , 'sender_name')  ,
				'contact_name' => data_get( $sessionSender , 'sender_contact')  ,
				'mobile' => data_get( $sessionSender , 'sender_mobile')  ,
				'email' => data_get( $sessionSender , 'sender_email')  ,
				'address' => data_get( $sessionSender , 'sender_address')  ,
				'load_date' => data_get( $sessionSender , 'sender_date')   ,
		];

		$sessionEntrust = session('entrust');
		$entrust = [
				'name' => data_get( $sessionEntrust , 'entrust_name' ) ,
				'contact' => data_get( $sessionEntrust , 'entrust_contact' ) ,
				'mobile' => data_get( $sessionEntrust , 'entrust_mobile' ) ,
		] ;

		$sessionRecevier = session('recevier');
		$recevier = [
				'name' => data_get( $sessionRecevier , 'recevier_name' )  ,
				'contact_name' => data_get( $sessionRecevier , 'recevier_contact' ) ,
				'mobile' => data_get( $sessionRecevier , 'recevier_mobile' ) ,
				'email' => data_get( $sessionRecevier , 'recevier_email' ) ,
				'address' => data_get( $sessionRecevier , 'recevier_address' ) ,
				'id_no' => data_get( $sessionRecevier , 'recevier_idno' ) ,
		] ;

		$order = new Order();
		$order->user_id = $user->id ;
		$order->flight_id = data_get($goods , 'flight_id' , 0 ) ;
		$order->shipment = data_get($goods , 'shipment') ;
		$order->destinationport = data_get($goods , 'destinationport') ;
		$order->transport_protocol = data_get($goods , 'transport_protocol') ;
		$order->goods_kind = data_get($goods , 'goods_kind') ;
		$order->company_id = data_get( $goods , 'company_id' ) ;
		$order->ship_id = data_get($goods , 'ship_id' , 0 ) ;
		$order->voyage = '' ;
		$order->remark = data_get( $goods  , 'remark' , '' );
		if( $order->flight_id ) {
			$flight = Flight::findOrFail( $order->flight_id );
			$order->voyage = $flight->no ;
		}
		$order->cabinet = 0 ;
		$order->cabinet_num = 0 ;
		$order->departure = '' ;
		$order->destination = '' ;
		$order->trailer_cost = 0 ;
		$order->ship_cost = 0 ;
		$order->other_cost = 0 ;
		$order->costinfo = '' ;
		$order->state = 0 ;
		//$order->barge_time = 0 ;
		//$order->start_time = ;
		//$order->end_time = 0 ;
		$order->admin_id = 0 ;
		$order->rebate = 0 ;
		$order->rebate_status = 0 ;
		$order->seal_num = '' ;
		$order->cabinet_no = '' ;
		$order->order_sn = '' ;
		$order->file = '' ;
		$order->enable_ensure = data_get( $ensure , 'enable_ensure') ? 1 : 0 ;
		$order->ensure_name = data_get( $ensure , 'ensure_name');
		$order->insure_goods_worth = ( int ) data_get( $ensure , 'ensure_goods_worth');
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
			session(['goods' => null ]) ;
			session(['sender' => null ]) ;
			session(['entrust' => null ]) ;
			session(['recevier' => null ]) ;
			return response()->json(['errcode' => 0 , 'msg' => '下单成功']) ;
		} catch( \Exception $e ) {
			DB::rollback();
			return response()->json(['errcode' => 10001 , 'msg' => '下单失败' . $e->__toString()]) ;
		}
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
		return view('wap.home.track' , ['order' => $order  , 'from' => $from , 'to' => $to ] );
	}

	public function login( Request $request ) {
		$referer = \URL::previous() == $request->getUri() ? route('wap.member') : \URL::previous() ;
		session( ['_login_previous' => $referer ]);
		//如果是切换账号的话就不可自动登录
		$changeAccount = session('change_account') ;
		if( auth()->guard()->check() ) {
			return redirect( $referer );
		}
		$weixinBrowser = strpos($request->header('user_agent'), 'MicroMessenger') !== false ;
		$weixinBrowser = true ;
		$wechatInfo = session('wechat');
		$bind = true ;
		if( $wechatInfo && !$changeAccount ) {
			$user = UserRsync::where('type' , 'wechat' )->where('token' , $wechatInfo['oauth_user']->id )->first();
			if( $user ) {
				auth()->guard('wap')->loginUsingId( $user->user_id );
				return redirect( $referer );
			}
			$bind = false ;
		}
		return view('wap.home.login' , ['bind' => $bind , 'is_weixin' => $weixinBrowser ] );
	}

	public function dologin( Request $request ) {
		$referer = $request->input('return');
		$username = $request->input('name');
		$passwd = $request->input('password') ;
		if( !$username ) {
			return response()->json([ 'errcode' => 10001 , 'msg' => '请填写用户名'], 200 );
		}
		if( !$passwd ) {
			return response()->json([ 'errcode' => 10002 , 'msg' => '请填写密码'], 200 );
		}

		$user = User::where('name' , $username )->first();
		if( empty( $user ) ) {
			return response()->json(['errcode' => 10003 , 'msg' => '用户名或密码错误'] , 200 );
		}
		$result = auth()->guard('wap')->attempt( ['name' => $username , 'password' => $passwd ], false  );
		if( $result ) {
				auth()->guard('wap')->loginUsingId( $user->id );

				//登录成功后销毁掉
				session(['change_account' => null ] ) ;
				$isBind = $request->input('is_bind') ;
				if( $isBind ) {
					$wechatInfo = session('wechat');
					if( $wechatInfo ) {
						UserRsync::firstOrCreate(
								[
										'type' => 'wechat' ,
										'token' => $wechatInfo['oauth_user']->id
								] ,
								[
										'user_id' => auth()->guard('wap')->user()->id
								]
						);
					}
				}
				return response()->json(['errcode' => 0 , 'msg' => '登录成功' , 'url' => $referer ]);

		}
		return response()->json(['errcode' => 10002 , 'msg' => '登录失败'  ]);
	}

	public function register() {

		return view('wap.home.register');
	}

	public function doregister( Request $request ) {
		$mobile = $request->input('name');
		$verify = $request->input('code');
		$key = "verify_" . $mobile . ":reg";
		if( config('app.supper_verify') != $verify ) {
			$code = Cache::get( $key );
			if( !$verify ) {
				return response()->json(['errcode' => 10002 , 'msg' => '验证码失效']);
			}
			if( $verify != $code ) {
				return response()->json(['errcode' => 10003 , 'msg' => '验证码不正确']);
			}
		}
		$data = $request->all();
		$count = User::where('name' , $data['name'])->count();
		if( $count ) {
			return response()->json(['errcode' => 10003 , 'msg' => '用户已经注册']);
		}

		$user = User::create([
				'name' => $data['name'],
				'password' => bcrypt($data['password']),
				'rec_id' => (int) data_get( $data , 'rec_id' ),
				'contact' => data_get( $data , 'contact' ) ,
				'qq' => data_get( $data , 'qq' )
		]);
		if( $user ) {
			//注册成功
			auth()->guard('wap')->login($user);
			//检查有没有微信信息  如是有 且没有绑定任何用记 则给绑定
			$wechatInfo = session('wechat');
			if( $wechatInfo ) {
				$wechatUser = UserRsync::where('type' , 'wechat' )->where('token' , $wechatInfo['oauth_user']->id )->first();
				if( !$wechatUser ) {
					UserRsync::firstOrCreate(
							[
							'type' => 'wechat',
							'token' => $wechatInfo ['oauth_user']->id
					], [
							'user_id' => $user->id
					] );
				}
			}
				return response()->json(['errcode' => 0 , 'msg' => '注册成功']);
		} else {
				return response()->json(['errcode' => 10002 , 'msg' => '注册失败']);
		}
	}

	public function logout( Request $request ) {
		auth()->guard('wap')->logout();
		$request->session()->flush();
		$request->session()->regenerate();
		session(['change_account' => 1 ]) ;
		return redirect( route('wap.index') );
	}


	public function findpwd() {
		return view('wap.home.findpwd');
	}

	public function dofindpwd( Request $request ) {
		$phone = $request->get('name');
		$code = $request->get('code');
		$password = $request->get('password');
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
			auth()->guard('wap')->loginUsingId( $user->id );
			return response()->json( ['errcode' => 0 , 'msg'=> '修改成功' ]);
		}
		return response()->json( ['errcode' => 10004 , 'msg'=> '修改失败' ]);
	}

	public function recom() {

		return view('wap.home.recom');
	}
}
