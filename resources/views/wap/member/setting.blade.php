@extends('wap.layout')

@section('content')
<div class="aui-card-list">
	<div class="aui-card-list-content">
		<ul class="aui-list aui-media-list person-list">
			<li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.modinfo')}}">
				<div class="aui-media-list-item-inner">
					<div class="aui-list-item-inner aui-list-item-arrow">修改资料</div>
				</div>
			</li>
			<li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.modpwd')}}">
				<div class="aui-media-list-item-inner">
					<div class="aui-list-item-inner aui-list-item-arrow">修改密码</div>
				</div>
			</li>
			<li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.page' , ['id' => 8 ])}}">
				<div class="aui-media-list-item-inner">
					
					<div class="aui-list-item-inner aui-list-item-arrow">常用帮助</div>
				</div>
			</li>
			<li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.page' , ['id' => 1 ])}}">
				<div class="aui-media-list-item-inner">
					<div class="aui-list-item-inner aui-list-item-arrow">关于富裕通</div>
				</div>
			</li>

		</ul>
	</div>
	<div class="aui-padded-b-10 aui-padded-t-10 aui-padded-l-10 aui-padded-r-10">
		<a href="{{route('wap.logout')}}" class="aui-btn aui-btn-danger aui-btn-block">退出登录</a>
	</div>
</div>
@endsection

@section('script')
<script >
$(document).on('tap' , 'li.aui-list-item' , function(){
	var url = $(this).data('href');
	if( url ) {
		location.href = url ;
	}
}) ;

</script>

@endsection