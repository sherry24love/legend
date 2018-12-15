@extends('layouts.layout')

@section('style')
<style type="text/css">
    .none{display: none;}
    .block{
        display: block;
    }
</style>
@endsection

@section('content')
<div class="center_content index_content">
    <div class="tracking-form" style="min-height: 400px; ">
	   <div class="content_title">
	        <div class="contsl">
	            <div class="contlyz">富裕通物流</div>
	            <div class="contlzw"><span class="">legend56.com</span></div>
	            <div class="contlxz">致力于打造专业船运服务平台</div>
	        </div>
	    </div>
        <div class="row">
        	<div class="track-search-form">
	            <form name="form1" class="form-inline">
	                <div class="form-group">
	                    <input type="text" class="form-control input-lg" value="{{request()->input('waybill')}}" name="waybill" id="waybill" placeholder="请输入运单号或柜号">
	                </div>
	                <input type="submit" class="btn btn-danger btn-lg" value="查询">
	            </form>
            </div>
        </div>
        <div class="row">
        	<div class="search-result">
        	@if( request()->input('waybill'))
        		@if( !empty( $order ) )
        		<table class="table search-table">
	        		<tr>
	        			<th>订单编号</th>
	        			<td>{{ $order->order_sn }}</td>
	        			<th>下单时间</th>
	        			<td >{{$order->created_at}}</td>
	        			<th>订单状态</th>
	        			<td >{{ data_get( config('global.order_state') , $order->state )}}</td>
	        		</tr>
	        		<tr>
	        			<th>船公司名称</th>
	        			<td>{{$order->company->name or '待定'}}</td>

	        			<th>船名</th>
	        			<td>{{$order->ship->name or '待定'}}</td>
	        			<th>航次编号</th>
	        			<td>{{$order->voyage or '待定'}}</td>
	        		</tr>
	        		@if( $order->barge_port > 0 )
	        		<tr>
	        			<th>起运港:</th>
	        			<td>{{$order->fromport->name or ''}}</td>
						<th>预计离港时间:</th>
	        			<td>{{str_limit( $order->barge_plan_time , 10 , '' )}}</td>
						<th>实际离港时间:</th>
	        			<td>{{str_limit( $order->barge_time , 10 , '' )}}</td>
	        		</tr>
	        		<tr>
	        			<th>中转港:</th>
	        			<td>{{$order->bargeport->name or ''}}</td>
						<th>预计离港时间:</th>
	        			<td>{{ data_get( $from , 'leave_plan_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_plan_date') ) : ''}}</td>
						<th>实际离港时间:</th>
	        			<td>{{ data_get( $from , 'leave_actual_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_actual_date') ) : ''}}</td>
	        		</tr>
	        		@else
	        		<tr>
	        			<th>起运港:</th>
	        			<td>{{$order->fromport->name or ''}}</td>
						<th>预计离港时间:</th>
	        			<td>{{ data_get( $from , 'leave_plan_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_plan_date') ) : ''}}</td>
						<th>实际离港时间:</th>
	        			<td>{{ data_get( $from , 'leave_actual_date') ? date('Y-m-d H:i' , data_get( $from , 'leave_actual_date') ) : ''}}</td>
	        		</tr>
	        		@endif
	        		@if( $order->barge_to_port > 0 )
	        		<tr>
	        			<th>目的中转港:</th>
	        			<td>{{$order->bargetoport->name or ''}}</td>
						<th>预计到港时间:</th>
	        			<td>{{ data_get( $to , 'arrive_plan_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_plan_date') ) : ''}}</td>
						<th>实际到港时间:</th>
	        			<td>{{ data_get( $to , 'arrive_actual_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_actual_date') ) : ''}}</td>
	        		</tr>
	        		<tr>
	        			<th>目的港:</th>
	        			<td>{{$order->toport->name or ''}}</td>
						<th>预计到港时间:</th>
	        			<td>{{str_limit( $order->barge_to_plan_time , 10 , '' )}}</td>
						<th>实际到港时间:</th>
	        			<td>{{str_limit( $order->barge_to_time , 10 , '' )}}</td>
	        		</tr>
	        		@else
					<tr>
	        			<th>目的港:</th>
	        			<td>{{$order->toport->name or ''}}</td>
						<th>预计到港时间:</th>
	        			<td>{{ data_get( $to , 'arrive_plan_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_plan_date') ) : ''}}</td>
						<th>实际到港时间:</th>
	        			<td>{{ data_get( $to , 'arrive_actual_date') ? date('Y-m-d H:i' , data_get( $to , 'arrive_actual_date') ) : ''}}</td>
	        		</tr>
	        		@endif
	        		<tr>
	        			<th>派送:</th>
	        			<td colspan="5">{{$order->barge_to_remark or ''}}</td>
	        		</tr>
        		</table>
        		@else
        		<p class="no-result">
        			找不到您想查的信息，您可以联系我们的客服咨询一下
        		</p>
        		@endif
        	@endif
        	</div>

        </div>
	</div>
</div>
<div class="cooperation">
	<h4>各船公司货物跟踪快速入口</h4>
	<div class="cooperation_content">
		<a href="http://elines.coscoshipping.com" target="_blank">中远</a>
		<a href="http://www.antong56.com" target="_blank">泉州安通</a>
		<a href="http://dc.trawind.com" target="_blank">信风</a>
		<a href="http://www.luckytrans.cn" target="_blank">和易</a>
		<a href="http://www.shhede.com" target="_blank">上海合德</a>
	</div>
</div>
@endsection
