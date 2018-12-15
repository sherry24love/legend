@extends('wap.layout')

@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">{{data_get( config('global.flight_type') , $id ) }}</div>
</header>

@endsection


@section('content')
<section class="aui-content">
	<div class="aui-padded-10">
	<div class="title"><span>{{data_get( config('global.flight_type') ,  $key )}}</span></div>
		<div class="bg-white" id="list-content">
	@if( $list->total() )
		@include('wap.home.flightitem')
	@else
		<section class="aui-content-padded">
		    <h5>暂无数据</h5>
		</section>
	@endif
		</div>
	</div>
	</div>
	@if( $list->lastPage() > 1 )
     <a class="load-more" current-page="{{$list->currentPage()}}" max-page="{{$list->lastPage()}}">点击查看更多</a>
    @endif
</section>
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
</script>
@endsection