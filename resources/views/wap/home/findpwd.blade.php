@extends('wap.layout')

@section('content')

<div class="aui-content aui-margin-t-15">
    <ul class="aui-list aui-form-list">
    	<li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">手机号</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请输入手机号" id="mobile" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">验证码</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请输入验证码" id="verify" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                      <span class="btn-verify" onclick="send_code( this )">发送验证码</span>  
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">新密码</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请输入新密码" id="pwd" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">确认密码</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请再次输入密码" id="confirm_pwd" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
    </ul>
    <div class="aui-margin-15">
    	<div class="aui-btn aui-btn-danger aui-btn-block" id="register">找回密码</div>
    </div>
    <div class="login-link">
    	<a href="{{route('wap.login')}}">返回登录</a>
    </div>
</div>

@endsection

@section('footer')
@endsection

@section('script')
<script type="text/javascript">
var toast = new auiToast();

var time = 180;
var send = false ;
var setTime ;

function send_code(obj) {
    if ( false === send ) {
        send = true ;
        var mob = $("#mobile").val();
        if(!mob){
            toast.fail({
                title:"请输入手机号码",
                duration:2000
            });
            send = false ;
            return send;
        }

        $.ajax({
            url:"{{route('sendsms')}}" ,
            type:'post' ,
            dataType:'json' ,
            data:{
                _token:"{{csrf_token()}}" ,
                'mobile' : mob ,
                'type':'findpwd'
            } ,
            success:function( data ){
                if ( 0  === data.errcode ) {
                    toast.success( {
                        title: '验证码发送成功，请注意查收'
                    });
                    setTime = setInterval(function () {
                        if (time <= 0) {
                            send = false ;
                            time =180;
                            $(obj).html("再次发送");
                            clearInterval(setTime);
                            return;
                        }
                        time--;
                        var text = time + "秒后重新发送";
                        $(obj).html(text);
                    }, 1000);
                } else {
                    send = false ;
                    time =180;
                    toast.fail( {
                        title: data.msg 
                    });
                }
            }
        });
    }
}


$('#register').click(function(){
	var name = $('#mobile').val();
    var verify = $('#verify').val();
	var pwd = $('#pwd').val();
    var confirm_pwd = $('#confirm_pwd').val();
	if( !name ) {
		toast.fail({
		    title:"请输入手机号码",
		    duration:2000
		});
        return false ;
	}
    if( !verify ) {
        toast.fail({
            title:"请输入验证码",
            duration:2000
        });
        return false ;
    }
	if( !pwd ) {
		toast.fail({
		    title:"请输入密码",
		    duration:2000
		});
        return false ;
	}
    if( !confirm_pwd ) {
        toast.fail({
            title:"请再次输入密码",
            duration:2000
        });
        return false ;
    }
    if( pwd != confirm_pwd ) {
        toast.fail({
            title:"两次输入密码密码不一致",
            duration:2000
        });
        return false ;
    }
    

	$.ajax({
		url:"{{route('wap.findpwd')}}" ,
		type:"post" ,
		data:{
			name:name ,
            code:verify ,
			password:pwd ,
			_token:"{{csrf_token()}}"
		},
		dataType:'json' ,
		success:function( data ){
            if(data.errcode === 0 ) {
                toast.success({title:data.msg}) ;
                setTimeout( function(){
                    location.href = "{{route('wap.index')}}";
                } , 1500 );
            } else {
                toast.fail({title:data.msg}) ;
            }
		}
	});
});

</script>
@endsection