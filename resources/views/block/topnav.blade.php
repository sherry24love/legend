<div class="center_content head_content">
    <ul class="guide_ul">
        <li  class="tab_active"><a href="/" target="_self">首页</a></li>
        <li  class=""><a href="{{route('singlepage' , ['id' => 3 ])}}" target="_self">拖车服务</a></li>
        <li  class=""><a href="{{route('singlepage' , ['id' => 4 ])}}" target="_self">海陆联运</a></li>
        <li  class=""><a href="{{route('checkin')}}" target="_self">在线订舱</a></li>
        <li  class=""><a href="{{route('posts' , ['id' => 7 ])}}" target="_self">航线推介</a></li>
        <li  class=""><a href="{{route('portprice')}}" target="_self">运价查询</a></li>
        <li  class=""><a href="{{route('posts' , ['id' => 4 ])}}" target="_self">最新优惠</a></li>
        <li  class=""><a href="{{route('member.order')}}" target="_self">我的订单</a></li>
        <li  class=""><a href="{{route('recom')}}" target="_blank">推广奖励</a></li>
        <li  class=""><a href="{{route('singlepage' , ['id' => 1 ])}}">关于我们</a></li>
        <li class="pull-right login-panel">
        @if( auth()->guard('web')->guest() )
            <a href="{{route('register')}}">注册</a>
            <a href="{{route('login')}}">登录</a>
            <a href="{{route('member')}}">用户中心</a>
        @else
            <span>欢迎您，<a href="{{route('member')}}">{{ substr_replace( data_get( auth()->guard('web')->user() , 'name' ) , '****' , 4 , 4 ) }}</a></span>
            <a href="{{route('member')}}">用户中心</a>
            <a href="javascript:void(0)" onclick="document.getElementById('logout').submit();">退出</a>
            <form action="{{route('logout')}}" id="logout" method="post">
            {{csrf_field()}}
            </form>
        @endif
        </li>
    </ul>
</div>