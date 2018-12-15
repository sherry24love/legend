@extends('wap.layout')

@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">会员中心</div>
    <a class="aui-pull-right aui-btn" href="{{route('wap.setting')}}">
        <i class="aui-iconfont aui-icon-gear"></i>
    </a>
</header>

@endsection

@section('content')
<div class="personbox aui-margin-b-15">
    @if( $wechat )
    <div class="p-head"><img src="{{ $wechat->avatar }}"></div>
    @else
	<div class="p-head"><img src="{{asset('mobile/images/head.jpg')}}"></div>
    @endif
	<div class="p-name">
        <i class="iconfont icon-gereninfo"></i>&nbsp;{{$user->contact ? $user->contact : $user->name }}<br/>
        <i class="aui-iconfont aui-icon-mobile"></i>&nbsp;{{$user->name}}<br/>
        <i class="iconfont icon-jiangli"></i>&nbsp;{{$money}}
    </div>
</div>
<div class="aui-card-list aui-margin-b-15">
    <div class="aui-card-list-content">
        <ul class="aui-list aui-media-list person-list">
        @if( $wechat && !$user->rsync )
        	<li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.bindwechat')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="iconfont icon-wechat"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">绑定微信</div>
                </div>
            </li>
        @endif
            <li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.order')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="iconfont icon-order"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">我的订单</div>
                </div>
            </li>
            <li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.refund')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="iconfont icon-fanli-copy"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">我的返利</div>
                </div>
            </li>
            <li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.reward')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="iconfont icon-yongjin"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">我的奖励</div>
                </div>
            </li>
            <li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.recom')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="iconfont icon-lanmantuiguang"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">我的推广</div>
                </div>
            </li>
            <li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.qrcode')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="aui-iconfont aui-icon-share"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">推广码</div>
                </div>
            </li>
            <li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.withdraw')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="iconfont icon-tixian"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">我的提现</div>
                </div>
            </li>
            
            <li class="aui-list-item aui-list-item-middle" data-href="{{route('wap.member.bank')}}">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media">
                        <i class="iconfont icon-yinxingqia1"></i>
                    </div>
                    <div class="aui-list-item-inner aui-list-item-arrow">我的银行卡</div>
                </div>
            </li>
        </ul>
    </div>
</div>
<div style="height:2.25rem;">&nbsp;</div>
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