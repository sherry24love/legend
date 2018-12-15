<table class="table">
<tbody>
	<tr>
		<th>名称</th>
	    <td>{{$goods->name or ''}}</td>
	    <th>箱量*箱型</th>
	    <td>{{$goods->box_num or ''}}&nbsp;*&nbsp;{{data_get( config('global.box_type') , data_get( $goods , 'box_type') )}}</td>
	    <th>总量</th>
	    <td>{{$goods->total_num or ''}}</td>
	</tr>
	<tr>
		<th>单柜毛重</th>
	    <td>{{$goods->weight or '' }}吨</td>
	    <th>总体积</th>
	    <td>{{$goods->cubage or ''}}m<sup>3</sup></td>
	    <th>包装类型</th>
	    <td>{{$goods->package or ''}}</td>
	</tr>
</tbody>
</table>