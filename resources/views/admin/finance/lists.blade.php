<style>
	.thead thead tr th {
		text-align: center;
		vertical-align: middle;
	}
	.thead tbody tr td {
		text-align: center;
		vertical-align: middle;
	}
</style>
<div class="box">
	<div class="box-header">

		<div class="form-inline pull-left">
	    <form action="{{route('admin.finance.lists')}}" method="get" pjax-container="">
		        <fieldset>
				<!--
		        	<span>
			             <a class="btn btn-sm btn-primary grid-batch-check" ><i class="fa fa-edit"></i> 批量转结</a>
			        </span>
				-->
					<div class="input-group input-group-sm">
					    <span class="input-group-addon"><strong>医院名称</strong></span>
					    <select class="form-control select-org" name="org_id">
					    	@foreach( $orglist as $k => $val )
					    		<option value="{{$k}}" @if( request()->input('org_id') == $k ) selected @endif >{{ $val }}</option>
					    	@endforeach
					    </select>
					</div>
		            <div class="btn-group btn-group-sm">
		                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
		                <a href="{{route('admin.finance.lists')}}" class="btn btn-warning"><i class="fa fa-undo"></i></a>
		            </div>

		        </fieldset>
		</form>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body table-responsive no-padding">
		
			<div class="col-sm-12">
			注：每月1号凌晨统计上一个月报表
			</div>
		
		<table class="table table-bordered thead">
			<thead>
				<tr>
					<th rowspan="2">月份</th>
					<th colspan="2">期初数</th>
					<th colspan="2">本月新增</th>
					<th colspan="2">本月取消</th>
					<th colspan="2">本月可结算</th>
					<th colspan="2">期末结转</th>
					<th colspan="2">操作</th>
				</tr>
				<tr>
					<th>预约量</th>
					<th>已收金额</th>
					<th>预约量</th>
					<th>已收金额</th>
					<th>预约量</th>
					<th>退还金额</th>
					<th>预约量</th>
					<th>可结算金额</th>
					<th>预约量</th>
					<th>已收金额</th>
				</tr>

			</thead>
			<tbody>
			@if( $list )
				@foreach( $list->items() as $val )
				<tr>
					<td>{{date('Y-m' , $val->month ) }}</td>
					<td>{{$val->last_num}}</td>
					<td>{{$val->last_cash}}</td>
					<td>{{$val->register_num}}</td>
					<td>{{$val->register_cash}}</td>
					<td>{{$val->cancel_num}}</td>
					<td>{{$val->cancel_cash}}</td>
					<td>{{$val->check_num}}</td>
					<td>{{$val->check_cash}}</td>
					<td>{{$val->total_num}}</td>
					<td>{{$val->total_cash}}</td>
					<td>
						@if( $val->status == 0 )
							<a href="javascript:void(0)" data-total="{{$val->check_cash}}" class="btn btn-sm btn-primary btn-check" data-id="{{$val->id}}">转结</a>
						@endif
						@if( $val->status == 1 )
							已转结
						@endif
						<a href="{{route('admin.finance.show' , ['org_id' => $val->org_id , 'month' => date('Y-m' , $val->month ) ])}}">查看详细</a>
					</td>
				</tr>
				@endforeach
			@endif	
			</tbody>
		</table>
	</div>
	<div class="box-footer clearfix">
		@if( $list )
			{{$list->render()}}
		@endif
	</div>
	<!-- /.box-body -->
</div>

