function initWxApi(){
	wx.config({
		debug: false,
		appId: WX_APPID, // 必填，公众号的唯一标识
		timestamp: WXJS_TIMESTAMP, // 必填，生成签名的时间戳
		nonceStr: NONCESTR, // 必填，生成签名的随机串
		signature: SIGNATURE,// 必填，签名，见附录1
		jsApiList: [
			'checkJsApi',
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ',
			'onMenuShareWeibo',
			'hideMenuItems',
			'showMenuItems',
			'hideAllNonBaseMenuItem',
			'showAllNonBaseMenuItem',
			'translateVoice',
			'startRecord',
			'stopRecord',
			'onRecordEnd',
			'playVoice',
			'pauseVoice',
			'stopVoice',
			'uploadVoice',
			'downloadVoice',
			'chooseImage',
			'previewImage',
			'uploadImage',
			'downloadImage',
			'getNetworkType',
			'openLocation',
			'getLocation',
			'hideOptionMenu',
			'showOptionMenu',
			'closeWindow',
			'scanQRCode',
			'chooseWXPay',
			'openProductSpecificView',
			'addCard',
			'chooseCard',
			'openCard'
			]
		});
	wx.error(function(res){
		//alert('js授权出错,请检查域名授权设置和参数是否正确');
	})
}

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



function jsApiCall(  ) {
	if( typeof( params ) != 'object' ) {
		params = JSON.parse( params );
	}
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		params ,
		function(res){
			var toast = new auiToast();
			if(res.err_msg == "get_brand_wcpay_request:ok" ) {
				// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
				//查询支付状态 并且
				$.getJSON( '/wap/index/paysuccess?order_id=' + order_id , {} , function(data){
					if( data.errcode == 0 ) {
						//查询支付状态 并且设计是否支付成功 
						//alert('支付成功!');
						toast.success({
							'title': '支付成功!'
						} , function(){
							location.reload();
						});
					} else {
						//支付失败提醒
						//alert('支付失败!');
						toast.fail({
							'title': '支付失败!'
						});
					}
				});
			}
			/**
			 * 由于不需要自动关闭
			else if ( res.err_msg == 'get_brand_wcpay_request:cancel' ) {
				//关闭订单
				$.getJSON( '/wap/index/giveup?order_id=' + order_id , {} , function(data){
					if( data.errcode == 0 ) {
						//查询支付状态 并且设计是否支付成功 
						alert('您取消了支付!');
					} else {
						//支付失败提醒
						alert('支付失败!');
					}
				});
			} else {
				//关闭订单
				$.getJSON( '/wap/index/giveup?order_id=' + order_id , {} , function(data){
					if( data.errcode == 0 ) {
						//查询支付状态 并且设计是否支付成功 
						alert('支付失败!');
					} else {
						//支付失败提醒
						alert('支付失败!');
					}
				});
			}
			**/
		}
	);
}

function callpay() {
	if (typeof WeixinJSBridge == "undefined"){
		if( document.addEventListener ){
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		}else if (document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	}else{
		jsApiCall();
	}
}