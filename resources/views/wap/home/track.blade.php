@extends('wap.layout')
@section('style')
<style>
.dates_color {
	color: rgba(255, 124, 0, 0.95);
    font-weight: bold;
}

.order-det-info {
	margin-bottom:.5rem;
}

.info-header {
	line-height: 1.8rem;
    text-align: center;
    font-weight: bold;
    font-size: .8rem;
}

.info-content {
	display:-webkit-box;
    display:-webkit-flex;
    display:-ms-flexbox;
    display:flex;
    -webkit-box-pack:justify;
    -webkit-justify-content:space-between;
    -ms-flex-pack:justify;
    justify-content:space-between;
}

.info-content .info-info {
	display:inline-block;
	width:45%;
	border:none;
}

.info-content .info-info span {
	display:block;
	text-align:center;
}

</style>

@endsection

@section('content')
<div class="aui-searchbar" id="search">
    <div class="aui-searchbar-input aui-border-radius" tapmode >
        <i class="aui-iconfont aui-icon-search"></i>
        <form method="get" id="track">
            <input type="search" placeholder="请输入运单号或柜号" name="waybill" value="{{request()->input('waybill')}}" >
        </form>
    </div>
    <div class="aui-searchbar-cancel" onclick="document.getElementById('track').submit()" tapmod>查询</div>
</div>
@if( request()->input('waybill'))
	@if( !empty( $order ) )
<div class="order-det-info">
	<div class="info-info">
		<span>订单号</span>
		<span>{{ $order->order_sn  }}</span>
	</div>
	<div class="info-info">
		<span>下单时间</span>
		<span>{{$order->created_at}}</span>
	</div>
	<div class="info-info">
		<span>订单状态</span>
		<span>{{ data_get( config('global.order_state') , $order->state )}}</span>
	</div>	
	<div class="info-info">
		<span>起目港口</span>
		<span>
			@if( $order->barge_port ) 
            {{$order->fromport->name or ''}}(驳船)&nbsp;->&nbsp; 
            {{$order->bargeport->name or ''}}&nbsp;->&nbsp; 
            @else 
            {{$order->fromport->name or ''}}&nbsp;->&nbsp; 
            @endif 
            @if( $order->barge_to_port )
			{{$order->bargetoport->name or ''}}&nbsp;->&nbsp;
			{{$order->toport->name or ''}}(驳船)
			@else 
			{{$order->toport->name or ''}}
			@endif 
		</span>
	</div>	
	<div class="info-info">
		<span>船公司名称</span>
		<span>{{$order->company->name or '待定'}}</span>
	</div>
	<div class="info-info">
		<span>船名</span>
		<span>{{$order->ship->name or '待定'}}</span>
	</div>
	<div class="info-info">
		<span>航次编号</span>
		<span>{{$order->voyage or '待定'}}</span>
	</div>
</div>


<div class="order-det-info">
	@if( $order->barge_port > 0 )
	<div class="info-header">{{$order->fromport->name or ''}}(驳船)</div>
	<div class="info-content">
	<div class="info-info">
		<span>预计离港时间</span>
		<span class="dates_color">{{str_limit( $order->barge_plan_time , 16 , '' )}}</span>
	</div>
	<div class="info-info">
		<span>实际离港时间</span>
		<span class="dates_color" >{{str_limit( $order->barge_time , 16 , '' )}}</span>
	</div>
	</div>
</div>

<div class="order-det-info">
	<div class="info-header">{{$order->bargeport->name or ''}}</div>
	<div class="info-content">
	<div class="info-info ">
		<span>预计离港时间</span>
		<span class="dates_color">{{ data_get( $from , 'leave_plan_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_plan_date') ) : ''}}</span>
	</div>
	<div class="info-info">
		<span>实际离港时间</span>
		<span class="dates_color">{{ data_get( $from , 'leave_actual_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_actual_date') ) : ''}}</span>
	</div>
	</div>
	@else
	<div class="info-header">{{$order->fromport->name or ''}}</div>
	<div class="info-content">
	<div class="info-info ">
		<span>预计离港时间</span>
		<span class="dates_color">{{ data_get( $from , 'leave_plan_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_plan_date') ) : ''}}</span>
	</div>
	<div class="info-info ">
		<span>实际离港时间</span>
		<span class="dates_color">{{ data_get( $from , 'leave_actual_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_actual_date') ) : ''}}</span>
	</div>
	</div>
	@endif
</div>

<div class="order-det-info">	
	@if( $order->barge_to_port > 0 )
	<div class="info-header">{{$order->bargetoport->name or ''}}</div>
	<div class="info-content">
	<div class="info-info">
		<span>预计到港时间</span>
		<span class="dates_color">{{ data_get( $to , 'arrive_plan_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_plan_date') ) : ''}}</span>
	</div>
	<div class="info-info">
		<span>实际到港时间</span>
		<span class="dates_color">{{ data_get( $to , 'arrive_actual_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_actual_date') ) : ''}}</span>
	</div>
	</div>
</div>

<div class="order-det-info">	
	<div class="info-header">{{$order->toport->name or ''}}</div>
	<div class="info-content">
	<div class="info-info">
		<span>预计驳船到港时间</span>
		<span class="dates_color">{{str_limit( $order->barge_to_plan_time , 16 , '' )}}</span>
	</div>
	<div class="info-info">
		<span>实际驳船到港时间</span>
		<span class="dates_color">{{str_limit( $order->barge_to_time , 16 , '' )}}</span>
	</div>
	</div>
	@else
	<div class="info-header">{{$order->toport->name or ''}}</div>
	<div class="info-content">
	<div class="info-info">
		<span>预计到港时间</span>
		<span class="dates_color">{{ data_get( $to , 'arrive_plan_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_plan_date') ) : ''}}</span>
	</div>
	<div class="info-info">
		<span>实际到港时间</span>
		<span class="dates_color">{{ data_get( $to , 'arrive_actual_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_actual_date') ) : ''}}</span>
	</div>
	</div>
	@endif
</div>

<div class="order-det-info" style="margin-bottom:3.5rem;">
	<div class="info-info" style="font-size:.8rem;">
		<span>派送</span>
	</div>
	<div class="info-info" style="border:none;">
		<span>{{  $order->barge_to_remark or '' }}</span>
	</div>
</div>
@else
<section class="aui-content-padded">
    <h5>找不到您想查的信息，您可以联系我们的客服咨询一下</h5>
</section>
@endif
@endif

@endsection
