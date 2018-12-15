@extends('wap.layout')

@section('content')

<div class="aui-content aui-margin-t-15">
    <ul class="aui-list aui-form-list">
    	<li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">旧密码</div>
                <div class="aui-list-item-input" >
                	<input type="password" placeholder="请输入旧密码" id="oldpwd" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">新密码</div>
                <div class="aui-list-item-input" >
                	<input type="password" placeholder="请输入新密码" id="pwd" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
    </ul>
    <div class="aui-margin-15">
    	<div class="aui-btn aui-btn-danger aui-btn-block" id="mod">保存</div>
    </div>
</div>

@endsection

@section('footer')
@endsection

@section('script')
<script type="text/javascript">
var toast = new auiToast();
$('#mod').click(function(){
	var oldpwd = $('#oldpwd').val();
	var pwd = $('#pwd').val();
	if( !oldpwd ) {
		toast.fail({
		    title:"请输入旧密码",
		    duration:2000
		});
	}
	if( !pwd ) {
		toast.fail({
		    title:"请输入新密码",
		    duration:2000
		});
	}

	$.ajax({
		url:"{{route('wap.modpwd')}}" ,
		type:"post" ,
		data:{
			old_pwd:oldpwd ,
			pwd:pwd ,
			_token:"{{csrf_token()}}"
		},
		dataType:'json' ,
		success:function( data ){
            if(data.errcode === 0 ) {
                toast.success({title:data.msg}) ;
                setTimeout( function(){
                    location.href = "{{route('wap.member')}}";
                } , 1500 );
            } else {
                toast.fail({title:data.msg}) ;
            }
		}
	});
});

</script>
@endsection