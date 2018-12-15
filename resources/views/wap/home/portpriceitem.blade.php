@if( $flight->isNotEmpty() )

<div class="title"><span>{{data_get( config('global.flight_type') ,  $key )}}</span></div>
<div class="bg-white">
@foreach( $flight as $v )
	<div class="tj">
		<div class="tj-logo">
		@if( $v->link_type == 0 )
			<a href="javascript:void(0);">
		@endif
		@if( $v->link_type == 1 )
			<a href="{{$v->link}}" target="_blank">
		@endif
			<img src="{{  $v->cover ? asset( $v->cover ) : asset( 'images/bd-r-logo.jpg' )}}">
			</a>
		</div>
		<div class="tj-txt" onclick="location.href='{{route('wap.portprice' , ['fromport' => $v->from_port , 'toport' => $v->to_port ] )}}'">
			<div class="tj-item">
				<span>起运港口：</span><span class="big-txt">{{$v->fromPort->name or '' }}</span>
			</div>
			<div class="tj-item">
				<span>目的港口：</span><span class="big-txt">{{$v->toPort->name or '' }}</span>
			</div>
			<div class="tj-item">
				<span>参考价格：</span><span class="red-big-txt">{{$v->price > 0 ? $v->price : '待定'}}</span>
			</div>
			<div class="tj-item">
				<span>有效期：</span><span class="big-txt">
				@if($v->available_from && $v->available_from != '0000-00-00 00:00:00')
				{{ date('y-m-d' , strtotime( $v->available_from ) )}}
				@endif
				/
				@if( $v->available_to && $v->available_to != '0000-00-00 00:00:00')
				{{ date('y-m-d' , strtotime( $v->available_to ) )}}
				@else
				不限
				@endif
				</span>
			</div>
		</div>
	</div>
@endforeach
<div class="topic-more"><a href="{{route('wap.flight' , ['id' => $key ] )}}">查看更多</a><i class="arrow-left"></i></div>
</div>
@endif