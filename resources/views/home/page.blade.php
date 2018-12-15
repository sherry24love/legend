@extends('layouts.layout')

@section('content')
<div class="center_content container-fluid">
    <div class="row">
	    	<div class="col-sm-8" style="border-right: 1px solid rgba( 102 , 102 , 102 , 0.33 );">
	    	@include('block.leftadv')
			<div class="news-detail">
				<h1 class="page-header">{{$page->title}}</h1>
				<div class="n-date">
					@if( $page->cover ) 
					<img src="{{asset( $page->cover ) }}" style="display: block;max-width: 100%;width: 100%" />
					@endif
				</div>
				<div class="n-cont">
				{!! $page->content !!}
				</div>
			</div>        
		</div>
		<div class="col-sm-4">
		  @include('block.singleadv')
		  <!-- 货物 -->
		  <div class="from_box_r">
		      	@include('block.track')
		    		@include('block.order')
		  </div>
		  @include('block.singlebar')
		  
		  <!-- 服务和常见问题 -->
		  @include('block.newscates')
		</div>
    </div>
</div>
@endsection