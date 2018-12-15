@extends('layouts.layout')

@section('style')
<style type="text/css">
    .none{display: none;}
    .block{
        display: block;
    }
    #newBridge .icon-right-center {display: none;}
.nb-icon-inner-wrap {display: none;}
</style>
@endsection


@section('content')

<div class="container">
    <div class="row">
        @include('block.member_left')
		
        <div class="col-sm-10 npl npr">
            <div class="right_second">
            		@include('block.success')
            		@include('block.error')
                <div class="list-tab">
                    <ul class="order_list">
                    	<li class="active">
	                    		<a href="{{route('member.order')}}">订单列表</a>
	                    </li>
	                    <li class="active">
	                    		<a href="javascript:void(0)">订单详情</a>
	                    </li>
	                </ul>
                </div>
                
                <div class="order_info" style="margin-top:20px;">
                			<div class="row head">
					  			<strong>运单信息</strong>

					  		</div>
					  		<div class="row">
					  			<div class="col-sm-12">
					  				<table class="order-detail table table-hover table-bordered">
					  					<tr>
					  						<td>
					  							<label>订单编号</label>
					  							<span>{{$order->order_sn or ''}}</span>
					  						</td>
					  						<td>
					  							<label>货主</label>
					  							<span>{{$order->owner or ''}}</span>
					  						</td>
					  						<td>
					  							&nbsp;
					  						</td>
					  					</tr>
					  					<tr>
					  						<td colspan="3" >
					  							<label>港口信息</label>
					  							<span>
					  							
					  							@if( $order->barge_port )
					  								{{$order->fromport->name or ''}}(驳船) &nbsp;--&nbsp;
					  								{{$order->bargeport->name or ''}}&nbsp;--&nbsp;
					  							@else
					  								{{$order->fromport->name or ''}}&nbsp;--&nbsp;
					  							@endif
					  							
					  							@if( $order->barge_to_port )
					  								{{$order->bargetoport->name or ''}}&nbsp;--&nbsp;
					  								{{$order->toport->name or ''}}(驳船)
					  							@else
					  								{{$order->toport->name or ''}}
					  							@endif
					  							</span>
					  						</td>
					  					</tr>
					  					<tr>
					  						<td>
					  							<label>运单编号</label>
					  							<span>{{$order->waybill or ''}}</span>
					  						</td>
					  						<td>
					  							<label>柜号</label>
					  							<span>
					  							<?php 
					  								if( isset( $order->cabinet_no ) ) {
					  									if( strlen( $order->cabinet_no ) > 12 ) {
					  										echo str_limit( $order->cabinet_no , 12 ) ;
					  										echo '<a class="more-cabinet_no" data-msg="'. $order->cabinet_no .'">更多</a>';
					  									} else {
					  										echo $order->cabinet_no ;
					  									}
						  							}

					  							?>

					  							</span>
					  						</td>
					  						<td>
					  							<label>封条号</label>
					  							<span>
				  								<?php 
				  									if( isset( $order->seal_num ) ) {
					  									if( strlen( $order->seal_num ) > 12 ) {
					  										echo str_limit( $order->seal_num , 12 ) ;
					  										echo '<a class="more-seal_num" data-msg="'. $order->seal_num .'">更多</a>';
					  									} else {
					  										echo $order->seal_num ;
					  									}
						  							}
					  							?>

					  							</span>
					  						</td>
					  					</tr>
					  					<tr>
					  						<td>
					  							<label>船公司</label>
					  							<span>{{$order->company->name or '' }}</span>
					  						</td>
					  						<td>
					  							<label>船名</label>
					  							<span>{{$order->ship->name or '' }}</span>
					  						</td>
					  						<td>
					  							<label>航次</label>
					  							<span>
					  							{{$order->voyage }}
					  							</span>
					  						</td>
					  					</tr>
					  					@if( $order->barge_port > 0 )
					  					<tr>
					  						<td >
					  							<label>预计驳船离港</label>
					  							<span>
					  							@if( $order->barge_plan_time && $order->barge_plan_time != '0000-00-00 00:00:00')
					  							{{str_limit( $order->barge_plan_time , 16 , '' )}}
					  							@endif
					  							</span>
					  						</td>
					  						<td >
					  							<label>实际驳船离港</label>
					  							<span>
					  							@if( $order->barge_time && $order->barge_time != '0000-00-00 00:00:00')
					  							{{str_limit( $order->barge_time , 16 , '' )}}
					  							@endif
					  							</span>
					  						</td>
					  						<td>
					  						&nbsp;
					  						</td>
					  					</tr>
					  					@endif
					  					<tr>
					  						<td>
					  							<label>预计大船离港</label>
					  							<span>
					  							@if( $order->start_time && $order->start_time != '0000-00-00 00:00:00')
					  							{{str_limit( $order->start_time , 16 , '' )}}
					  							@endif
					  							</span>
					  						</td>
					  						<td >
					  							<label>预计大船到港</label>
					  							<span>
					  							@if( $order->end_time && $order->end_time != '0000-00-00 00:00:00')
					  							{{str_limit( $order->end_time , 16 , '' )}}
					  							@endif
					  							</span>
					  						</td>
					  						<td>
					  						&nbsp;
					  						</td>
					  					</tr>
					  					@if( $order->barge_to_port > 0 )
					  					<tr>
					  						<td >
					  							<label>预计驳船到港</label>
					  							<span>
					  							@if( $order->barge_to_plan_time && $order->barge_to_plan_time != '0000-00-00 00:00:00')
					  							{{str_limit( $order->barge_to_plan_time , 16 , '' )}}
					  							@endif
					  							</span>
					  						</td>
					  						<td >
					  							<label>实际驳船到港</label>
					  							<span>
					  							@if( $order->barge_to_time && $order->barge_to_time != '0000-00-00 00:00:00')
					  							{{str_limit( $order->barge_to_time , 16 , '' )}}
					  							@endif
					  							</span>
					  						</td>
					  						<td>
					  							<label style="width:150px;">目的港驳船船名/航次</label>
					  							<span>
					  							{{$order->barge_to_flight}}
					  							</span>
					  						</td>
					  					</tr>
					  					@endif
					  					<tr>
					  						<td>
					  							<label>拖车费</label>
					  							<span>{{$order->trailer_cost or ''}}</span>
					  						</td>
					  						<td>
					  							<label>海运费</label>
					  							<span>{{$order->ship_cost or ''}}</span>
					  						</td>
					  						<td>
					  							<label>其他费用</label>
					  							<span>{{$order->other_cost or '' }}</span>
					  						</td>
					  					</tr>
					  					<tr>
					  						<td colspan="3">
					  							<label>费用说明</label>
					  							<span>{{$order->costinfo ? $order->costinfo : '' }}</span>
					  						</td>
					  					</tr>
					  					<tr>
					  						<td>
					  							<label>保险信息</label>
					  							<span>{{$order->enable_ensure  == 1  ? '需要保险' : '不需要保险' }}</span>
					  						</td>
					  						<td>
					  							<label>保险人</label>
					  							<span>{{$order->ensure_name or ''}}</span>
					  						</td>
					  						<td>
					  							<label>保险额度</label>
					  							<span>{{$order->insure_goods_worth or '' }}</span>
					  						</td>
					  					</tr>
					  					<tr>
					  						<td>
					  							<label>返利</label>
					  							<span>{{$order->rebate or 0 }}</span>
					  						</td>
					  						<td colspan="2">
					  							<span class="key">付款后即可申请返利提现</span>
					  						</td>
					  					</tr>
					  					<tr>
					  						<td colspan="3">
					  							<label>备注</label>
					  							<span>{{$order->remark or ''}}</span>
					  						</td>
					  					</tr>
					  					<tr>
					  						<td colspan="3">
					  							<label>派送信息</label>
					  							<span>{{$order->barge_to_remark or ''}}</span>
					  						</td>
					  					</tr>
					  				</table>

					  			</div>

					  		</div>
					  		<div class="row head">
					  			<strong>委托人信息</strong>

					  		</div>
					    	<div class="row">
					    		<div class="col-sm-12">
					    			<table class="order-detail table table-hover table-bordered">
						    			<tr>
						    				<td>
						    					<label>名称</label>
						    					<span>{{$entrust->name or ''}}</span>
						    				</td>
						    				<td>
						    					<label>联系人</label>
						    					<span>{{$entrust->contact or ''}}</span>
						    				</td>
						    				<td>
						    					<label>电话</label>
						    					<span>{{$entrust->mobile or ''}}</span>
						    				</td>
						    			</tr>
						    		</table>
					    		</div>
					    	</div>
					    	<div class="row head">
					    		<strong>商品信息</strong>
					    	</div>
					    	<div class="row">
					    		
					    		<div class="col-sm-12">
					    			<table class="order-detail table table-hover table-bordered">
						    			<tr>
						    				<td>
						    					<label>名称</label>
						    					<span>{{$goods->name or ''}}</span>
						    				</td>
						    				<td>
						    					<label>箱量*箱型</label>
						    					<span>{{$goods->box_num or ''}}&nbsp;*&nbsp;{{data_get( config('global.box_type') , data_get( $goods , 'box_type') )}}</span>
						    				</td>
						    				<td>
						    					<label>总量</label>
						    					<span>{{$goods->total_num or ''}}</span>
						    				</td>
						    			</tr>
						    			<tr>
						    				<td>
						    					<label>单柜毛重</label>
						    					<span>{{$goods->weight or '' }}吨</span>
						    				</td>
						    				<td>
						    					<label>总体积</label>
						    					<span>{{$goods->cubage or ''}}m<sup>3</sup></span>
						    				</td>
						    				<td>
						    					<label>包装类型</label>
						    					<span>{{$goods->package or ''}}</span>
						    				</td>
						    			</tr>
						    		</table>
					    		</div>

					    	</div>
					    	<div class="row head">
					  			<strong>发货人信息</strong>

					  		</div>
					    	<div class="row">
					    		<div class="col-sm-12">
						    		<table class="order-detail table table-hover table-bordered">
						    			<tr>
						    				<td>
						    					<label>名称</label>
						    					<span>{{$sender->name or ''}}</span>
						    				</td>
						    				<td>
						    					<label>联系人</label>
						    					<span>{{$sender->contact_name or ''}}</span>
						    				</td>
						    				<td>
						    					<label>电话</label>
						    					<span>{{$sender->mobile or ''}}</span>
						    				</td>
						    			</tr>
						    			<tr>
						    				<td>
						    					<label>邮箱</label>
						    					<span>{{$sender->email or ''}}</span>
						    				</td>
						    				<td>
						    					<label>装货地址</label>
						    					<span>{{$sender->address or ''}}</span>
						    				</td>
						    				<td>
						    					<label>装货日期</label>
						    					<span>{{$sender->load_date or ''}}</span>
						    				</td>
						    			</tr>
						    		</table>
					    		</div>
					    	</div>
					    	<div class="row head">
					  			<strong>收货人信息</strong>

					  		</div>
					    	<div class="row">
					    		<div class="col-sm-12">
						    		<table class="order-detail table table-hover table-bordered">
						    			<tr>
						    				<td>
						    					<label>名称</label>
						    					<span>{{$recevier->name or ''}}</span>
						    				</td>
						    				<td>
						    					<label>联系人</label>
						    					<span>{{$recevier->contact_name or ''}}</span>
						    				</td>
						    				<td>
						    					<label>电话</label>
						    					<span>{{$recevier->mobile or ''}}</span>
						    				</td>
						    			</tr>
						    			<tr>
						    				<td>
						    					<label>邮箱</label>
						    					<span>{{$recevier->email or ''}}</span>
						    				</td>
						    				<td>
						    					<label>收货地址</label>
						    					<span>{{$recevier->address or ''}}</span>
						    				</td>
						    				<td>
						    					<label>证件号码</label>
						    					<span>{{$recevier->id_no or ''}}</span>
						    				</td>
						    			</tr>
						    		</table>
					    		</div>
					    	</div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script >
$('.more-cabinet_no').click(function(){
	var msg = $(this).data('msg');
	if( msg ) {
		msg = msg.replace(/,/g , '&nbsp;');
		layer.open({
			'content': msg ,
			'title':'柜号'
		});
	}

});

$('.more-seal_num').click(function(){
	var msg = $(this).data('msg');
	if( msg ) {
		msg = msg.replace(/,/g , '&nbsp;');
		layer.open({
			'content': msg ,
			'title' :'封条号'
		});
	}

});

</script>
@endsection
