<script type="text/javascript" charset="utf-8">
wx.config(
	<?php 
    	echo $js->config(
	    	array(
	    		'onMenuShareQQ', 'onMenuShareWeibo' , 'checkJsApi',
				'onMenuShareTimeline',
				'onMenuShareAppMessage', 'scanQRCode' ,
				'chooseWXPay' , 'chooseImage' , 'previewImage' , 'uploadImage' ,
				"downloadImage", "startRecord", "stopRecord", "onVoiceRecordEnd", "playVoice", "pauseVoice", "stopVoice", 
				"onVoicePlayEnd", "uploadVoice", "downloadVoice"
			), false 
		) ;
	?>
);
</script>