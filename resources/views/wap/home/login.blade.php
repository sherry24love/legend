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
        @if( $is_weixin && $bind === false )
		<li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">绑定微信</div>
                <div class="aui-list-item-input" >
                	<input class="aui-checkbox" id="is_bind" type="checkbox" name="checkbox" >
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>

        @endif
    </ul>
    <div class="aui-margin-15">
    	<div class="aui-btn aui-btn-danger aui-btn-block" id="login">登录</div>
    </div>
    <div class="login-link">
    	<a href="{{route('wap.register')}}">立即注册</a>
    	<a href="{{route('wap.findpwd')}}">忘记密码</a>
    </div>
</div>

@endsection

@section('footer')
@endsection

@section('script')
<script type="text/javascript">
var toast = new auiToast();
$('#login').click(function(){
	var name = $('#mobile').val();
	var pwd = $('#pwd').val();
	if( !name ) {
		toast.fail({
		    title:"请输入手机号码",
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

	$.ajax({
		url:"{{route('wap.login')}}" ,
		type:"post" ,
		data:{
			name:name ,
			password:pwd ,
			_token:"{{csrf_token()}}",
			is_bind : $('#is_bind').prop('checked') ? 1 : 0 
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
		},
		complete:function( event , xhr , opt ){
            if( event.status != 200 ) {
            	toast.fail({title: '用户名或密码错误!' }) ;
            }
        }
	});
});

</script>
@endsection