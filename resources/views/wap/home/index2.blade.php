<!DOCTYPE html>
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
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="x5-fullscreen" content="true">
<title>大屏手机就是小米Max群</title>
<meta name="description" content="" />

<link rel="stylesheet" type="text/css" href="{{asset('aui/css/aui.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/im.css')}}">
</head>
<body>
<header class="aui-bar aui-bar-nav bg-red" style="position:fixed;">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">咨询记录</div>
    <a class="aui-pull-right aui-btn" href="#">
        医生主页
    </a>
</header>
<div class="chat-box" id="chatBox">
		
	<div class="netcall-box" id="netcallBox">
		<div class="netcall-mask hide">
			<div class="netcallTip"></div>
		</div>
		<div class="top hide">
			<span class="transferAudioAndVideo switchToAudio" id="switchToAudio">切换音频</span>
			<span class="transferAudioAndVideo switchToVideo" id="switchToVideo">切换视频</span>
			<span class="fullScreenIcon toggleFullScreenButton" id="toggleFullScreenButton" title="切换全屏">&nbsp;</span>
		</div>
		<!-- p2p呼叫界面 -->
		<div class="netcall-calling-box hide">
			<img alt="用户头像" class="avatar">
			<div class="nick"></div>
			<div class="tip">等待对方接听...</div>
			<div class="op">
				<button id="callingHangupButton" class="netcall-button red">挂断</button>
			</div>
		</div>
		<!-- p2p视频界面 -->
		<div class="netcall-show-video hide">
			<div class="netcall-video-left">
				<div class="netcall-video-remote bigView">
					<div class="message"></div>
					<span class="switchViewPositionButton"></span>
				</div>
			</div>
			<div class="netcall-video-right">
				<div class="netcall-video-local smallView">
					<div class="message"></div>
					<span class="switchViewPositionButton"></span>
				</div>
				<div class="operation">
					<div class="control">
						<div class="microphone control-item">
							<div class="slider hide">
								<div class="txt">10</div>
								<input class="microSliderInput" id="microSliderInput1" type="range" min="0" max="10" step="1" value="10" data-orientation="vertical" style="position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0;"><div class="rangeslider rangeslider--vertical" id="js-rangeslider-0"><div class="rangeslider__fill" style="height: 74px;"></div><div class="rangeslider__handle" style="bottom: 68px;"></div></div>
							</div>
							<span class="icon-micro"></span>
						</div>
						<div class="volume control-item">
							<div class="slider hide">
								<div class="txt">10</div>
								<input class="volumeSliderInput" id="volumeSliderInput1" type="range" min="0" max="10" step="1" value="10" data-orientation="vertical" style="position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0;"><div class="rangeslider rangeslider--vertical" id="js-rangeslider-3"><div class="rangeslider__fill" style="height: 74px;"></div><div class="rangeslider__handle" style="bottom: 68px;"></div></div>
							</div>
							<span class="icon-volume"></span>
						</div>
						<div class="camera control-item">
							<span class="icon-camera"></span>
						</div>
					</div>
					<div class="op">
						<button class="hangupButton netcall-button red">挂断</button>
					</div>
					<div class="tip">00 : 00</div>
				</div>
			</div>
		</div>
		<!-- p2p音频界面 -->
		<div class="netcall-show-audio hide">
			<img alt="用户头像" class="avatar">
			<div class="nick">test</div>
			<div class="tip">00 : 00</div>
			<div class="control">
				<div class="microphone control-item ">
					<div class="slider hide">
						<div class="txt">10</div>
						<input class="microSliderInput" id="microSliderInput" type="range" min="0" max="10" step="1" value="10" data-orientation="vertical" style="position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0;"><div class="rangeslider rangeslider--vertical" id="js-rangeslider-1"><div class="rangeslider__fill" style="height: 74px;"></div><div class="rangeslider__handle" style="bottom: 68px;"></div></div>
					</div>
					<span class="icon-micro"></span>
				</div>
				<div class="volume control-item">
					<div class="slider hide">
						<div class="txt">10</div>
						<input class="microSliderInput" id="volumeSliderInput" type="range" min="0" max="10" step="1" value="10" data-orientation="vertical" style="position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0;"><div class="rangeslider rangeslider--vertical" id="js-rangeslider-2"><div class="rangeslider__fill" style="height: 74px;"></div><div class="rangeslider__handle" style="bottom: 68px;"></div></div>
					</div>
					<span class="icon-volume"></span>
				</div>
			</div>
			<div class="op">
				<button class="hangupButton netcall-button red">挂断</button>
			</div>

		</div>
		<!-- 多人音视频互动界面 -->
		<div class="netcall-meeting-box hide" id="netcallMeetingBox"></div>
		<!-- 被叫界面 -->
		<div class="netcall-becalling-box hide">
			<img alt="用户头像" class="avatar">
			<div class="nick"></div>
			<p id="becallingText" class="tip"></p>
			<div class="op">
				<div class="normal">
					<div class="checking-tip">检查插件中...<span class="netcall-icon-checking"></span></div>
					<button class="netcall-button blue beCallingAcceptButton" id="beCallingAcceptButton">
						<span class="txt">接听</span>
						<span class="netcall-icon-checking"></span>
					</button>
					<button class="netcall-button red beCallingRejectButton" id="beCallingRejectButton">
						拒绝
					</button>
				</div>
				<div class="exception">
					<button class="netcall-button blue" id="downloadAgentButton">下载音视频插件</button><br>
					<button class="netcall-button red beCallingRejectButton">拒绝</button>
					<div class="exception-tip">拒绝调用插件申请会导致无法唤起插件,需重启浏览器</div>
				</div>

			</div>
		</div>
		<div class="dialogs hide">
		</div>
	</div>
	<div class="chat-content box-sizing" id="chatContent">
	</div>
	<div class="u-chat-notice hide">您已退出</div>
	<div class="chat-mask hide"></div>
					
</div>

<footer class="aui-bar aui-bar-tab" id="footer" style="text-align:left;">
	<div class="chat-editor box-sizing" id="chatEditor" data-disabled="1">
		<div id="emojiTag" class="m-emojiTag">
			<div class="m-emoji-wrapper" style="width: 500px; height: auto; display: none;">
				<div class="m-emoji-picCol" style="width: 500px; height: 300px;">
				
					<ul class="m-emoji-picCol-ul" style="width: 490px; height: auto;">
					</ul>
				
				</div>
				
				<div class="m-emoji-chnCol" style="width: 500px; height: auto;">
					<div class="m-emoji-chnCol-ul" style="width: auto; height: 50px;">
					
					</div>
				</div>
			
			</div>
		</div>
		<a class="chat-btn u-emoji" id="showEmoji"></a>
		<span class="chat-btn msg-type" id="chooseFileBtn">
			<a class="icon icon-file" data-type="file"></a>
		</span>
		<!--
		<a class="chat-btn u-netcall-audio-link hide" id="showNetcallAudioLink">&nbsp;</a>
		<a class="chat-btn u-netcall-video-link" id="showNetcallVideoLink">&nbsp;</a>
		-->
		<textarea id="messageText" class="chat-btn msg-input box-sizing radius5px p2p" rows="1" autofocus="autofocus" maxlength="500"></textarea>
		<a class="btn-send radius5px" id="sendBtn">发送</a>
		<form action="#" id="uploadForm">
			<input multiple="multiple" type="file" name="file" id="uploadFile" class="hide">
		</form>
	</div>
</footer>

<script>
var leyiconf = {};
leyiconf.home = "{{route('home')}}" ;
var fromUser = {!! json_encode( $from ) !!};
var toUser = {!! json_encode( $to ) !!};

</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{asset('plugin/swiper.min.js')}}"></script>
	<!-- js-->
	
<script src="{{asset('nim/3rd/NIM_Web_SDK_v4.0.0.js')}}"></script>
<script src="{{asset('nim/3rd/NIM_Web_Netcall_v4.0.0.js')}}"></script>
<script src="{{asset('nim/3rd/jquery-1.11.3.min.js')}}"></script>

<script src="{{asset('nim/3rd/platform.js')}}"></script>
<script src="{{asset('nim/im/js/3rd/jquery-ui.min.js')}}"></script>
<script src="{{asset('nim/3rd/rangeslider.min.js')}}"></script>
<!-- 右键菜单-->
<script src="{{asset('nim/im/js/3rd/contextMenu/jquery.ui.position.js')}}"></script>
<script src="{{asset('nim/im/js/3rd/contextMenu/jquery.contextMenu.js')}}"></script>
             
<script src="{{asset('nim/im/js/config.js')}}"></script>
<script src="{{asset('nim/im/js/emoji.js')}}"></script>
<script src="{{asset('nim/im/js/util.js?v=2')}}"></script>
<script src="{{asset('nim/im/js/cache.js?v=2')}}"></script>
<script src="{{asset('nim/im/js/link.js')}}"></script>
<script src="{{asset('nim/im/js/ui.js?v=2')}}"></script>
<script src="{{asset('nim/im/js/widget/uiKit.js?v=2')}}"></script>
<script src="{{asset('nim/im/js/widget/minAlert.js')}}"></script>
<script src="{{asset('nim/im/js/module/base.js')}}"></script>
<script src="{{asset('nim/im/js/module/message.js')}}"></script>
<script src="{{asset('nim/im/js/module/sysMsg.js')}}"></script>
<script src="{{asset('nim/im/js/module/personCard.js')}}"></script>
<script src="{{asset('nim/im/js/module/session.js')}}"></script>
<script src="{{asset('nim/im/js/module/friend.js')}}"></script>
             
<script src="{{asset('nim/im/js/module/team.js')}}"></script>
<script src="{{asset('nim/im/js/module/team_dialog.js')}}"></script>
<script src="{{asset('nim/im/js/module/cloudMsg.js')}}"></script>
<script src="{{asset('nim/im/js/module/notification.js')}}"></script>
<script src="{{asset('nim/im/js/module/netcall.js')}}"></script>
<script src="{{asset('nim/im/js/module/netcall_meeting.js')}}"></script>
<script src="{{asset('nim/im/js/module/netcall_ui.js')}}"></script>
<script src="{{asset('nim/im/js/md5.js?v=2')}}"></script>
<script src="{{asset('nim/im/js/main.js?v=2')}}"></script>

</body>

</html>