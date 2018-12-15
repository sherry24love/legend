<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Withdraw ;
use Illuminate\Http\Request;
use App\User;
use App\Models\Finance;

class WithdrawController extends Controller
{
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('提现管理');
			$content->description('列表');

			$content->body($this->grid());
		});
	}

	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		Admin::script( view('admin.script.withdraw')->render() );
		return Admin::grid(Withdraw::class, function (Grid $grid) {
			$grid->model()->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->column('user.name' , '用户名');
			$grid->cash('提现金额');
			$grid->card_name('开户人姓名');
			$grid->card_bank_id('开户行')->display( function( $v ){
				return data_get( config('global.bank' ) , $v );
			} );
			$grid->card_no('银行账号');
			$grid->status('状态' )->display( function( $v ){
				return data_get( config('global.withdraw_status' ) , $v );
			} );
			$grid->created_at('创建时间');
			$grid->updated_at('修改时间');
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->disableCreation();
			
			$grid->filter( function( $filter ){
				$filter->disableIdFilter();
				$filter->where(function( $query ){
					$input = $this->input ;
					return $query->where('user_id' , function( $query ) use( $input ){
						return $query->from('users')->where('name' , $input )->select('id');
					});
				} , '用户名');
				$filter->is('status' , '状态')->select( config('global.withdraw_status') );
				$filter->between('created_at' , '申请时间')->dateTime();
			});
			
			
			$grid->actions( function( $actions ) {
				$actions->disableEdit();
				$actions->disableDelete();
				if( $actions->row->status === 0 ) {
					$okUrl = route('admin.withdraw.deal'  , ['id' => $actions->row->id ] ) ;
					$actions->append("<a data-href='{$okUrl}' class='btn btn-xs btn-primary wd-deal'>打款申请</a>&nbsp;");
				}
				if( $actions->row->status === 3 ) {
					$okUrl = route('admin.withdraw.ok'  , ['id' => $actions->row->id ] ) ;
					$failUrl = route('admin.withdraw.fail'  , ['id' => $actions->row->id ] ) ;
					$actions->append("<a data-href='{$okUrl}' class='btn btn-xs btn-primary wd-ok'>打款成功</a>&nbsp;");
					$actions->append("<a data-href='{$failUrl}' class='btn btn-xs btn-primary wd-fail'>审核不通过</a>&nbsp;");
				}
			} );
		});
	}
	
	public function deal( $id , Request $request ) {
		$withdraw = Withdraw::findOrFail( $id );
		if( $withdraw->status != 0 ) {
			return response()->json( ['errcode' => 10001 , 'msg' => '当前状态不能操作']) ;
		}
		$remark = $request->input('value');
		$withdraw->remark = $remark ;
		$withdraw->admin_id = auth()->guard('admin')->user()->id ;
		$withdraw->status = 3 ;
		if( $withdraw->save() ) {
			return response()->json( ['errcode' => 0 , 'msg' => '正在申请中']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '正在申请中操作失败']) ;
	}
	
	public function ok( $id , Request $request ) {
		$withdraw = Withdraw::findOrFail( $id );
		if( $withdraw->status != 3 ) {
			return response()->json( ['errcode' => 10001 , 'msg' => '当前状态不能操作']) ;
		}
		$remark = $request->input('value');
		$withdraw->remark = $remark ;
		$withdraw->admin_id = auth()->guard('admin')->user()->id ;
		$withdraw->status = 1 ;
		if( $withdraw->save() ) {
			return response()->json( ['errcode' => 0 , 'msg' => '操作成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '操作失败']) ;
	}
	
	public function fail( $id , Request $request  ) {
		$withdraw = Withdraw::findOrFail( $id );
		if( $withdraw->status != 3 ) {
			return response()->json( ['errcode' => 10001 , 'msg' => '当前状态不能操作']) ;
		}
		$remark = $request->input('value');
		$rows = Withdraw::where('status' , 3 )->where('id' , $id )->update(
			[
					'status' => 2 ,
					'admin_id' => auth()->guard('admin')->user()->id ,
					'remark' => $remark
			]		
		);
		if( $rows ) {
			//并且追加一条充值记录 还原用户的金额
			$user = User::find( $withdraw->user_id );
			$finance = new Finance();
			$finance->user_id = $user->id ;
			$finance->cash = $withdraw->cash ;
			$finance->act = 'in' ;
			$finance->orgin_cash = $user->money ;
			$finance->result_cash = $user->money + $withdraw->cash  ;
			$finance->type = 'unwithdraw' ;
			$finance->target_id = $withdraw->id ;
			$finance->save();
			User::where('id' , $user->id )->where('money' , $user->money )->update(['money' => $finance->result_cash ] ) ;
				
			return response()->json( ['errcode' => 0 , 'msg' => '操作成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '操作失败']) ;
	}
}
