@extends('wap.layout')

@section('content')
<div class="aui-tab" id="tab">
    <a class="aui-tab-item @if( trim( request()->input('status') ) == '' ) aui-active @endif " href="{{route('wap.member.order')}}">全部</a>
    <a class="aui-tab-item @if( trim( request()->input('status') ) != '' ) aui-active @endif" href="{{route('wap.member.order' , ['status' => 0 ])}}">待确认</a>
</div>

<div class="all-list">
    <ul class="aui-list aui-media-list order-list">
    @include('wap.member.orderitem')
    </ul>
    @if( $order->lastPage() > 1 )
    <a class="load-more" current-page="{{$order->currentPage()}}" max-page="{{$order->lastPage()}}">点击查看更多</a>
    @endif
</div>

@endsection

@section('footer')
@endsection


@section('script')
<script>
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

$(document).on('tap' , '.btn-detail' , function(){
    var url = $(this).data('href');
    if( url ) {
        location.href = url ;
    }
});
</script>
@endsection