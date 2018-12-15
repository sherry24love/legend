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
	                    		<a href="javascript:;">我的银行卡</a>
	                    </li>
	                    <li class="">
	                    		<a href="{{route('member.bank.create')}}" class=""><b>新增银行卡</b></a>
	                    </li>
	                   	
	                </ul>
                </div>
                
                <div class="order_info" style="margin-top:20px;">
                		<table class="table table-border">
                			<thead>
                				<tr>
                					<th>
                						#
                					</th>
                					<th>
                						开户行
                					</th>
                					<th>
                						姓名
                					</th>
                					<th>
                						卡号
                					</th>
                					<th>
                						添加时间
                					</th>
                					<th>
                						操作
                					</th>
                				</tr>
                			</thead>
                			<tbody>
                				@foreach( $bank->items() as $val )
                				<tr>
                					<td>
                						<input type="checkbox"   />
                					</td>
                					
                					<td>
                						{{data_get( config('global.bank') , $val->bank_id ) }}
                					</td>
                					<td>
                						{{$val->name}}
                					</td>
                					<td>
                						{{$val->card_no}}
                					</td>
                					<td>
                						{{$val->created_at}}
                					</td>
                					<td>
                						<a href="{{route('member.bank.edit' , ['id' => $val->id ] )}}" class="btn btn-xs">修改</a>&nbsp;
                						<a data-href="{{route('member.bank.delete' , ['id' => $val->id ] )}}" class="btn btn-xs btn-delete btn-danger">删除</a>
                					</td>
                				</tr>
                				@endforeach
                			</tbody>
                		</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$('.btn-delete').click(function(){
	var that = $(this);
	layer.confirm("您确定要删除本张银行卡信息吗?" , function( index ){
		layer.close( index );
		location.href = that.href ;
	});
});
</script>
@endsection
