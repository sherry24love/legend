<table class="table">
	<tbody>
		<tr>
			<th width="100">订单编号</th>
			<td>{{str_pad( $order->id , 5 , '0' , STR_PAD_LEFT )}}</td>
		</tr>
		<tr>
			<th>订单状态</th>
			<td>{{data_get( config('global.order_status') , $order->order_status ) }}</td>
		</tr>
		<tr>
			<th>支付状态</th>
			<td>{{data_get( config('global.pay_status') , $order->pay_status ) }}</td>
		</tr>
		<tr>
			<th>支付方式</th>
			<td>{{data_get( config('global.pay_type') , $order->pay_type ) }}</td>
		</tr>
		@if( $order->pay_time )
		<tr>
			<th>支付时间</th>
			<td>{{date('Y-m-d H:i:s' , $order->pay_time ) }}</td>
		</tr>
		@endif
		<tr>
			<th>提交时间</th>
			<td>{{$order->created_at}}</td>
		</tr>
		@if( $order->post_script )
		<tr>
			<th>运单编号</th>
			<td>{{$order->post_script}}</td>
		</tr>
		@endif
		@if( $order->shipping_time )
		<tr>
			<th>发货时间</th>
			<td>{{date('Y-m-d H:i:s' , $order->shipping_time ) }}</td>
		</tr>
		@endif
	</tbody>
</table>