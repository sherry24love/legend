@extends('layouts.layout')

@section('content')
<div class="center_content index_content">
	<div class="container">
		<div class="row">
			<ol class="breadcrumb">
			  <li><a href="#">首页</a></li>
			  <li><a href="#">新闻资讯</a></li>
			  <li class="active">{!!data_get( $category ,  $id )!!}</li>
			</ol>
		</div>
	</div>
	<div class="row">
	    	<div class="col-sm-8" style="border-right: 1px solid rgba( 102 , 102 , 102 , 0.33 );">
	    		<!-- 这里可以加一个广告 -->
	    		@include('block.leftadv')
	        	<div class="right_second">
			    <ul class="news-list">
				    	@foreach( $posts as $v )
				    <li>
					    	<span class="pull-right">{{str_limit( $v->updated_at , 10 , '' )}}</span>
					    <a href="{{route('pc.posts.show' , ['id' => $v->id ])}}">{{$v->title}}</a>
				    </li>
				    @endforeach
			    </ul>
			    <div class="pc_page pages">
			        <ul>
			        		{!!$posts->render()!!}
			        </ul>
			    </div>


			    @if( $id == 7 ) 
			    <div class="cooperation">
					<h4>各船公司船期查询快速入口</h4>
					<div class="cooperation_content">
						<a href="http://elines.coscoshipping.com" target="_blank">中远</a>
						<a href="http://www.antong56.com" target="_blank">泉州安通</a>
						<a href="http://dc.trawind.com" target="_blank">信风</a>
						<a href="http://www.luckytrans.cn" target="_blank">和易</a>
						<a href="http://tsct.kuaicang.cn" target="_blank">上海合德</a>
					</div>
				</div>
			    @endif
			</div>
		</div>
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
