<table class="table">
	<tbody>
		<tr>
			<th width="120">医生名称</th>
			<td>{{$order->doctor->name or '' }}</td>
		</tr>
		<tr>
			<th width="120">医生电话</th>
			<td>{{$order->doctor->mobile or '' }}</td>
		</tr>
		<tr>
			<th width="120">医生单位</th>
			<td>{{$order->doctor->unit_name or '' }}</td>
		</tr>
		<tr>
			<th width="120">诊断说明</th>
			<td>{{$order->diagnosis }}</td>
		</tr>
		<tr>
			<th width="120">医生备注</th>
			<td>{{$order->doctor_remark }}</td>
		</tr>
	</tbody>
</table>
