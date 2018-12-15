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
                <div class="aui-list-item-label gray aui-font-size-14">密&nbsp;&nbsp;&nbsp;&nbsp;码</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请输入密码" id="pwd" value="" />
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
                <div class="aui-list-item-label gray aui-font-size-14">联系人</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请输联系人姓名" id="contact" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">QQ</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请输入QQ号码" id="qq" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">推荐人</div>
                <div class="aui-list-item-input" >
                    <input type="text" @if( session('rec_id') ) readonly @endif placeholder="如有推广人请填写推广码" id="rec_id" value="{{session('rec_id')}}" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
    </ul>
    <div class="aui-margin-15">
    	<div class="aui-btn aui-btn-danger aui-btn-block" id="register" onclick="reg()">注册</div>
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
                'type':'reg'
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

var regging = false ;
function reg() {
    if( regging ) {
        return false ;
    }
    regging = true ;
    setTimeout(function(){
        regging = false ;
    } , 5000 ) ;
	var name = $('#mobile').val();
    var verify = $('#verify').val();
	var pwd = $('#pwd').val();
	if( !name ) {
		toast.fail({
		    title:"请输入手机号码",
		    duration:2000
		});
        regging = false ;
        return false ;
	}
    if( !verify ) {
        toast.fail({
            title:"请输入验证码",
            duration:2000
        });
        regging = false ;
        return false ;
    }
	if( !pwd ) {
		toast.fail({
		    title:"请输入密码",
		    duration:2000
		});
        regging = false ;
        return false ;
	}

	$.ajax({
		url:"{{route('wap.register')}}" ,
		type:"post" ,
        beforeSend:function(){
            toast.loading({'title':'加载中'});
            setTimeout(function(){
                regging = false ;
                toast.hide();
            } , 5000 ) ;
        },
		data:{
			name:name ,
            code:verify ,
			password:pwd ,
            conatct:$('#contact').val().trim() ,
            qq:$('#qq').val().trim(),
            rec_id:$('#rec_id').val().trim(),
			_token:"{{csrf_token()}}"
		},
		dataType:'json' ,
		success:function( data ){
            regging = false ;
            toast.hide();
            if(data.errcode === 0 ) {
                toast.success({title:data.msg}) ;
                setTimeout( function(){
                    location.href = "{{route('wap.index')}}";
                } , 1500 );
            } else {
                toast.fail({title:data.msg}) ;
            }
		},
        complete:function(){

        }
	});
}

</script>
@endsection