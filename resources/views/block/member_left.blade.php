<div class="col-sm-2 npl">
	<div class="left_bar">
		<div class="user_center">用户中心</div>
		<ul>
		        <li @if( $leftMenu == 'order' )class="li_active" @endif ><a href="{{route('member.order')}}">我的订单</a></li>
		        <li @if( $leftMenu == 'refund' )class="li_active" @endif><a href="{{route('member.refund')}}">我的返利</a></li>
		        <li @if( $leftMenu == 'reward' )class="li_active" @endif><a href="{{route('member.reward')}}">我的奖励</a></li>
		        <li @if( $leftMenu == 'withdraw' )class="li_active" @endif ><a href="{{route('member.withdraw')}}">我的提现</a></li>
		        <li @if( $leftMenu == 'recom' )class="li_active" @endif><a href="{{route('member.recom')}}">我的推广</a></li>
		        <li @if( $leftMenu == 'bank' )class="li_active" @endif><a href="{{route('member.bank') }}">我的银行卡</a></li>
		        <li @if( $leftMenu == 'modpwd' )class="li_active" @endif><a href="{{route('member.modpwd')}}">修改密码</a></li>
		        <li @if( $leftMenu == 'userinfo' )class="li_active" @endif><a href="{{route('member.userinfo')}}">联系人信息</a></li>
		</ul>
	</div>        
</div>