var s_open = true;
var act = '';//动作
var toast = new auiToast();

//$(function(){
	$(document).on('tap' , '#second' , function(){
		act = $(this).attr('act');
		if( s_open ){
			//如果没有 则允许点击
			sendCode($("#second"),act);
		}
	});

	//注册
	$(document).on('tap' , '.js_reg' , function(){
		act = $(this).attr('act');
		//点击提交注册信息
		var mobile = $('#mobile').val();
		var mb_code = $('#mb_code').val();
		var password = $('#password').val();
		if(!mobile){
			toast.fail({title:"请输入手机号!"});
			$("#mobile").focus(); 
			return false;
		}
		var myreg = /^(((17[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
		if(!myreg.test($("#mobile").val())) 
		{ 
			toast.fail({title:"手机号码格式不正确!"});
			$("#mobile").focus(); 
			return false; 
		} 
			 
		if(!mb_code){
			toast.fail({title:"请填写手机验证码!"});
			$("#mb_code").focus(); 
			return false;
		}
		if(!password){
			toast.fail({title:"请填写密码!"});
			$("#password").focus(); 
			return false;
		}
		$.post('/u/reg',{mobile:mobile,password:password,mb_code:mb_code,act:act},function( res ){
			if( res.errcode == 0 ){
				toast.success({title: res.msg });
				location.href = res.data;
			}else{
				toast.fail({title: res.msg });
			}
		},'json');
	});
	
	//找回密码
	$(document).on('tap' , '#js_findpw' , function(){
		act = $(this).attr('act');
		var mobile = $('#mobile').val();
		var mb_code = $('#mb_code').val();
		var psw = $('#psw').val();
		var rpsw = $('#rpsw').val();
		if(!mobile){
			toast.fail({title:"请输入手机号!"});
			$("#mobile").focus(); 
			return false;
		}
		var myreg = /^(((17[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
		if(!myreg.test($("#mobile").val())) 
		{ 
			toast.fail({title:"手机号码格式不正确!"});
			$("#mobile").focus(); 
			return false; 
		} 
		if(!mb_code){
			toast.fail({title:"请填写手机验证码!"});
			$("#mb_code").focus(); 
			return false;
		}
		if(!psw){
			toast.fail({title:"请填写密码!"});
			$("#psw").focus(); 
			return false;
		}
		if(psw != rpsw){
			toast.fail({title:"两次密码输入不一致!"});
			$("#rpsw").focus(); 
			return false;
		}
		$.post('/u/findPw',{mobile:mobile,psw:psw,rpsw:rpsw,mb_code:mb_code,act:act},function( res ){
			if( res.errcode == 0 ){
				toast.success({title: res.msg });
				location.href = res.data;
			}else{
				toast.fail({title: res.msg });
			}
		 
		},'json');
	});

// 发送验证码
function sendCode(obj,act) {
	var mobile = $("#mobile").val();
	var result = isMobile();
	if (result) {
		var date = new Date();
		// 加载ajax 获取验证码的方法
		var url = '/wap/u/sendforreg' ;
		if( act != 'reg' ) {
			url = '/wap/u/sendforfindpwd' ;
		}
		
		$.get( url ,{ mobile:mobile ,act:act},function( data ){
			if( data.errcode === 0 ) {
				toast.success({
					'title' : data.msg
				});
				setCoutDown(date, obj);
			} else {
				toast.fail({
					'title' : data.msg
				});
			}
			
		},'json');
//		var date = new Date();
//		addCookie("secondsremained", date.toGMTString(), 60);// 添加cookie记录,有效时间60s
//		setCoutDown(date, obj);
	}
}

var nowDate = null;
var time_difference = 0;
var count_down = 0;
function setCoutDown(date, obj) {
	nowDate = new Date();
	time_difference = ((nowDate - date) / 1000).toFixed(0);
	count_down = 60 - time_difference;
	obj.html('倒计时'+count_down);
	if (count_down <= 0) {
		s_open = true;
		obj.removeAttr("disabled");
		obj.text("发送验证码");
//		addCookie("secondsremained", "", 60);// 添加cookie记录,有效时间60s
		return;
	}
	obj.attr("disabled", true);
	obj.html("重新发送(" + count_down + ")");
	s_open = false;
	setTimeout(function() {
		setCoutDown(date, obj)
	}, 1000) // 每1000毫秒执行一次
}
// 发送验证码时添加cookie
function addCookie(name, value, expiresHours) {
	// 判断是否设置过期时间,0代表关闭浏览器时失效
	if (expiresHours > 0) {
		var date = new Date();
		date.setTime(date.getTime() + expiresHours * 1000);
		$.cookie(name, escape(value), {
			expires : date
		});
	} else {
		$.cookie(name, escape(value));
	}
}
// 校验手机号是否合法
function isMobile() {
	var mobile = $("#mobile").val();
	var myreg = /^(((17[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
	if (!myreg.test(mobile)) {
		toast.fail({title: "请输入有效的手机号码！" });
		return false;
	} else {
		return true;
	}
} 
