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
use App\Models\Withdraw;
use App\Models\UserBank;
use App\Models\Finance;
use App\User;
use App\Models\Refund;
use App\Models\Reward;

class WithdrawController extends Controller {
	
	
	public function index( Request $request ) {
		$user = auth()->guard()->user();
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
		$total = Withdraw::where('user_id' , $user->id )->where('status' , 1 )->sum('cash');
		
		$withdraw = $query->paginate( 10 );
		$data = [
				'withdraw' => $withdraw ,
				'leftMenu' => 'withdraw' ,
				'money' => $user->money ,
				'total' => $total
		] ;
		return view('withdraw.index' , $data );
	}
	
	public function create() {
		$user = auth()->guard()->user();
		$bank = UserBank::where('user_id' , $user->id )->get();
		if( empty( $bank ) ) {
			return redirect( route('member.bank.create'))->with('error'  , '您还没有添加银行卡') ;
		}
		
		$data = [
				'bank' => $bank ,
				'leftMenu' => 'withdraw' ,
				'moeny' => $user->money
		] ;
		return view('withdraw.create' , $data );
	}
	
	public function store( Request $request ) {
		$user = auth()->guard('web')->user();
		$money = $request->input('cash') ;
		if( $money > $user->money ) {
			return redirect( )->back()->with('error'  , '账户余额不足') ;
		}
		$cardId = $request->input('bank_id');
		$card = UserBank::find( $cardId );
		if( empty( $card ) ) {
			return redirect( )->back()->with('error'  , '银行卡信息不正确') ;
		}
		
		$withdraw = new Withdraw();
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
			return redirect( route('member.withdraw'))->with('success'  , '提现申请提交完成') ;
		}
		return redirect( )->back()->with('error'  , '提现申请提交失败') ;
	}
	
	public function cancel( $id ) {
		$user = auth()->guard()->user();
		$withdraw  = Withdraw::where('user_id' , $user->id )->where('id' , $id )->first();
		if( $withdraw ) {
			if( $withdraw->status != 0 ) {
				return redirect( )->back()->with('error'  , '提现记录状态不正确') ;
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
				return redirect( route('member.withdraw'))->with('success'  , '取消提现完成') ;
			}
		}
		return redirect( )->back()->with('error'  , '取消提现失败') ;
	}
}