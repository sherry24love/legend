<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
	    <title>健康档案</title>
	    <link rel="stylesheet" type="text/css" href="{{asset('mobile/css/aui.css')}}" />
	    <link rel="stylesheet" type="text/css" href="{{asset('mobile/css/iconfont.css')}}" />
	    <link rel="stylesheet" type="text/css" href="{{asset('mobile/css/myStyle.css')}}" />
	</head>
	<body class="bg-white">
		<div class="lose">
			<img src="{{asset('mobile/images/404.png')}}">
			<p>抱歉！您访问的页面不存在</p>
			<div class="lose-back-btn" onclick="location.href='{{route('wap.index')}}'">返回首页</div>
		</div>
	</body>
</html>
