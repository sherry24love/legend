<table class="table">
<tbody>
	<tr>
		<th width="16%">发货人</th>
	    <td colspan="3">{{$sender->name or ''}}</td>
	</tr>
	<tr>
		<th>发货联系人</th>
	    <td colspan="3">{{$sender->contact_name or ''}}</td>
	</tr>
	<tr>
		<th>发货联系电话</th>
	    <td colspan="3">{{$sender->mobile or ''}}</td>
	</tr>
	<tr>
		<th>发货联系邮箱</th>
	    <td colspan="3">{{$sender->email or ''}}</td>
	</tr>
	<tr>
		<th>装货日期</th>
	    <td colspan="3">{{$sender->load_date or '' }}</td>
	</tr>
	<tr>
		<th>发货地址</th>
	    <td colspan="3">{{$sender->address or ''}}</td>
	</tr>
</tbody>
</table>
