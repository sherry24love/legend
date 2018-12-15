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
        			@include('block.success')
	        		@include('block.error')
                <div class="list-tab">
                    <ul class="order_list">
	                    <li class="active">
	                    		<a href="javascript:;">我的奖励</a>
	                    </li>
	                </ul>
                </div>
                <div class="row nml" style="margin-bottom:20px;margin-top:20px;">
                    <form name="searchform" id="searchform" class="form-inline">
                        <div class="input-group input-daterange">
                            <input type="text" class="form-control" name="from" value="{{request()->input('from')}}">
                            <span class="input-group-addon">至</span>
                            <input type="text" class="form-control" name="to" value="{{request()->input('to')}}">
                        </div>
                        
                            <input type="button" class="btn btn-default cursor" value="查询" onclick="document.getElementById('searchform').submit()" style="margin-right: 13px;">
                        </div>
                    </form>
                </div>
                
                <div class="order_info">
                		<table class="table table-hover">
                			<thead>
                				<tr>
                					<th>
                						金额
                					</th>
                					<th>
                						运单号
                					</th>
                					<th>
                						订单编号
                					</th>
                					<th>
                						柜数
                					</th>
                					<th>
                						下单时间
                					</th>
                                    <th>
                                        预计到账
                                    </th>
                					<th>
                						状态
                					</th>
                				</tr>
                			</thead>
                			<tbody>
                				@foreach( $list->items() as $val )
                				<tr>
                					
                					<td>
                						{{$val->cash}}
                					</td>
                					<td>
                						{{$val->order->waybill  ? substr_replace( $val->order->waybill , '****' , 4 , 4 ) : '' }}
                					</td>
                					<td>
                						{{$val->order->order_sn  ? substr_replace( $val->order->order_sn , '****' , 4 , 4 ) : '' }}
                					</td>
                					<td>
                						{{data_get( $val->order->goods , 'box_num' ) }}
                					</td>
                					<td>
                						{{$val->order->created_at }}
                					</td>
                                    <td>
                                        {{ str_limit( $val->expect , 10 , '' ) }}
                                    </td>
                					<td>
                						{{$val->status == 1 ? '已确认' : '待确认'}}
                					</td>
                				</tr>
                				@endforeach
                			</tbody>
                		</table>
					<div class="pc_page">
					    <ul>
					    	{{$list->render()}}
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
        format:'yyyy-mm-dd'
    });
});
</script>
@endsection
