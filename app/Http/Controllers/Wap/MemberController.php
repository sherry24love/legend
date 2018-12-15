<?php
namespace App\Http\Controllers\Wap ;


use App\Http\Controllers\Controller;
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
use App\Models\UserBank;
use App\Models\Refund;
use App\Models\Reward;
use App\User;
use App\Models\Withdraw;
use App\Models\Finance;
use EasyWeChat ;

class MemberController extends Controller {


	public function __construct() {
		$js = EasyWeChat::js();
		view()->share('js' , $js );
	}

	public function index() {
		$user = auth()->guard('wap')->user();
		$wechatInfo = session('wechat');
		$data = [
				'leftMenu' => 'userinfo' ,
				'money' => $user->money ,
				'user' => $user ,
				'wechat' => data_get( $wechatInfo , 'oauth_user')
		] ;
		return view('wap.member.index' , $data) ;
	}

	public function bindwechat( ) {
		$wechatInfo = session('wechat');
		$user = auth()->guard('wap')->user();
		if( $wechatInfo ) {
			$wechatUser = \App\Models\UserRsync::where('type' , 'wechat' )->where('token' , $wechatInfo['oauth_user']->id )->first();
			if( !$wechatUser ) {
				UserRsync::firstOrCreate(
						[
								'type' => 'wechat',
								'token' => $wechatInfo ['oauth_user']->id
						], [
								'user_id' => $user->id
						] );
			}

			return response()->redirectTo( route('wap.member') ) ;
		}
		return response()->redirectTo( route('wap.login') ) ;
	}

	public function setting() {

		return view('wap.member.setting');
	}

	public function modinfo() {
		$user = auth()->guard('wap')->user();
		$data = [
				'leftMenu' => 'userinfo' ,
				'money' => $user->money ,
				'user' => $user
		] ;
		return view('wap.member.modinfo' , $data );
	}

	public function updateinfo( Request $request ) {
		$user = auth()->guard('wap')->user();
		$user->contact = $request->input('contact');
		$user->qq = $request->input('qq');
		if( $user->save() ) {
			return response()->json(['errcode' => 0 , 'msg' => '修改成功' ]);
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '修改失败' ]);
	}

	public function modpwd() {

		return view('wap.member.modpwd');
	}

	public function updatepwd( Request $request ) {
		$oldPassword = $request->get('old_pwd');
		$newPassword = $request->get('pwd');

		if( auth()->guard('wap')->getProvider()->getHasher()->check( $oldPassword , auth()->guard('wap')->user()->getAuthPassword() ) ) {
			if( User::where('id' , auth()->guard('wap')->user()->id )->update(['password' => bcrypt( $newPassword )]) ) {
				return response()->json(['errcode' => 0 , 'msg' => '修改成功' ]);
			}
			return response()->json(['errcode' => 10002 , 'msg' => '修改失败' ]);
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '旧密码不正确' ]);

	}


	public function order( Request $request ) {
		$user = auth()->guard()->user();
		$query = Order::with('fromport' , 'toport')->where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$status = $request->input('status');
		if( trim( $status ) != '' ) {
			$query->where('state' , $status );
		}
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$order = $query->paginate( 10 );
		$data = [
				'order' => $order ,
		] ;
		if( $request->ajax() ) {
			return view('wap.member.orderitem' , $data );
		}
		return view('wap.member.order' , $data ) ;
	}

	public function orderdetail( $id ) {
		$order = Order::findOrFail( $id );
		$user = auth()->guard('wap')->user();
		if( $order->user_id != $user->id ) {
			return redirect()->back()->with('error' , '您没有权限');
		}
		$data = [
				'order' => $order ,
				'entrust' => $order->entrust ,
				'sender' => $order->sender ,
				'recevier' => $order->recevier ,
		] ;
		return view('wap.member.orderdetail' , $data ) ;
	}

	/**
	 * 返利   自己下单返利
	 */
	public function refund( Request $request ) {
		$user = auth()->guard()->user();

		$query = Refund::with('order')->where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$status = $request->input('status');
		if( trim( $status ) != '' ) {
			$query->where('status' , $status );
		}
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$list = $query->paginate( 10 );
		$data = [
				'list' => $list ,
				'leftMenu' => 'refund' ,
				'moeny' => $user->money
		] ;
		if( $request->ajax() ) {
			return view('wap.member.refunditem' , $data );
		}
		return view('wap.member.refund' , $data );
	}

	/**
	 * 奖励
	 */
	public function reward(  Request $request  ) {
		$user = auth()->guard()->user();
		$query = Reward::with('order')->where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$status = $request->input('status');
		if( trim( $status ) != '' ) {
			$query->where('status' , $status );
		}
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$list = $query->paginate( 10 );
		$data = [
				'list' => $list ,
				'leftMenu' => 'reward' ,
				'moeny' => $user->money
		] ;
		if( $request->ajax() ) {
			return view('wap.member.rewarditem' , $data );
		}
		return view('wap.member.reward' , $data );
	}

public function recom( Request $request  ) {
		$user = auth()->guard('wap')->user();
		$query = User::where( 'rec_id' , $user->id )->orderBy('id' ,'desc');
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$list = $query->paginate( 10 );
		$data = [
				'list' => $list ,
				'leftMenu' => 'recom' ,
				'moeny' => $user->money
		] ;

		if( $request->ajax() ) {
			return view('wap.member.recomitem' , $data );
		}
		return view('wap.member.recom' , $data );
	}

	public function withdraw( Request $request ) {
		$user = auth()->guard('wap')->user();
		$query = Withdraw::where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$status = $request->input('status');
		if( trim( $status ) != '' ) {
			$query->where('status' , $status );
		}
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}


		$withdraw = $query->paginate( 10 );
		$data = [
				'withdraw' => $withdraw ,
				'leftMenu' => 'withdraw' ,
				'moeny' => $user->money
		] ;
		if( $request->ajax() ) {
			return view('wap.member.withdrawitem' , $data );
		}
		$total = Withdraw::where('user_id' , $user->id )->where('status' , 1 )->sum('cash');
		$data['total'] = $total ;
		return view('wap.member.withdraw' , $data );
	}

	public function withdrawcancel( $id ) {
		$user = auth()->guard('wap')->user();
		$withdraw  = Withdraw::where('user_id' , $user->id )->where('id' , $id )->first();
		if( $withdraw ) {
			if( $withdraw->status != 0 ) {
				return response()->json(['errcode' => 10001  , 'msg' => '提现记录状态不正确'] );
			}
			$row = Withdraw::where('id' , $id )->where('status' , 0 )->update(['status' => 4 ]) ;

			if( $row ) {
				$finance = new Finance() ;
				$finance->user_id = $user->id ;
				$finance->cash = $withdraw->cash  ;
				$finance->act = 'in' ;
				$finance->orgin_cash = $user->money ;
				$finance->result_cash = $user->money +  $withdraw->cash   ;
				$finance->type = 'cancelwithdraw' ;
				$finance->target_id = $withdraw->id ;
				$finance->save();
				User::where('id' , $user->id )->where('money' , $user->money )->update(['money' => $finance->result_cash ] ) ;
				return response()->json(['errcode' => 0  , 'msg' => '取消提现完成'] );
			}
		}
		return response()->json(['errcode' => 10002  , 'msg' => '取消提现失败'] );
	}

	public function withdrawcreate() {
		$user = auth()->guard('wap')->user();
		$bank = UserBank::where('user_id' , $user->id )->get();
		if( empty( $bank ) ) {
			return redirect( route('wap.bank.create'))->with('error'  , '您还没有添加银行卡') ;
		}

		$data = [
				'bank' => $bank ,
				'money' => $user->money
		] ;
		return view('wap.member.withdrawcreate' ,$data );
	}

	public function withdrawstore(  Request $request  ) {
		$user = auth()->guard('wap')->user();
		$money = $request->input('cash') ;
		if( $money > $user->money ) {
			return response()->json(['errcode' => 10002  , 'msg' => '账户余额不足'] );
		}
		$cardId = $request->input('bank_id');
		$card = UserBank::find( $cardId );
		if( empty( $card ) ) {
			return response()->json(['errcode' => 10002  , 'msg' => '银行卡信息不正确'] );
		}

		$withdraw = new Withdraw( );
		$withdraw->cash = $money ;
		$withdraw->card_name = $card->name ;
		$withdraw->card_bank_id = $card->bank_id ;
		$withdraw->card_no = $card->card_no ;
		$withdraw->user_id = $user->id ;
		if( $withdraw->save() ) {
			$finance = new Finance() ;
			$finance->user_id = $user->id ;
			$finance->cash = $money ;
			$finance->act = 'out' ;
			$finance->orgin_cash = $user->money ;
			$finance->result_cash = $user->money - $money  ;
			$finance->type = 'withdraw' ;
			$finance->target_id = $withdraw->id ;
			$finance->save();
			User::where('id' , $user->id )->where('money' , $user->money )->update(['money' => $finance->result_cash ] ) ;
			return response()->json(['errcode' => 0  , 'msg' => '提现申请提交完成'] );
		}
		return response()->json(['errcode' => 10001  , 'msg' => '提现申请提交失败'] );
	}



	public function bank() {
		$user = auth()->guard('wap')->user();
		$query = UserBank::where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$bank = $query->paginate( 10 );
		$data = [
				'bank' => $bank ,
				'leftMenu' => 'bank' ,
		] ;
		return view('wap.member.bank' , $data );
	}

	public function bankcreate() {
		$user = auth()->guard('wap')->user();
		$count = UserBank::where( 'user_id' , $user->id )->count();
		if( $count > 5 ) {
			return redirect()->back()->with('error' , '您添加的银行卡数已经达到最大上限！') ;
		}
		$data = [
				'leftMenu' => 'bank' ,
		];
		return view('wap.member.bankcreate' , $data);
	}


	public function bankstore( Request $request ) {
		$user = auth()->guard()->user();
		$count = UserBank::where( 'user_id' , $user->id )->count();
		if( $count > 5 ) {
			return response()->json(['errcode' => 10001 , 'msg' => '您添加的银行卡数已经达到最大上限！']) ;
		}
		$bank = new UserBank();
		$bank->user_id = $user->id ;
		$bank->name = $request->input('name');
		$bank->card_no = $request->input('card_no') ;
		$bank->bank_id = $request->input('bank_id');
		if( $bank->save() ) {
			return response()->json(['errcode' => 0 , 'msg' => '添加成功！']) ;
		}
		return response()->json(['errcode' => 10001 , 'msg' => '添加银行卡出错！']) ;
	}

	public function bankedit( $id ) {
		$user = auth()->guard()->user();
		$card = UserBank::findOrFail( $id );
		if( $card->user_id != $user->id ) {
			return redirect()->back()->with('error' , '您没有权限！') ;
		}
		$data = [
				'leftMenu' => 'bank' ,
				'bank' => $card ,
		];
		return view('wap.member.bankedit' , $data);
	}

	public function bankupdate( $id , Request $request ) {
		$user = auth()->guard()->user();
		$card = UserBank::findOrFail( $id );
		if( $card->user_id != $user->id ) {
			return response()->json(['errcode' => 10001 , 'msg' => '您没有权限！']) ;
		}
		$card->name = $request->input('name');
		$card->card_no = $request->input('card_no') ;
		$card->bank_id = $request->input('bank_id');
		if( $card->save() ) {
			return response()->json(['errcode' => 0 , 'msg' => '修改成功！']) ;
		}
		return response()->json(['errcode' => 10001 , 'msg' => '修改银行卡出错！']) ;
	}

	public function bankdrop( $id ) {
		$user = auth()->guard('wap')->user();
		$card = UserBank::findOrFail( $id );
		if( $card->user_id != $user->id ) {
			return response()->json(['errcode' => 10001 , 'msg' => '您没有权限！']) ;
		}
		if( $card->delete() ) {
			return response()->json(['errcode' => 0 , 'msg' => '删除成功！']) ;
		}
		return response()->json(['errcode' => 10001 , 'msg' => '删除银行卡出错！']) ;
	}

	public function qrcode() {

		$js = EasyWeChat::js();

		return view('wap.member.qrcode' , ['js' => $js ]) ;
	}
}
