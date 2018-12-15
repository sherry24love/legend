@extends('wap.layout')

@section('content')
<div class="aui-content aui-margin-b-50">
    <ul class="aui-list aui-media-list">
    @include('wap.home.postsitem')
     </ul>
     @if( $posts->lastPage() > 1 )
     <a class="load-more" current-page="{{$posts->currentPage()}}" max-page="{{$posts->lastPage()}}">点击查看更多</a>
     @endif
 </div>

@endsection

@section('footer')

@endsection

@section('script')
<script>
var load = false ;
$('.load-more').click(function(){
    console.log( 1 );
    var that = $(this);
    var currentPage = $(this).attr('current-page') ;
    var maxPage = $(this).attr('max-page') ;
    if( false === load && currentPage < maxPage ) {
        load = true ;
        currentPage = 0 ;
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

</script>
@endsection