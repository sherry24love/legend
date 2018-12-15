@extends('layouts.layout')
@section('style')
<style>
.select2 {
	border: 2px solid #c8c8c8;
    border-radius: 4px;
    width: 200px;
    height: 40px;
    padding: 0 5px;
}

.select2-container--default .select2-selection--single {
	background-color: #fff;
    border: none;
}
.select2-container .select2-selection--single {
	height: 36px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
	line-height: 38px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
	top:4px;
}

</style>

@endsection
@section('content')
<div class="content">
	<div class="search-box">
	<form action="" method="get" id="search-form">
		<select class="in-txt" name="fromport" id="fromport">
			<option value="0">请选择起运港</option>
			@foreach( $ports as $k=> $val )
				<option value="{{data_get( $val , 'id') }}" alt="{{data_get( $val , 'short_py') }}"
				@if( request()->input('fromport') == $k ) selected @endif

				>{{data_get( $val , 'name') }}</option>
			@endforeach
		</select>
		<img src="/images/round.png" id="changeFromTo">
		<select class="in-txt" name="toport" id="toport">
			<option value="0">请选择目的港</option>
			@foreach( $ports as $k=> $val )
				<option value="{{data_get( $val , 'id') }}" alt="{{data_get( $val , 'short_py') }}"
				@if( request()->input('toport') == $k ) selected @endif

				>{{data_get( $val , 'name') }}</option>
			@endforeach
		</select>
		<input type="text" class="in-txt datepicker" name="date" id="date" value="{{request()->input('date')}}" placeholder="请输入装货日期" />
		<a href="javascript:void(0);" class="search-btn search-price">搜索</a>
	</form>
	</div>
	<div class="bd-cont">
		<div class="bd-left">
			<div class="menu">
				<ul>
					<li><a href="#">船公司</a></li>
					<li><a href="#">起运港</a></li>
					<li><a href="#">目的港</a></li>
					<li><a href="#">船名/航次</a></li>
					<li><a href="#">航程</a></li>
					<li><a href="#">20GP</a></li>
					<li><a href="#">40GP</a></li>
					<li><a href="#">40HQ</a></li>
				</ul>
			</div>
			@if( $page->count() > 0 )
			@foreach( $page->items() as $val )
			<div class="item-box">
				<?php
				$dates = array();
				foreach( $val->flight->dates as $v ) {
					$dates[ $v->port_id ] = $v ;
				}
				?>
				<div class="item">
					<div class="logobox"><img src="{{ data_get( data_get( $company , data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'company_id' ) ) , 'cover' ) ? asset( data_get( data_get( $company , data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'company_id' ) ) , 'cover' ) ) : asset('images/logo.jpg') }}"></div>
					<div class="item-name">{{ data_get( data_get( $company , data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'company_id' ) ) , 'name' ) }}</div>
				</div>
				<div class="item">
					<h3>{{data_get( data_get( $ports , $val->from_port_id ) , 'name' )}}</h3>
					<p>预计开船</p>
					<p>{{ data_get( $val , 'from_port_leave_time' ) ? data_get( $val , 'from_port_leave_time' ) : '待定' }}</p>
				</div>
				<div class="item">
					<h3>{{data_get( data_get( $ports , $val->to_port_id ) , 'name' )}}</h3>
					<p>预计到达</p>
					<p>{{ data_get( $val , 'to_barge_port_arrive_time' ) ? data_get( $val , 'to_barge_port_arrive_time' ) : '待定' }}</p>
				</div>
				<div class="item">
					<h4>{{data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'name' )}}<br />{{ data_get( data_get( $val , 'flight' )  , 'no' ) }}</h4>
				</div>
				<div class="item">
					<h5>
						@if( data_get( $val , 'from_port_leave_time' ) && data_get( $val , 'to_barge_port_arrive_time' ) )
						{{ strtotime( data_get( $val , 'to_barge_port_arrive_time' ) ) / 86400 - strtotime( data_get( $val , 'from_port_leave_time' ) ) / 86400 }} 天
						@else
						待定
						@endif
					</h5>
				</div>
				<div class="item">
				@if( $val->price_20gp > 0 )
					<a href="{{route('checkin' , ['shipment' => $val->from_port_id  , 'destinationport' => $val->to_port_id  , 'date' => request()->input('date') , 'box_type' => 1 , 'company_id' => data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'company_id' ) , 'ship_id' => data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'id' )  , 'flight_id' => $val->flight_id ] )}}" class="red-btn mt40"><em>￥</em>&nbsp;{{$val->price_20gp}}</a>
				@else
<a id="{{$val->id}}" class="yellow-btn mt40">咨询客服</a>
                    <script type="text/javascript">
                        $(document).ready(function(){
                                BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: '{{$val->id}}'});
                        });
                    </script>
				@endif
				</div>
				<div class="item">
				@if( $val->price_40gp > 0 )
					<a href="{{route('checkin' , ['shipment' => $val->from_port_id  , 'destinationport' => $val->to_port_id  , 'date' => request()->input('date') , 'box_type' => 3 , 'company_id' => data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'company_id' ) , 'ship_id' => data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'id' )  , 'flight_id' => $val->flight_id ] )}}" class="red-btn mt40"><em>￥</em>&nbsp;{{$val->price_40gp}}</a>
				@else
<a id="{{$val->id}}" class="yellow-btn mt40">咨询客服</a>
                    <script type="text/javascript">
                        $(document).ready(function(){
                                BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: '{{$val->id}}'});
                        });
                    </script>
				@endif
				</div>
				<div class="item">
				@if( $val->price_40hq > 0 )
					<a href="{{route('checkin' , ['shipment' => $val->from_port_id  , 'destinationport' => $val->to_port_id  , 'date' => request()->input('date') , 'box_type' => 4 , 'company_id' => data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'company_id' ) , 'ship_id' => data_get( data_get( data_get( $val , 'flight' ) , 'ship' ) , 'id' )  , 'flight_id' => $val->flight_id ] )}}" class="red-btn mt40"><em>￥</em>&nbsp;{{$val->price_40hq}}</a>
				@else
<a id="{{$val->id}}" class="yellow-btn mt40">咨询客服</a>
                    <script type="text/javascript">
                        $(document).ready(function(){
                                BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: '{{$val->id}}'});
                        });
                    </script>
				@endif
				</div>
			</div>
			@endforeach
			{{$page->render()}}
			@else

				<div class="nodata">
					<div class="nodata-pic"><img src="{{asset( 'images/nodata.jpg')}}"></div>
					<h4>对不起，没搜索到您需要的航线</h4>
					<div class="nodata-btn">
						<a  class="big-yellow-btn" id='qq-serve'>咨询客服</a>
						<a href="{{route('checkin' , ['shipment' => request()->input('fromport') , 'destinationport' => request()->input('toport') , 'date' => request()->input('date')])}}" class="big-red-btn">直接订舱</a>
					</div>
				</div>
			@endif

		</div>

		<div class="bd-right">
			<div class="bd-r-title">推荐路线<a target="_blank" href="{{route('flight')}}" class="pull-right">更多&gt;&gt;</a></div>
			<ul class="tj-list">
			@foreach( $recommend as $v )
				<li>
					<div class="bd-r-logo">
					@if( $v->link_type == 0 )
					<a href="javascript:void(0);">
					@endif
					@if( $v->link_type == 1 )
					<a href="{{$v->link}}" target="_blank">
					@endif
						<img src="{{  $v->cover ? asset( $v->cover ) : asset( 'images/bd-r-logo.jpg' )}}">
					</a>
					</div>
					<p onclick="location.href='{{route('portprice' , ['fromport' => $v->from_port , 'toport' => $v->to_port ] )}}'">
						起运港口：<span class="big-txt">{{$v->fromPort->name or '' }}</span><br />
						目的港口：<span class="big-txt">{{$v->toPort->name or '' }}</span><br />
						参考价格：<span class="red-big-txt">{{$v->price > 0 ? $v->price : '待定'}}</span><br />
						<span style="font-size:12px;">
						有效期：
						@if( $v->available_from && $v->available_from != '0000-00-00 00:00:00')
						{{date('m-d' , strtotime( $v->available_from ) )}}
						/
						@endif
						@if( $v->available_to && $v->available_to != '0000-00-00 00:00:00')
							{{date('m-d' , strtotime( $v->available_to ) )}}
						@else
							不限
						@endif
						</span>
					</p>
				</li>
			@endforeach
			</ul>
		</div>
	</div>
</div>
@endsection

@section('script')
<link href="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC3/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC3/js/bootstrap-datepicker.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC2/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script>
$(document).ready(function(){
	$('.datepicker').datepicker({
	    language:'zh-CN' ,
	    format:'yyyy-mm-dd'
	});

	$('.search-price').on('click' , function(){
		var fromport = $('#fromport').val();
		var toport = $('#toport').val();
		if( fromport == 0 ) {
			layer.msg("请选择起运港");
			return false ;
		}

		if( toport == 0 ) {
			layer.msg("请选择目的港");
			return false ;
		}

		if( fromport == toport ) {
			layer.msg("起运港和目的港不能一致");
			return false ;
		}
		$('#search-form').submit();
	});

	$('#changeFromTo').on('click' , function(){
		var fromport = $('#fromport').val();
		var toport = $('#toport').val();
		$('#fromport').val(toport).trigger('change') ;
		$('#toport').val(fromport).trigger('change') ;
	});
	$('select').select2({
	   matcher: function(term, text) {
		   if ( typeof term.term == 'undefined' ) {
				return text ;
		   }
		   var attr = $(text.element).attr('alt');
		   attr = attr ? attr : '' ;

		   return text.text.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ||
				attr.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ? text : null ;
	   }
	});
    BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: 'qq-serve'});
});
</script>
@endsection
