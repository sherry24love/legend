@extends('layouts.layout')

@section('content')


<div class="container">
    <div class="login_content" style="background:url( {{asset('img/login.bg.jpg')}} ) center no-repeat ;">
        @include('block.regheader')
        <div class="login-tip" style="width: 426px;">
            请登录查看详细订单
        </div>
        <div class="login_box">
            <input type="hidden" value="1" id="send_again"/>
            <form method="post" id="loginform" name="loginform" >
                {{csrf_field()}}
                <ul>
                    <li>账号注册</li>
                    <li>手&nbsp;&nbsp;&nbsp;机 : <input type="text" placeholder="手机号码" maxlength="12" name="mob" id="mob"/></li>
                    <li>验证码 : <input type="text" placeholder="验证码" maxlength="6" name="vcode" id="vcode" style="width:150px;"/>
                        <span class="vcode send_code" onclick="send_code(this)">发送验证码</span>
                    </li>
                    <li>新密码 : <input type="text" placeholder="新密码" maxlength="16" name="pwd" id="pwd"/></li>
                    <li><a class="login_but">立即找回</a></li>
                    <li><span>返回 <a href="{{route('login')}}">登录</a></span></li>
                </ul>
            </form>
        </div>

        @include('block.regfooter')
    </div>
</div>
@endsection

@section('script')
<script>
$(".login_but").click(function(){
    if( !$('#mob').val().trim() ) {
        layer.msg("请填写手机号");
        return false ;
    }
    if( !$('#vcode').val().trim() ) {
        layer.msg("请填写验证码");
        return false ;
    }
    if( !$('#pwd').val().trim() ) {
        layer.msg("请填写验证密码");
        return false ;
    }


    var d =$("form").serialize();
    $.post( location.href , d,function(data){
        if( 0 === data.errcode ){
            location.href='{{route("member")}}';
        }else{
            layer.alert(data);
        }
    } , 'json' );
});

$(document).keydown(function(event){
    if(event.keyCode==13){
        $(".login_but").trigger('click');
    }
});

var time = 180;


function send_code(obj) {
    if (1 == $("#send_again").val()) {
        var mob = $("#mob").val();
        if(!mob){
            layer.msg("请输入手机号码");
            return;
        }
        $("#send_again").val(0);
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
                	layer.msg(data.msg);
                    setTime = setInterval(function () {
                        if (time <= 0) {
                            $("#send_again").val(1);
                            time =60;
                            $(obj).html("再次发送");
                            clearInterval(setTime);
                            return;
                        }
                        time--;
                        var text = time + "秒后重新发送";
                        $(obj).html(text);
                    }, 1000);
                } else {
                    $("#send_again").val(1);
                    time =60;
                    layer.msg(data.msg);
                }
            }
        });
    }
}

</script>
@endsection
