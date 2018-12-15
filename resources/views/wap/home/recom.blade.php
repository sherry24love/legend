<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,initial-scale=1.0,width=device-width"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
	    <title>富裕通物流</title>
	    <link rel="stylesheet" href="//at.alicdn.com/t/font_thrhug5k6hepzaor.css" />
	    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}" />
	    @section('style')
	    <style>
            .popup {
                position: fixed;
                top:0px;
                height: 100%;
                display: none;
            }

            .popup .content-block {
                position: relative;
                margin-top: 150px;
                text-align: right;
            }
	    </style>
	    @show
	</head>
	<body>
<script type="text/javascript" src="{{asset('js/styleinterference.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<div id="loading" class="loading up"><i></i></div>
<div class="wrap">
    <div class="box_h5">
        <a class="btn btn-share" data-clipboard-text="{{route('wap.index' , ['rec_id' => str_pad( data_get( auth()->guard('wap')->user() , 'id' ) , 4 , '0' , STR_PAD_LEFT ) ])}}">&nbsp;</a>
        <div class="title">
            <p class="big">推广即获现金奖励</p>
            <p class="money">50元 <b>/柜</b></p>
            <p class="info">马上注册成为推广者</p>
            <p class="info2">推广成功即获50/柜现金奖励。</p>
        </div>
        <div class="outbox">
            <em>&nbsp;</em>
            <p><i>&nbsp;</i></p>
            <p class="step"><b class="s">第一步：</b>注册 <b class="c">富裕通</b> 账号</p>
            <p><i class="m2">&nbsp;</i></p>
            <p class="step"><b class="s">第二步：</b>点击 <b class="c">我要推广</b></p>
            <p><i class="m3">&nbsp;</i></p>
            <p class="step"><b class="s">第三步：</b>获取 <b class="c">推广链接</b></p>
            <p><i class="m4">&nbsp;</i></p>
            <p class="step"><b class="s">第四步：</b><b class="c">推广成功</b> ，船到港7天获得奖励</p>
            <em class="n2">&nbsp;</em>
            <p class="text">
<b>1. 推广人可以是个人也可以是企业公司，以手机号码为唯一的用户名；</b>
<b>2. 被推广人通过推广人的推广链接或推广码注册成为会员，即与推广人建立推广关系；</b>
<b>3. 推广关系一旦建立，永久有效，不会变更；</b>
<b>4. 被推广人完成货物装载并配船后，推广人才能获得奖励；</b>
<b>5. 奖励以被推广人实际配船为准，订舱后如取消出货则无奖励；</b>
<b>6. 推广者可随时在网站平台上查询推广所获得的奖励和可提现金额；</b>
<b>7. 船到港7天后推广者可申请提现，推广者在可提现最大额度内自由申请提现；</b>
<b>8. 平台收到提现申请后，将在两个工作日内转账至会员绑定银行卡上，遇节假日则顺延。</b>
<b>动动手指，富裕YOU你！还等什么？赶快来注册参与推广吧~~~</b>
<b>更多推广奖励规则可访问---<span onclick="location.href='{{route('wap.page' , ['id' => 8 ])}}'" style="font-size: 18px;color: red;font-weight: bold;">常见问题FAQ</span></b>
            </p>
        </div>
    </div>
</div>
<div class="popup popup-share" style="background: rgba(0,0,0,.7);">
  <div class="content-block">
    <img src="{{asset('img/sharepic.png')}}" width="100%" />
  </div>
</div>

<script type="text/javascript" src="{{asset('js/default.js')}}"></script>
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
$('.btn-share').on('click' , function(){
    @if( session('wechat') )
    $('.popup').show();
    $('.popup').on('click' , function(){
        $(this).hide();
    });
    @else
        new Clipboard('.btn-share');
        alert("地址已经复制，您可以发送给您的朋友了！");
    @endif
});

@if( auth()->guard('wap')->check() )
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
    link:'{{route('wap.index')}}' ,
    imgUrl:"{{asset('img/share_logo.png')}}"

});

@endif
</script>
</body>
</html>