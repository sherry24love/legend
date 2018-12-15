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
	<div class="bd-cont">
		<div class="bd-left-fullscreen">
			<div class="title">
				推荐路线
			</div>
			<div class="tab">
				<a class="@if( request('id' , 0 ) == 0 ) active @endif " href="{{route('flight')}}">全部推荐</a>
				@foreach( config('global.flight_type') as $k => $val )
				<a href="{{route('flight' , ['id' => $k ])}}" class="@if( request('id') == $k ) active @endif ">{{$val}}</a>
				@endforeach
			</div>
			@if( $page->count() > 0 )
			<ul class="tj-list">
			@foreach( $page->items() as $v )
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
					<a href="{{route('portprice' , ['fromport' => $v->from_port , 'toport' => $v->to_port ] )}}">
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
					</a>
				</li>
			@endforeach	
			</ul>
			{{$page->render()}}
			@else

				<div class="nodata">
					<div class="nodata-pic"><img src="{{asset( 'images/nodata.jpg')}}"></div>
					<h4>对不起，暂时没有推荐航线</h4>
					<div class="nodata-btn">
						<a href="http://p.qiao.baidu.com/cps/chat?siteId=10618334&userId=23679895" target="_blank" class="big-yellow-btn">咨询客服</a>
						<a href="{{route('checkin' , ['shipment' => request()->input('fromport') , 'destinationport' => request()->input('toport') , 'date' => request()->input('date')])}}" class="big-red-btn">直接订舱</a>
					</div>
				</div>
			@endif
			
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
});
</script>
@endsection