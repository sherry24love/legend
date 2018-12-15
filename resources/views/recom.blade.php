<html>
	<head>
		<link type="text/css" rel="stylesheet" href="{{asset('css/style.css')}}">
		<script type="text/javascript" src="{{asset('js/styleinterference.js')}}"></script>
		<style type="text/css">html {font-size:20px}</style>
	</head>
	<body>
	<div class="box_pc">
    <a href="{{route('member.recom')}}" title="我的推广">&nbsp;</a>
	    	<div class="title">
	        <span class="big">推广即获现金奖励<span class="money">50元 <b>/柜</b></span></span>
	        <span class="info">马上注册成为推广者</span>
	        <span class="info2">推广成功即获50/柜现金奖励。</span>
	    </div>
    <div class="outbox">
        <em>&nbsp;</em>
        <span><i>&nbsp;</i></span>
        <span class="step"><b class="s">第一步：</b>注册 <b class="c" onclick="location.href='{{route('register')}}'">富裕通</b> 账号</span>
        <span><i class="m2">&nbsp;</i></span>
        <span class="step"><b class="s">第二步：</b>点击 <b class="c" onclick="location.href='{{route('member.recom')}}'">我要推广</b></span>
        <span><i class="m3">&nbsp;</i></span>
        <span class="step"><b class="s">第三步：</b>获取 <b class="c" onclick="location.href='{{route('member.recom')}}'">推广链接</b></span>
        <span><i class="m4">&nbsp;</i></span>
        <span class="step"><b class="s">第四步：</b><b class="c">推广成功</b> ，船到港7天获得奖励</span>
        <em class="n2" style="display:block">&nbsp;</em>
		<span class="text">
<b>1. 推广人可以是个人也可以是企业公司，以手机号码为唯一的用户名；</b>
<b>2. 被推广人通过推广人的推广链接或推广码注册成为会员，即与推广人建立推广关系；</b>
<b>3. 推广关系一旦建立，永久有效，不会变更；</b>
<b>4. 被推广人完成货物装载并配船后，推广人才能获得奖励；</b>
<b>5. 奖励以被推广人实际配船为准，订舱后如取消出货则无奖励；</b>
<b>6. 推广者可随时在网站平台上查询推广所获得的奖励和可提现金额；</b>
<b>7. 船到港7天后推广者可申请提现，推广者在可提现最大额度内自由申请提现；</b>
<b>8. 平台收到提现申请后，将在两个工作日内转账至会员绑定银行卡上，遇节假日则顺延。</b>
<b>动动手指，富裕<strong style="font-size: 18px;color: red;" >YOU</strong>你！还等什么？赶快来注册参与推广吧~~~</b>
<b>更多推广奖励规则可访问---<strong style="font-size: 18px;color: red;" onclick='location.href="{{route('singlepage' , ['id' => 8 ])}}"' class="go">常见问题FAQ</strong></b>

		</span>
    </div>
</div>
</body>
</html>