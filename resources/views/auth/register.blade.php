@extends('layouts.layout')

@section('content')


<div class="container">
    <div class="login_content" style="height:700px;background:url( {{asset('img/login.bg.jpg')}} ) center no-repeat ;">
        @include('block.regheader')
        <div class="login-tip" style="width: 426px;">
            请登录查看详细订单
        </div>
        <div class="login_box">
            <input type="hidden" value="1" id="send_again">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <ul>
                    <li>账号注册</li>
                    <li><em>*</em>手&nbsp;&nbsp;&nbsp;机 : <input type="text" placeholder="手机号码" maxlength="12" name="name" id="name"></li>
                    <li><em>*</em>密&nbsp;&nbsp;&nbsp;码 : <input type="password" placeholder="密码" maxlength="16" name="password" id="password"></li>
                    <li><em>*</em>验证码 : <input type="text" placeholder="验证码" maxlength="6" name="verify" id="verify" style="width:150px;">
                        <span class="vcode send_code" >发送验证码</span>
                    </li>
					<li>推荐人 : <input type="text" placeholder="请输入4位数推广码，没有可以不填" @if( session('rec_id') ) readonly @endif maxlength="12" name="rec_id" value="{{session('rec_id')}}" id="rec_id"></li>
                    <li>联系人 : <input type="text" placeholder="联系人" maxlength="10" name="contact" id="contact"></li>
                    <li>QQ&nbsp;号 : <input type="text" placeholder="QQ号码" name="qq" id="qq" maxlength="11"></li>
                    <li><a class="login_but">立即注册</a></li>
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
    var d =$("form").serialize();
    $.post( location.href , d,function(data){
        if( 0 === data.errcode ){
            location.href='{{route('member')}}';
        }else{
            layer.alert(data.msg );
        }
    } , 'json' );
});

$(document).keydown(function(event){
    if(event.keyCode==13){
        $(".login_but").trigger('click');
    }
});
var time = 180;

$('.send_code').bind('click' , function(){
	var that = $(this);
	send_code( that );
});

function send_code(obj) {
    if (1 == $("#send_again").val()) {
        var mob = $("#name").val();
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
                'type':'reg'
            } ,
            success:function( data ){
                if ( 0  === data.errcode ) {
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
