@extends('wap.layout')
@section('style')
<style>
#qrcode-bg {
	position: fixed;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index:99;
	background: rgba( 0 , 0 , 0 , .7);
}
#qrcode {
	position: fixed;
    top: 120px;
    height: 350px;
    width: 70%;
    background: rgba( 255 , 255 , 255 , 1);
    z-index: 9999;
    left: 15%;
    text-align: center;
    border-radius: 15px;
}

.qrcode {
	margin: 50px auto 0px auto;
	width: 150px;
}
.i-close {
	position: absolute;
	top:10px;
	right:10px;
}
.qrcode-content {
	margin-top: 30px;
	line-height: 25px;
    text-align: center;
    width: 100%;
    color: #000;
    font-size: 16px;
    padding: 10px;
}

</style>

@endsection
	@section('content')
	<div id="aui-slide">
        <div class="aui-slide-wrap" >
        @if( $adv->isEmpty() )
        	<div class="aui-slide-node bg-dark">
	            <a href="#">
	                <img src="{{asset('mobile/images/bd_banner.jpg')}}" />
	            </a>
            </div>
        @else
        	@foreach( $adv as $k=> $v )
            <div class="aui-slide-node bg-dark">
	            <a href="{{$v->link or '#'}}">
	                <img src="{{ asset( $v->cover )}}" />
	            </a>
            </div>
            @endforeach
        @endif
        </div>
        <div class="aui-slide-page-wrap"><!--分页容器--></div>
    </div>

    <div class="bd-p-menu">
    	<div class="bd-menu">
			<a href="{{route('wap.portprice')}}" class="icon1" >
				<span class="iconfont icon-jiagechaxun"></span>
				<strong>运价查询</strong>
			</a>
		</div>
    	<div class="bd-menu">
			<a href="{{route('wap.checkin')}}" class="icon2" >
				<span class="iconfont icon-xiadan"></span>
				<strong>自助订舱</strong>
			</a>
		</div>
		<div class="bd-menu">
			<a class="icon5" id="nav-qq-serve" >
				<span class="iconfont icon-kefu"></span>
				<strong>在线咨询</strong>
			</a>
		</div>
    	<div class="bd-menu">
    		<a href="{{route('wap.recom')}}" class="icon3" >
    			<span class="iconfont icon-lanmantuiguang" ></span>
				<strong>推广奖励</strong>
			</a>
		</div>
    	<div class="bd-menu">
    		<a href="{{route('wap.posts' , ['id' => 4 ])}}" class="icon4">
    			<span class="iconfont icon-youhui"></span>
				<strong>最新优惠</strong>
			</a>
		</div>
		<div class="bd-menu">
    		<a href="tel:0596-6859322" class="icon6">
    			<span class="iconfont icon-dianhua1"></span>
				<strong>客服电话</strong>
			</a>
		</div>
    </div>

    <div class="bd-tab tab-btn">
    	<span class="active">航线推介</span>
    	<span >行业知识</span>
    	<span >公司资讯</span>
    </div>
    <div class="tab-item aui-content aui-margin-b-50">
	    <ul class="aui-list aui-media-list">
	    @foreach( $posts_A as $v )
	        <li class="aui-list-item aui-list-item-arrow">
	            <div class="aui-media-list-item-inner">
	                <div class="aui-list-item-inner">
	                <a href="{{route('wap.show' , ['id' => $v->id ])}}">
	                    <div class="aui-list-item-text">
	                        <div class="aui-list-item-title">{{$v->title}}</div>
	                        <div class="aui-list-item-right">{{date("y/m/d" , strtotime( $v->updated_at ) )}}</div>
	                    </div>
	                    <div class="aui-list-item-text aui-ellipsis-2">
	                        {{$v->description}}
	                    </div>
	                </a>
	                </div>
	            </div>
	        </li>
	    @endforeach
	     </ul>
	     @if( $posts_A->count() >= 8 )
	     <a class="load-more" href="{{route('wap.posts' , ['id' => 7 ])}}">点击查看更多</a>
	     @endif
	 </div>
	 <div class="tab-item aui-content aui-margin-b-50 aui-hide">
	    <ul class="aui-list aui-media-list">
	        @foreach( $posts_B as $v )
	        <li class="aui-list-item aui-list-item-arrow">
	            <div class="aui-media-list-item-inner">
	                <div class="aui-list-item-inner">
	                <a href="{{route('wap.show' , ['id' => $v->id ])}}">
	                    <div class="aui-list-item-text">
	                        <div class="aui-list-item-title">{{$v->title}}</div>
	                        <div class="aui-list-item-right">{{date("y/m/d" , strtotime( $v->updated_at ) )}}</div>
	                    </div>
	                    <div class="aui-list-item-text aui-ellipsis-2">
	                        {{$v->description}}
	                    </div>
	                </a>
	                </div>
	            </div>
	        </li>
	    @endforeach
	     </ul>
	     @if( $posts_B->count() >= 8 )
	     <a class="load-more" href="{{route('wap.posts' , ['id' => 5 ])}}">点击查看更多</a>
	     @endif
	 </div>
	 <div class="tab-item aui-content aui-margin-b-50 aui-hide">
	    <ul class="aui-list aui-media-list">
	        @foreach( $posts_C as $v )
	        <li class="aui-list-item aui-list-item-arrow">
	            <div class="aui-media-list-item-inner">
	                <div class="aui-list-item-inner">
	                <a href="{{route('wap.show' , ['id' => $v->id ])}}">
	                    <div class="aui-list-item-text">
	                        <div class="aui-list-item-title">{{$v->title}}</div>
	                        <div class="aui-list-item-right">{{date("y/m/d" , strtotime( $v->updated_at ) )}}</div>
	                    </div>
	                    <div class="aui-list-item-text aui-ellipsis-2">
	                        {{$v->description}}
	                    </div>
	                </a>
	                </div>
	            </div>
	        </li>
	    @endforeach
	     </ul>
	     @if( $posts_C->count() >= 8 )
	     <a class="load-more" href="{{route('wap.posts' , ['id' => 6 ])}}">点击查看更多</a>
	     @endif
	 </div>
	 <div id="qrcode-bg" style="display: none;"></div>
	 <div id="qrcode" style="display:none;">
	 	<div class="qrcode">
	 		<img src="{{asset('img/QR_code.jpg')}}" />
	 	</div>
	 	<span class="i-close">X</span>
	 	<p class="qrcode-content">您还没有关注公众号<br/>点击长按关注</p>
	 </div>
	@endsection

	@section('script')
	<script src="{{asset('mobile/js/aui-popup.js')}}"></script>
	<script >
	var slide = new auiSlide({
        container:document.getElementById("aui-slide"),
        // "width":300,
        "height":133,
        "speed":500,
        "autoPlay": 3000, //自动播放
        "loop":true,
        "pageShow":true,
        "pageStyle":'dot',
        'dotPosition':'center'
    });

    $('.tab-btn span').click(function( ){
    	var index = $(this).index() ;
    	$(this).siblings().removeClass('active') ;
    	$(this).addClass('active') ;
    	$('.tab-item').addClass('aui-hide');
    	$('.tab-item').eq( index ).removeClass('aui-hide') ;
    });
    @if( $is_weixin && $subscribe )
    $('#qrcode-bg').show();
    $('#qrcode').show();
    $('#qrcode .i-close').on('click' , function(){
    	$('#qrcode-bg').hide();
    	$('#qrcode').hide();
    })
    setTimeout( function(){
    	$('#qrcode-bg').hide();
    	$('#qrcode').hide();
    } , 20000 );
    @endif


	</script>
<!-- WPA Button Begin -->
<script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=XzgwMDgzNTE2OF80NzY5MDdfODAwODM1MTY4Xw"></script>
<script>
$(function(){
    BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: 'nav-qq-serve'});
});
</script>
<!-- WPA Button End -->
	@endsection
