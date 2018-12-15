<style>
<!--
.table-price {
}

.table-price>tbody>tr>th {
	text-align:center;
	vertical-align:middle;
}

.table-price>tbody>tr>td {
	text-align:center;
	vertical-align:middle;
}
.table-price .form-control {
	width:90px;
}
.table-price .inputtext {
	width:60px;
}
.table-price .inputdate {
	width: 130px;
	padding:5px;
}

-->
</style>
<table class="table table-bordered">
<tr>
<th>编号</th>
<td>{{$flight->id}}</td>
<th>船名称</th>
<td>{{$flight->ship->name or ''}}</td>
<th>航次名称</th>
<td>{{$flight->no}}</td>
<td align="right">
	
	<a class="btn btn-xs btn-default" onclick="history.back(-1)">返回</a>
</td>
</tr>
</table>
<hr/>
<form action="{{route('admin.flight.pricestore' , ['id' => $id ])}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">
{{ csrf_field() }}
<table class="table table-bordered table-price">
	<tr>
		<th rowspan="2">起运港<br/>(驳船)</th>
		<th rowspan="2">起运港<br/>(大船）</th>
		<th rowspan="2">目的港<br/>(大船)</th>
		<th rowspan="2">目的港<br/>(驳船)</th>
		<th colspan="">20GP</th>
		<th colspan="">20HP</th>
		<th colspan="">40GP</th>
		<th colspan="">40HQ</th>
		<th rowspan="">操作</th>
	</tr>
	<tr>
		<th>价格<br>是否特价</th>
		<th>价格<br>是否特价</th>
		<th>价格<br>是否特价</th>
		<th>价格<br>是否特价</th>
		<th>
			
			<a class="btn btn-primary btn-add">增加</a>
		</th>
	</tr>
	@foreach( $prices as $price )
	<tr>
		<th>
			<select class="form-control" name="from_port_id[]">
				<option value="0">请选择</option>
				@foreach( $all_port as $k => $val )
				<option @if( data_get( $price , 'from_port_id' ) == $k ) selected @endif value="{{$k}}">{{$val}}</option>
				@endforeach
			</select>
		</th>
		<th>
			<select class="form-control" name="from_barge_port_id[]" >
				<option value="0">请选择</option>
				@foreach( $ports as $k => $val )
				<option @if( data_get( $price , 'from_barge_port_id' ) == $k ) selected @endif value="{{$k}}">{{$val}}</option>
				@endforeach
			</select>
		</th>
		<th>
			<select class="form-control" name="to_barge_port_id[]" >
				<option value="0">请选择</option>
				@foreach( $ports as $k => $val )
				<option @if( data_get( $price , 'to_barge_port_id' ) == $k ) selected @endif value="{{$k}}">{{$val}}</option>
				@endforeach
			</select>
		</th>
		<th>
			<select class="form-control" name="to_port_id[]">
				<option value="0">请选择</option>
				@foreach( $all_port as $k => $val )
				<option @if( data_get( $price , 'to_port_id' ) == $k ) selected @endif value="{{$k}}">{{$val}}</option>
				@endforeach
			</select>
		</th>
		<td>
		
			<input type="text" class="form-control inputtext" 
			name="price[20GP][]" 
			value="{{ data_get( $price , 'price_20gp' , 0 ) }}" />
		</td>
		<td>
			<input type="text" class="form-control inputtext" 
			name="price[20HP][]" 
			value="{{ data_get( $price , 'price_20hp' , 0 ) }}" />
		</td>
		<td>
			<input type="text" class="form-control inputtext" 
			name="price[40GP][]" 
			value="{{ data_get( $price , 'price_40gp' , 0 ) }}" />
		</td>
		<td>
			<input type="text" class="form-control inputtext" 
			name="price[40HQ][]" 
			value="{{ data_get( $price , 'price_40hq' , 0 ) }}" />
		</td>
		<td rowspan="2">
		<a class="btn btn-danger btn-del" >删除</a>
		</td>
	</tr>
	<tr>
		<th>
			<div class="input-group">
				<input type="text" class="form-control inputdate" value="{{$price->from_port_leave_time}}" name="from_port_leave_time[]" placeholder="驳船（大船）出发时间" />
			</div>
		</th>
		<th>
			<div class="input-group">
				<input type="text" class="form-control inputdate" value="{{$price->from_barge_port_arrive_time}}" name="from_barge_port_arrive_time[]" placeholder="驳船（大船）到达时间" />
			</div>
		</th>
		
		<th>
			<div class="input-group">
				<input type="text" class="form-control inputdate" value="{{$price->to_port_leave_time}}" name="to_port_leave_time[]" placeholder="驳船（大船）出发时间" />
			</div>
		</th>
		<th>
			<div class="input-group">
				<input type="text" class="form-control inputdate" value="{{$price->to_barge_port_arrive_time}}" name="to_barge_port_arrive_time[]" placeholder="驳船（大船）到达时间" />
			</div>
		</th>
		
		<td>
			<input type="checkbox" 
				name="promotion[20GP][]" 
				@if( data_get( $price  , 'is_promotion_20gp' , 0 ) == 1 ) checked @endif 
				value="1" 
			/>
		</td>
		<td>
			<input type="checkbox" name="promotion[20HP][]" 
			@if( data_get( $price , 'is_promotion_20hp' , 0 ) == 1 ) checked @endif 
			value="1" 
			/>
		</td>
		<td>
			<input type="checkbox" 
			name="promotion[40GP][]" 
			@if( data_get( $price , 'is_promotion_40gp' , 0 ) == 1 ) checked @endif 
			value="1"
			/>
		</td>
		<td>
			<input type="checkbox" name="promotion[40HQ][]" 
			@if( data_get( $price , 'is_promotion_40hq' ) == 1 ) checked @endif 
			value="1" 
			/>
		</td>
	</tr>
	@endforeach
	
</table>
<div class="box-footer clearfix">
	<button type="submit" class="btn btn-info pull-right">{{ trans('admin::lang.submit') }}</button>
</div>
</form>

<textarea style="display: none;" id="template-tr">
<tr>
	<th>
		<select class="form-control" name="from_port_id[]">
			<option value="0">请选择</option>
			@foreach( $all_port as $k => $val )
			<option value="{{$k}}">{{$val}}</option>
			@endforeach
		</select>
	</th>
	<th>
		<select class="form-control" name="from_barge_port_id[]">
			<option value="0">请选择</option>
			@foreach( $ports as $k => $val )
			<option value="{{$k}}">{{$val}}</option>
			@endforeach
		</select>
	</th>
	<th>
		<select class="form-control" name="to_barge_port_id[]">
			<option value="0">请选择</option>
			@foreach( $ports as $k => $val )
			<option value="{{$k}}">{{$val}}</option>
			@endforeach
		</select>
	</th>
	<th>
		<select class="form-control" name="to_port_id[]">
			<option value="0">请选择</option>
			@foreach( $all_port as $k => $val )
			<option value="{{$k}}">{{$val}}</option>
			@endforeach
		</select>
	</th>
	<td>
		<input type="text" class="form-control inputtext" 
		name="price[20GP][]" 
		value="" />
	</td>
	<td>
		<input type="text" class="form-control inputtext" 
		name="price[20HP][]" 
		value="" />
	</td>
	<td>
		<input type="text" class="form-control inputtext" 
		name="price[40GP][]" 
		value="" />
	</td>
	<td>
		<input type="text" class="form-control inputtext" 
		name="price[40HQ][]" 
		value="" />
	</td>
	<td rowspan="2">
	<a class="btn btn-danger btn-del" >删除</a>
	</td>
</tr>
<tr>
	<th>
		<div class="input-group">
			<input type="text" class="form-control inputdate" name="from_port_leave_time[]" placeholder="驳船（大船）出发时间" />
		</div>
	</th>
	<th>
		<div class="input-group">
			<input type="text" class="form-control inputdate" name="from_barge_port_arrive_time[]" placeholder="驳船（大船）到达时间" />
		</div>
	</th>
	<th>
		<div class="input-group">
			<input type="text" class="form-control inputdate" name="to_port_leave_time[]" placeholder="驳船（大船）出发时间" />
		</div>
	</th>
	<th>
		<div class="input-group">
			<input type="text" class="form-control inputdate" name="to_barge_port_arrive_time[]" placeholder="驳船（大船）到达时间" />
		</div>
	</th>
	
	<td>
		<input type="checkbox" name="promotion[20GP][]" value="1" />
	</td>
	<td>
		<input type="checkbox" name="promotion[20HP][]" value="1" />
	</td>
	<td>
		<input type="checkbox" name="promotion[40GP][]" value="1" />
	</td>
	<td>
		<input type="checkbox" name="promotion[40HQ][]" value="1" />
	</td>
</tr>
</textarea>

<script type="text/javascript">


</script>