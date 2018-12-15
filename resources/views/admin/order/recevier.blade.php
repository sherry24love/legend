<table class="table">
<tbody>
	<tr>
		<th width="16%">收货人</th>
	    <td colspan="3">{{$recevier->name or ''}}</td>
	</tr>
	<tr>
		<th>收货联系人</th>
	    <td colspan="3">{{$recevier->contact_name or ''}}</td>
	</tr>
	<tr>
		<th>收货联系电话</th>
	    <td colspan="3">{{$recevier->mobile or ''}}</td>
	</tr>
	<tr>
		<th>收货联系邮箱</th>
	    <td colspan="3">{{$recevier->email or ''}}</td>
	</tr>
	<tr>
		<th>收货人证件号码</th>
	    <td colspan="3">{{$recevier->id_no or ''}}</td>
	</tr>
	<tr>
		<th>收货地址</th>
	    <td colspan="3">{{$recevier->address or ''}}</td>
	</tr>
</tbody>
</table>
