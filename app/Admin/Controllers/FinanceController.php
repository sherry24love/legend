<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Finance ;
use App\Models\Reward ;
use App\Models\Refund;
use App\Models\Withdraw ;

class FinanceController extends Controller
{
	use ModelForm;

	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('用户流水记录');
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
		return Admin::grid( Finance::class, function (Grid $grid) {
			$grid->model()->orderBy('id' , 'desc');
			$grid->id('ID')->sortable();
			$grid->column('user.name' , '用户名' );
			$grid->act('收入/支出')->display( function( $v ){
				return $v == 'out' ? '支出' : '收入' ;
			} );
			$grid->cash('变动金额');
			$grid->orgin_cash('原始金额');
			$grid->result_cash('最终金额');
			$grid->type('事件来源');
			$grid->created_at('时间');
			$grid->disableBatchDeletion();
			$grid->disableExport();
			$grid->disableCreation() ;
			$grid->filter( function( $filter ){
				$filter->disableIdFilter();
				$filter->where( function( $query ) {
					$input = $this->input ;
					return $query->whereIn('user_id' , function( $query ) use ( $input ) {
						return $query->from('users')->where('name' , 'like' , "%{$input}%")->select('id');
					});

				} , '用户手机' );
				$filter->where( function( $query ) {
					$input = $this->input ;
					return $query->whereIn('user_id' , function( $query ) use ( $input ) {
						return $query->from('users')->where('contact' , 'like' , "%{$input}%")->select('id');
					});

				} , '联系人' );
				$filter->between('created_at' , '时间')->datetime();
			});
		});
	}

    /**
        *   * 修复数据
        *        */
    public function fixlog() {
        Finance::where('id' , '>' , 0 )->orderBy('id' , 'asc')->chunk( 100 , function( $finances ){
            foreach( $finances as $f ) {
                if( $f->type == 'refund' ) {
                                        $refund = Refund::with('order')->find( $f->target_id );
                                                        $f->type = "订单返利，返利单号为:{$f->target_id}，订单编号为:{$refund->order->id}运单号为:" . $refund->order->waybill ;
                                                        }
                if( $f->type == 'reward' ) {
                                        $reward = Reward::with('order')->find( $f->target_id ) ;
                                                        $f->type = "推广返利，推广单号为:{$f->target_id}，订单编号为:{$reward->order->id}运单号为:" . $reward->order->waybill ;
                                                        }
                if( $f->type == 'withdraw' ) {
                                        $f->type = "用户发起提现：单号:" . $f->target_id ;
                                                        }
                                $f->save();
                            }
            });
    }
}
