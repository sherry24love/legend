@extends('layouts.layout')

@section('content')
<div class="center_content index_content">
	<div class="container">
		<div class="row">
			<ol class="breadcrumb">
			  <li><a href="#">首页</a></li>
			  <li><a href="#">新闻资讯</a></li>
			  <li class="active">{{$posts->category->name or ''}}</li>
			</ol>
		</div>
	</div>

	<div class="row">
    	<div class="col-sm-8" style="border-right: 1px solid rgba( 102 , 102 , 102 , 0.33 );">
        	<div class="right_bar">
    <div class="right_second">
        <div class="news-detail">
            <h1 class="page-header">{{$posts->title}}</h1>
            <div class="n-date">{{$posts->created_at}}</div>
            <div class="n-cont">
            	@if( $posts->cover ) 
					<img src="{{asset( $posts->cover ) }}" style="display: block;max-width: 100%;width: 100%" />
				@endif
               <p>
               		{!! $posts->content !!}
               </p>
           </div>
        </div>
    </div>
</div>        </div>
        <div class="col-sm-4">
			@include('block.newscates')
			@include('block.singleadv')
			<div class="from_box_r">
				@include('block.track')
			    @include('block.order')
			</div>
        </div>
    </div>
</div>
@endsection