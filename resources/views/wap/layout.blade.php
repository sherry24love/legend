<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1, user-scalable=no">
		<meta http-equiv="cleartype" content="on">
		<meta name="format-detection" content="telephone=no">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<meta name="x5-fullscreen" content="true">
	    <title>富裕通物流</title>
	    <link rel="stylesheet" href="//at.alicdn.com/t/font_z9hurkd8gzo7p66r.css" />
	    <link rel="stylesheet" type="text/css" href="{{asset('mobile/css/aui.css')}}" />
	    <link rel="stylesheet" type="text/css" href="{{asset('mobile/css/aui-slide.css')}}" />
	    <link rel="stylesheet" type="text/css" href="{{asset('mobile/css/main.css')}}" />
	    @section('style')
	    <style>
	    </style>
	    @show
	</head>
	<body>
		@section('header')
		<header class="aui-bar aui-bar-nav bg-red">
		    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
		        <span class="aui-iconfont aui-icon-left"></span>
		    </a>
		    <div class="aui-title">富裕通</div>
		    <a class="aui-pull-right aui-btn" href="{{route('wap.page' , ['id' => 8 ])}}">
		        <i class="aui-iconfont aui-icon-question"></i>
		    </a>
		</header>
		@show
		@yield('content')
		@section('footer')
		<footer class="aui-bar aui-bar-tab" id="footer">
		    <a class="aui-bar-tab-item tapmode" href="{{route('wap.index' , ['t' => time() ])}}" >
		        <i class="aui-iconfont aui-icon-home"></i>
		        <div class="aui-bar-tab-label">首页</div>
		    </a>
		    <a class="aui-bar-tab-item tapmode" href="{{route('wap.checkin', ['t' => time() ])}}">
		        <i class="aui-iconfont aui-icon-cart"></i>
		        <div class="aui-bar-tab-label">订舱</div>
		    </a>
		    <a class="aui-bar-tab-item tapmode" href="{{route('wap.track', ['t' => time() ])}}">
		        <i class="aui-iconfont aui-icon-location"></i>
		        <div class="aui-bar-tab-label">追踪</div>
		    </a>
		    @if( !auth()->guard('wap')->check() )
		    <a class="aui-bar-tab-item tapmode" href="{{route('wap.register', ['t' => time() ])}}" >
		        <i class="aui-iconfont aui-icon-info"></i>
		        <div class="aui-bar-tab-label">注册</div>
		    </a>
		    @endif
		    <a class="aui-bar-tab-item tapmode" href="{{route('wap.member', ['t' => time() ])}}" >
		        <i class="aui-iconfont aui-icon-my"></i>
		        <div class="aui-bar-tab-label">我的</div>
		    </a>
		</footer>
		@show
	</body>
	<script src="{{asset('mobile/js/zepto.min.js')}}"></script>
	<script src="{{asset('mobile/js/touch.js')}}"></script>
	<script src="{{asset('mobile/js/aui-slide.js')}}"></script>
	<script src="{{asset('mobile/js/aui-toast.js')}}"></script>
	<script src="{{asset('mobile/js/aui-dialog.js')}}"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(array('onMenuShareQQ', 'onMenuShareWeibo' , 'checkJsApi',
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ',
			'onMenuShareWeibo', ), false ) ?>);
</script>
<script src="{{asset('packages/clipboard/clipboard.min.js')}}"></script>
<script type="text/javascript">
function initWxShare(shareData){
	wx.ready(function(res){
		//alert('res:'+res);
		//分享
		wx.onMenuShareTimeline({
			title: shareData.desc, // 分享标题
			link: shareData.link, // 分享链接
			imgUrl: shareData.imgUrl, // 分享图标
			success: function () { 
				// 用户确认分享后执行的回调函数
			},
			cancel: function () { 
				// 用户取消分享后执行的回调函数
			}
		});
		wx.onMenuShareAppMessage({
			title: shareData.title, // 分享标题
			desc: shareData.desc, // 分享描述
			link: shareData.link, // 分享链接
			imgUrl: shareData.imgUrl, // 分享图标
			type: shareData.type, // 分享类型,music、video或link，不填默认为link
			dataUrl: shareData.dataUrl, // 如果type是music或video，则要提供数据链接，默认为空
			success: function () { 
				// 用户确认分享后执行的回调函数
			},
			cancel: function () { 
				// 用户取消分享后执行的回调函数
			}
		});
		wx.onMenuShareQQ({
			title: shareData.title, // 分享标题
			desc: shareData.desc, // 分享描述
			link: shareData.link, // 分享链接
			imgUrl: shareData.imgurl, // 分享图标
			success: function () { 
			   // 用户确认分享后执行的回调函数
			},
			cancel: function () { 
			   // 用户取消分享后执行的回调函数
			}
		});
	})
}
	new Clipboard('.btn');
@if( auth()->guard('wap')->check())
initWxShare({
	title:'厦门富裕通物流微信平台' ,
	desc:'简单，何止简单' ,
	link:'{{route('wap.index' , ['rec_id' => str_pad( auth()->guard('wap')->user()->id , 4 , '0' , STR_PAD_LEFT ) ])}}' ,
	imgUrl:"{{asset('img/share_logo.png')}}"

});
@else
initWxShare({
	title:'厦门富裕通物流微信平台' ,
	desc:'简单，何止简单' ,
	link:'{{route('wap.index' )}}' ,
	imgUrl:"{{asset('img/share_logo.png')}}"

});
@endif
</script>
@section('script')
	<script >
	var slide = new auiSlide({
        container:document.getElementById("aui-slide"),
        // "width":300,
        "height":180,
        "speed":500,
        "autoPlay": 3000, //自动播放
        "loop":true,
        "pageShow":true,
        "pageStyle":'dot',
        'dotPosition':'center'
    })
	</script>
	@show
	<style>
	#newBridge .nb-icon-right-top {
    right: 5px;
    top: 65px;
    left: auto;
    bottom: auto;
    display: none;

	</style>
</html>