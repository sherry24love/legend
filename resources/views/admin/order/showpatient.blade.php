<table class="table">
	<tbody>
		<tr>
			<th width="100">患者姓名</th>
			<td>{{$order->consignee}}</td>
		</tr>
		<tr>
			<th width="100">患者电话</th>
			<td>{{$order->mobile}}</td>
		</tr>
		<tr>
			<th width="100">患者性别</th>
			<td>{{data_get( config('global.gender' ) , $order->gender ) }}</td>
		</tr>
		<tr>
			<th width="100">患者年龄</th>
			<td>{{$order->age}}</td>
		</tr>
		<tr>
			<th width="100">收寄地址</th>
			<td>{{$order->address}}</td>
		</tr>
	</tbody>
</table>