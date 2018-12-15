@if( !empty( $adv ) ) 
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
		@foreach( $adv as $k=> $v )
			<li data-target="#carousel-example-generic" data-slide-to="{{$k}}" @if( $k == 0 ) class="active" @endif ></li>
		@endforeach
		</ol>
	  	<div class="carousel-inner" role="listbox">
	  	
	  	@foreach( $adv as $k=> $v )

			<div class="item @if( $k == 0 ) active @endif" style="width:100%;height:300px;background: url({{ asset( $v->cover )}}) no-repeat center;background-size:cover">
				<a target="_blank" href="{{$v->link or '#'}}" style="width:100%;height:100%;display:block;"></a>
			</div>
		@endforeach

	  	</div>
</div>
@endif