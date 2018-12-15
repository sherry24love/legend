<table class="table">
	<tbody>
		<tr>
			<th width="120">是否有毒性药品</th>
			<td>{{$order->has_fatal ? '是' : '否' }}</td>
		</tr>
		<tr>
			<th width="120">医生是否签名</th>
			<td>{{$order->is_sign ? '是' : '否' }}</td>
		</tr>
		<tr>
			<th width="120">药品制法</th>
			<td>{{$order->makemethod->title or '' }}</td>
		</tr>
		<tr>
			<th width="120">药品剂量</th>
			<td>{{$order->dosage}}</td>
		</tr>
		<tr>
			<th>处方详细(RP)</th>
			<td>
				@foreach( $prescription as $val ) 
				<a class="btn btn-default">{{data_get( $val , 'goods_name' ) }} &nbsp;({{data_get( $val , 'num' )}}){{data_get( $val , 'measure_unit')}}</a>
				@endforeach
			</td>
		</tr>
	</tbody>
</table>
