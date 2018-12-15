@extends('layouts.layout')

@section('style')
<style type="text/css">
    .none{display: none;}
    .block{
        display: block;
    }
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
                    <li data-status="{{route('member.withdraw')}}" @if( trim( request()->input('status') ) == '' )class="active" @endif>
                    		<a href="{{route('member.withdraw')}}">全部</a>
                    	</li>
	                @foreach( config('global.withdraw_status') as $k => $val )
                    <li data-status="{{$k}}" class="
                    		@if( trim( request()->input('status') ) != '' 
                    			&& request()->input('status') == $k )
                    		active
                    		@endif
                    	">
                    		<a href="{{route('member.withdraw' , ['status' => $k ] )}}">{{$val}}</a>
                    	</li>
	                @endforeach
	                </ul>
                </div>
                <div class="row nml" style="margin-bottom:20px;margin-top:20px;">
                		<div class="col-sm-5">
                			账户余额:<span class="text-danger">{{ $money or 0 }}</span>
                			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                			<a href="{{route('member.withdraw.create')}}" class="btn btn-xs btn-danger btn-withdraw" >提现</a>
                		</div>
                		<div class="col-sm-2">
                			总提现金额:<span class="text-danger">{{ $total or 0 }}</span>
                		</div>
                </div>
                <div class="row nml" style="margin-bottom:20px;">
                		<div class="col-sm-12">
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
                </div>
                
                <div class="order_info">
                		<table class="table table-hover">
                			<thead>
                				<tr>
                					<th>
                						金额
                					</th>
                					<th>
                						状态
                					</th>
                					<th>
                						银行卡
                					</th>
                					<th>
                						收款人
                					</th>
                					<th>
                						申请时间
                					</th>
                					<th>
                						操作
                					</th>
                				</tr>
                			</thead>
                			<tbody>
                				@foreach( $withdraw->items() as $val )
                				<tr>
                					
                					<td>
                						{{$val->cash}}
                					</td>
                					<td>
                						{{data_get( config('global.withdraw_status') , $val->status )}}
                					</td>
                					<td>
                						{{$val->card_no }}
                					</td>
                					<td>
                						{{$val->card_name}}
                					</td>
                					<td>
                						{{$val->created_at}}
                					</td>
                					<td>
                						@if( $val->status == 2 )
                						<a href="javascript:void(0);" data-tip="{{$val->remark}}" class="btn btn-xs btn-reason">理由</a>&nbsp;
                						@endif
                						@if( $val->status == 0 )
                						<a data-href="{{route('member.withdraw.cancel' , ['id' => $val->id] )}}" class="btn btn-xs btn-cancel">取消</a>&nbsp;
                						@endif
                					</td>
                				</tr>
                				@endforeach
                			</tbody>
                		</table>
					<div class="pc_page">
					    <ul>
					    	{{$withdraw->render()}}
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
$('.btn-reason').click(function(){
    layer.alert( $(this).data('tip')) ;
});

$('.btn-cancel').click( function(){
    var that = $(this);
    layer.confirm('您确定要取消这次提现申请吗?' , function( index ){
        layer.close(index);
        location.href= that.data('href');
    }) ;

});
</script>
@endsection
