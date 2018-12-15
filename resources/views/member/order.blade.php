@extends('layouts.layout')

@section('style')
<style type="text/css">
    .none{display: none;}
    .block{
        display: block;
    }
    #newBridge .icon-right-center {display: none;}
.nb-icon-inner-wrap {display: none;}
</style>
@endsection


@section('content')

<div class="container">
    <div class="row">
        @include('block.member_left')
		
        <div class="col-sm-10 npl npr">
            <div class="right_second">
                <div class="list-tab">
                    <ul class="order_list">
                    <li data-status="" @if( trim( request()->input('status') ) == '' )class="active" @endif>
                    		<a href="{{route('member.order')}}">全部</a>
                    	</li>
	                @foreach( config('global.order_state') as $k => $val )
                    <li data-status="{{$k}}" class="
                    		@if( trim( request()->input('status') ) != '' 
                    			&& request()->input('status') == $k )
                    		active
                    		@endif
                    	">
                    		<a href="{{route('member.order' , ['status' => $k ] )}}">{{$val}}</a>
                    	</li>
	                @endforeach
	                </ul>
                </div>
                <div class="row nml" style="margin-bottom:20px;margin-top:20px;">
                    <form name="searchform" id="searchform" class="form-inline">
                    		<div class="form-group">
                            <input type="text" class="form-control" name="order_no" id="order_no" placeholder="请输入运单\订单号" value="{{request()->input('order_no')}}">
                        </div>
                        <input type="hidden" name="status" value="{{request()->input('status')}}" />
                        <div class="input-group input-daterange">
						    <input type="text" class="form-control" id="from" name="from" value="{{request()->input('from')}}">
						    <span class="input-group-addon">至</span>
						    <input type="text" class="form-control" id="to" name="to" value="{{request()->input('to')}}">
						</div>
                        
                            <input type="button" class="btn btn-default cursor" value="查询" onclick="document.getElementById('searchform').submit()" style="margin-right: 13px;">
                            &nbsp;&nbsp;
                            @if( in_array( request()->input('status') , [ 2 , 3 , 4 ] ) || trim( request()->input('status') ) == ''  )
                            <input type="button" class="btn btn-default cursor export" value="导出" style="margin-right: 13px;">
                            @endif
                            
                        </div>
                    </form>
                </div>
                
                <div class="order_info">
                		<table class="table table-hover">
                			<thead>
                				<tr>
                					<th>
                						运单号/订单编号
                					</th>
                					<th>
                						订单状态
                					</th>
                					<th>
                						装卸港口
                					</th>
                					<th>
                						运输条款
                					</th>
                					<th>
                						箱量
                					</th>
                					<th>
                						船公司/船名/船次
                					</th>
                					<th>
                						下单时间
                					</th>
                					<th>
                						查看
                					</th>
                				</tr>
                			</thead>
                			<tbody>
                				@foreach( $order->items() as $val )
                				<tr>
                					
                					<td>
                						{{$val->waybill}}<br/>
                						{{$val->order_sn}}
                					</td>
                					<td>
                						{{data_get( config('global.order_state') , $val->state)}}
                					</td>
                					<td>
                						{{$val->fromport->name or ''}}<br/>{{$val->toport->name or ''}}
                					</td>
                					<td>
                						{{data_get( config('global.transport_protocol') , $val->transport_protocol)}}
                					</td>
                					<td>
                						{{$val->goods->box_num or 0 }} * {{ data_get( config('global.box_type') , data_get( $val->goods , 'box_type' , '' ) ) }}
                					</td>
                					<td>
                						{{$val->company->name or '' }}<br/>{{$val->ship->name or ''}}<br/>{{$val->voyage}}
                					</td>
                					<td>
                						{{str_limit( $val->created_at , 10 , '' )}}
                					</td>
                					<td>
                						
                						<a href="{{route('member.order.show' , ['id' => $val->id ])}}" class="btn btn-xs">查看</a>&nbsp;
                                        @if( $val->state == 0 ) 
                                        <a href="{{route('checkinchange' , ['order_id' => $val->id ])}}" class="btn btn-xs">修改</a>&nbsp;<br/>
                                        @endif
                                        @if( in_array( $val->state , [2 , 3, 4 ] ))
                                        <a href="{{route('checkinchange' , ['order_id' => $val->id ])}}" class="btn btn-xs">更正信息</a>&nbsp;<br/>
                                        @endif
                                        @if( $val->state == 8 ) 
                                        <a href="{{route('checkinchange' , ['order_id' => $val->id ])}}" class="btn btn-xs">重新修改</a>&nbsp;<br/>
                                        @endif

                						@if( in_array( $val->state , [2 , 3, 4 ] ) && $val->waybill )
                						<a href="{{ route('track' , ['waybill' => $val->waybill ])}}" class="btn btn-xs">追踪</a>&nbsp;
                						<a href="{{route('member.order.export' , ['id' => $val->id ])}}" class="btn btn-xs" >导出</a>&nbsp;<br/>
                                        @endif
                                        <a href="{{route('checkin' , ['order_id' => $val->id ])}}" class="btn btn-xs">复制新增</a>&nbsp;
                					</td>
                				</tr>
                				@endforeach
                			</tbody>
                		</table>
					<div class="pc_page">
					    <ul>
					    	{{$order->render()}}
					    </ul>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<link href="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC3/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC3/js/bootstrap-datepicker.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC2/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script type="text/javascript">
$('.input-daterange input').each(function() {
    $(this).datepicker({
        format:'yyyy-mm-dd' ,
        language: 'zh-CN'
    });
});

$('.export').on('click' , function(){
    var from = $('#from').val();
    var to = $('#to').val();
    if( !from ) {
        layer.msg("请选择开始时间");
        return false ;
    }

    if( !to ) {
        layer.msg("请选择结束时间");
        return false ;
    }
    var order_state = $('.order_list li.active').data('status') ;
    location.href = "{{route('order.exportgoods')}}" + '?start=' + from + '&end=' + to + '&order_state=' + order_state ;
});
</script>
<style>
#newBridge .icon-right-center {display: none;}
.nb-icon-inner-wrap {display: none;}
</style>
@endsection
