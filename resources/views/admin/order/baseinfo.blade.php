<table class="table">
<tbody>
	<tr>
		<th>订单编号</th>
	    <td colspan="5">{{$order->order_sn or ''}}</td>
	</tr>
	<tr>
		<th>托运人</th>
	    <td colspan="3">{{$order->entrust->name or ''}}</td>
	    <th>运输条款</th>
	    <td>{{data_get( config('global.transport_protocol') , $order->transport_protocol )}}</td>
	</tr>
	<tr>
		<th>发货人</th>
	    <td colspan="3">{{$order->sender->name or ''}}</td>
	    <th>柜量/柜型</th>
	    <td>{{$order->goods->box_num or ''}} * {{ data_get( config('global.box_type' ) , data_get( $order->goods , 'box_type' , '' ) ) }}</td>
	</tr>
	<tr>
		<th>收货人</th>
	    <td colspan="3">{{$order->recevier->name or ''}}</td>
	    <th>船公司</th>
	    <td>{{$order->company->name or '' }}</td>
	</tr>
	<tr>
		<th>起运地</th>
	    <td>{{$order->sender->address or ''}}</td>
	    <th>运单号</th>
	    <td>{{$order->waybill or ''}}</td>
	    <td colspan="2">&nbsp;</td>
	</tr>
	
	
	@if( $order->barge_port ) 
	<tr>
		<th>起运港</th>
	    <td>{{$order->fromport->name or ''}}</td>
	    <th>预计驳船离港日期</th>
	    <td>{{str_limit( $order->barge_plan_time , 16 , '' )}}</td>
	    <th>实际驳船离港日期</th>
	    <td>{{str_limit( $order->barge_time , 16 , '' )}}</td>
	</tr>
	<tr>
		<th>起运中转港</th>
	    <td>{{$order->bargeport->name or ''}}</td>
	    <th>预计大船离港日期</th>
	    <td>{{$order->start_time}}</td>
	    <th>大船船名/航次</th>
	    <td>{{$order->ship->name or '' }}/{{$order->voyage }}</td>
	</tr>
	@else
	<tr>
		<th>起运港</th>
	    <td>{{$order->fromport->name or ''}}</td>
	    <th>预计大船离港日期</th>
	    <td>{{$order->start_time}}</td>
	    <th>大船船名/航次</th>
	    <td>{{$order->ship->name or '' }}/{{$order->voyage }}</td>
	</tr>
	
	@endif
	
	@if( $order->barge_to_port )
	
	<tr>
		<th>目的中转港</th>
	    <td>{{$order->bargetoport->name or ''}}</td>
	    <th>预计大船到港日期</th>
	    <td>{{$order->end_time }}</td>
	    <th>目的港驳船船名/航次</th>
	    <td>{{$order->barge_to_flight }}</td>
	</tr>
	<tr>
		<th>目的港</th>
	    <td>{{$order->toport->name or ''}}</td>
	    <th>预计驳船到港日期</th>
	    <td>{{str_limit( $order->barge_to_plan_time , 16 , '' )}}</td>
	    <th>实际驳船到港日期</th>
	    <td>{{str_limit( $order->barge_to_time , 16 , '' )}}</td>
	</tr>
	
	@else
	<tr>
		<th>目的港</th>
	    <td>{{$order->toport->name or ''}}</td>
	    <th>预计大船到港</th>
	    <td>{{$order->end_time }}</td>
	    <td colspan="2">&nbsp;</td>
	</tr>
	
	@endif
	
	<tr>
		<th>目的地</th>
	    <td>{{$order->recevier->address or ''}}</td>
	    <th>货主</th>
	    <td>{{$order->owner or ''}}</td>
	    <td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>
			柜号
		</th>
		<td colspan="5" >
			{{$order->cabinet_no }}
		</td>
	</tr>
	<tr>
		<th>
			封条号
		</th>
		<td colspan="5" >
			{{$order->seal_num}}
		</td>
	</tr>
	<tr>
		<th>拖车费</th>
	    <td>{{$order->trailer_cost or ''}}</td>
	    <th>海运费</th>
	    <td>{{$order->ship_cost or ''}}</td>
	    <th>其他费用</th>
	    <td>{{$order->other_cost or '' }}</td>
	</tr>
	<tr>
		<th>
			费用说明
		</th>
		<td colspan="5" >
			{{$order->costinfo}}
		</td>
	</tr>
	<tr>
		<th>保险信息</th>
	    <td>{{$order->enable_ensure  == 1  ? '需要保险' : '不需要保险' }}</td>
	    <th>保险人</th>
	    <td>{{$order->ensure_name or ''}}</td>
	    <th>保险额度</th>
	    <td>{{$order->insure_goods_worth or '' }}</td>
	</tr>
	<tr>
		<th>返利状态</th>
	    <td>{{$order->rebate_status  == 1  ? '已完成' : '待返利' }}</td>
	    <th>返利金额</th>
	    <td colspan="3">{{$order->rebate or 0}}</td>
	</tr>
	<tr>
		<th>备注</th>
	    <td colspan="5">
	    	{{$order->remark}}
	    </td>
	</tr>
	<tr>
		<th>派送说明</th>
	    <td colspan="5">
	    	{{$order->barge_to_remark}}
	    </td>
	</tr>
</tbody>
</table>
