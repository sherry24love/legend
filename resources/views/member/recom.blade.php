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
	                    		<a href="javascript:;">我的推广</a>
	                    </li>
	                </ul>
                </div>
                <div class="row nml" style="margin-bottom:20px;margin-top:20px;">
                    <div class="col-sm-2">
                         {!! QrCode::size(130)->margin(0)->generate( route('wap.index' , ['rec_id' => auth()->guard()->user()->id ]) )!!}
                    </div>
                    <div class="col-sm-8">
                    <p>我的推广码：{{str_pad( auth()->guard()->user()->id , 4 , '0' , STR_PAD_LEFT )}}</p>
                    <p>&nbsp;</p>
                    <p>
                        专属链接：{{route('index' , ['rec_id' => str_pad( auth()->guard()->user()->id , 4 , '0' , STR_PAD_LEFT ) ])}}&nbsp;&nbsp;
                        <a class="btn btn-xs btn-default" data-clipboard-text="{{route('index' , ['rec_id' => str_pad( auth()->guard()->user()->id , 4 , '0' , STR_PAD_LEFT ) ])}}">点击复制</a>
                    </p>
                    </div>
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
                						手机
                					</th>
                					<th>
                						姓名
                					</th>
                					<th>
                						注册时间
                					</th>

                				</tr>
                			</thead>
                			<tbody>
                				@foreach( $list->items() as $val )
                				<tr>
                					
                					<td>
                						{{substr_replace($val->name , '****' , 4 , 4 )}}
                					</td>
                					<td>
                						{{substr_replace($val->contact , '****' , 3 )}}
                					</td>
                					<td>
                						{{$val->created_at }}
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
new Clipboard('.btn');
$('.input-daterange input').each(function() {
    $(this).datepicker({
        format:'yyyy-mm-dd'
    });
});
</script>
@endsection
