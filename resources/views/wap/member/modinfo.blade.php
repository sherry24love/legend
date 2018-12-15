@extends('wap.layout')

@section('content')

<div class="aui-content aui-margin-t-15">
    <ul class="aui-list aui-form-list">
    	<li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">手机号码</div>
                <div class="aui-list-item-input" >
                    {{$user->name}}
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">账户资金</div>
                <div class="aui-list-item-input" >
                    {{$money}}
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
        <li class="aui-list-item">

            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">联系人</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请输入联系人" id="contact" value="{{$user->contact}}" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">QQ</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请输入QQ号码" id="qq" value="{{$user->qq}}" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">推荐人</div>
                <div class="aui-list-item-input" >
                    {{$user->recuser->name or ''}}
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">微信绑定</div>
                <div class="aui-list-item-input" >
                    {{ $user->outer_id ? '已绑定' : '未绑定' }}
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
	var contact = $('#contact').val();
	var qq = $('#qq').val();
	
	$.ajax({
		url:"{{route('wap.modinfo')}}" ,
		type:"post" ,
		data:{
			contact:contact ,
			qq:qq ,
			_token:"{{csrf_token()}}"
		},
		dataType:'json' ,
		success:function( data ){
            if(data.errcode === 0 ) {
                toast.success({title:data.msg}) ;
                setTimeout( function(){
                    location.href = "{{route('wap.setting')}}";
                } , 1500 );
            } else {
                toast.fail({title:data.msg}) ;
            }
		}
	});
});

</script>
@endsection