@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="login_content" style="background:url( {{asset('img/login.bg.jpg')}} ) center no-repeat ;">

        @include('block.regheader')

        <div class="login-tip">
            请登录查看详细订单
        </div>
        <div class="login_box">
            <form href="" method="post" id="loginform" name="loginform" >
                <ul>
                		{{csrf_field()}}
                    <li>账户登录</li>
                    <li><input type="text" placeholder="手机号码" name="name" id="name"/></li>
                    <li><input type="password" placeholder="密码" name="password" id="password"/></li>
                    <li><a class="login_but">立即登录</a></li>
                    <li>
                    		<span>没有账号，<a href="{{route('register')}}">请先注册</a></span>
                    		<span style="float:right;"><a href="{{route('findpwd')}} ">忘记密码？</a></span>
                    	</li>
                </ul>
            </form>
        </div>

        @include('block.regfooter')
</div>
<script type="text/javascript">
    $(".login_but").click(function(){
        var d =$("form").serialize();
        $.ajax({
            url:location.href ,
            data:d ,
            type:'post' ,
            dataType:'json' ,
            success:function( data ){
                if( data.login === 1 ) {
                        location.href = data.url ;
                } else {
                        layer.msg( '用户名或密码错误!');
                }
            },
            complete:function( event , xhr , opt ){
                if( event.status != 200 ) {
                	layer.msg( '用户名或密码错误!');
                }
            }

        });
    });
    $(document).keydown(function(event){
        if(event.keyCode==13){
            $(".login_but").click();
        }
    });
</script>
</div>
@endsection
