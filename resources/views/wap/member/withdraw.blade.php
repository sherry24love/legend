@extends('wap.layout')

@section('style')
<style>
.shop-name{
    font-size: 1rem;
    color:red;
}

.shop-name i {
    font-size: 1.4rem;
}
</style>
@endsection


@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">我的提现列表</div>
    <a class="aui-pull-right aui-btn" href="{{route('wap.withdraw.create')}}">
        <i class="aui-iconfont aui-icon-plus"></i>
    </a>
</header>

@endsection


@section('content')
<div class="aui-content-padded">
可提现金额：{{$moeny or 0 }}<br/>
总提现金额：{{$total or 0 }}
</div>
<div class="aui-tab" id="tab">
    <a class="aui-tab-item @if( trim( request()->input('status') ) == '' ) aui-active @endif " href="{{route('wap.member.withdraw')}}">全部</a>
    <a class="aui-tab-item @if( trim( request()->input('status') ) != '' ) aui-active @endif" href="{{route('wap.member.withdraw' , ['status' => 1 ])}}">已提现</a>
</div>

<div class="all-list">
    <ul class="aui-list aui-media-list order-list">
    @include('wap.member.withdrawitem')
    </ul>
    @if( $withdraw->lastPage() > 1 )
     <a class="load-more" current-page="{{$withdraw->currentPage()}}" max-page="{{$withdraw->lastPage()}}">点击查看更多</a>
     @endif
</div>

@endsection

@section('footer')
@endsection


@section('script')
<script>
var toast = new auiToast();
var load = false ;
$('.load-more').click(function(){
    var that = $(this);
    var currentPage = $(this).attr('current-page') ;
    currentPage = parseInt( currentPage );
    var maxPage = $(this).attr('max-page') ;
    maxPage = parseInt( maxPage );
    if( false === load && currentPage < maxPage ) {
        load = true ;
        $.get( location.href , {'page' : currentPage+1 } , function( data ){
            load = false ;
            $(that).attr('current-page' , currentPage + 1 );
            $('ul.aui-list').append( data );
            if( maxPage == currentPage+1 ) {
                $(that).remove();
            }
        });
    }

});

function cancel ( obj ) {
    var url = $( obj ).data('href');
    if( url ) {
        var d = new auiDialog();
        d.alert({
            title:"弹出提示",
            msg:'您确定要取消提现吗',
            buttons:['取消','确定']
        },function(ret){
            if( ret.buttonIndex == 2 ) {
                $.getJSON( url , {} , function( data ){
                    if( data.errcode === 0 ) {
                        toast.success({
                            'title':data.msg
                        });
                        setTimeout( function(){
                            location.reload();
                        } , 1500 ) ;
                    } else {
                        toast.fail({
                            'title':data.msg
                        });
                    }

                });
            }
        });
        
    }
}

function reason ( obj ) {
    var reason = $(obj).data('reason') ;
    if( reason ) {
        var d = new auiDialog();
        d.alert({
            msg:reason
        });

    }
}
</script>
@endsection