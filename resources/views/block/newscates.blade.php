<div class="box">
    <div class="title">
        <span class="spanOne">新闻中心</span>
    </div>
    <ul class="boxUl clear bulletin">
    	@foreach( $category as $k => $val )
        <li class=""><a href="{{route('posts' , ['id' => $k ])}}">{{$val}}</a></li>
	@endforeach
    </ul>
</div>