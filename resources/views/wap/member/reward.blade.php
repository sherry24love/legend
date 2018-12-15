@extends('wap.layout')

@section('content')
<div class="all-list">
@if( $list->total() )
<ul class="aui-list aui-media-list order-list" id="list-content">
    @include('wap.member.rewarditem')
</ul>
    @else
<section class="aui-content-padded">
    <h5>您暂时还没有奖励信息</h5>
</section>
    @endif
    @if( $list->lastPage() > 1 )
     <a class="load-more" current-page="{{$list->currentPage()}}" max-page="{{$list->lastPage()}}">点击查看更多</a>
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
            $('#list-content').append( data );
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