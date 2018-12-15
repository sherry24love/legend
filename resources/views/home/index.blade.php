@extends('layouts.layout')

@section('style')
<style type="text/css">
    .none{display: none;}
    .block{
        display: block;
    }
</style>
@endsection


@section('content')
<div class="index_banner">
	@include('block.bannerleft')
	<div class="banner-right">
		@include('block.carousel')
	</div>
</div>

<div class="container">
    <div class="row news-activities">
        <div class="col-xs-4 news-list">
            <h4 class="section-title">
              <span class="section-title__text">航线推介</span>
              <a class="section-title__more-link" href="{{route('posts' , ['id' => 7 ])}}" target="_blank">
                <span class="sfi sfi-plus-thin"></span>
                更多船期快讯
              </a>
            </h4>
            <div id="news_list">

                <ul>
                @foreach( $posts_A as $v )
                    <li>
                        <a href="{{route('pc.posts.show' , ['id' => $v->id ])}}" title="{{$v->title}}" class="" style="" target="_blank">
                        {{str_limit( $v->title , 38 )}}
                        </a>
                    </li>
                @endforeach       
                </ul>

            </div>
        
        </div>
        <div class="col-xs-4 news-list">
            <h4 class="section-title">
              <span class="section-title__text">行业资讯</span>
              <a class="section-title__more-link" href="{{route('posts' , ['id' => 5 ])}}" target="_blank">
                <span class="sfi sfi-plus-thin"></span>
                更多行业资讯
              </a>
            </h4>
            <div id="news_list">

                <ul>
                @foreach( $posts_B as $v )
                    <li>
                        <a href="{{route('pc.posts.show' , ['id' => $v->id ])}}" title="{{$v->title}}" class="" style="" target="_blank">
                        {{str_limit($v->title , 38 )}}
                        </a>
                    </li>
                @endforeach  
                        
                </ul>

            </div>
        
        </div>
        <div class="col-xs-4 news-list">
            <h4 class="section-title">
              <span class="section-title__text">公司资讯</span>
              <a class="section-title__more-link" href="{{route('posts' , ['id' => 6 ])}}" target="_blank">
                <span class="sfi sfi-plus-thin"></span>
                更多公司资讯
              </a>
            </h4>
            <div id="news_list">

                <ul>
                @foreach( $posts_C as $v )
                    <li>
                        <a href="{{route('pc.posts.show' , ['id' => $v->id ])}}" title="{{$v->title}}" class="" style="" target="_blank">
                        {{str_limit( $v->title , 38 ) }}
                        </a>
                    </li>
                @endforeach  
                        
                </ul>

            </div>
        
        </div>
    </div>
</div>



<div class="center_content">
    <ul class="server_list">
        <li>
            <a href="{{route('singlepage' , ['id' => 8 ])}}">
                <i class="iconfont icon-wenhao circle"></i>
                <span>常见问答</span>
            </a>
        </li>
        <li>
            <a href="{{route('singlepage' , ['id' => 11 ])}}">
                <i class="iconfont icon-liaotianduihua circle"></i>
                <span>快速咨询</span>
            </a>
        </li>
        <li>
            <a href="{{route('singlepage' , ['id' => 6 ])}}">
                <i class="iconfont icon--fuwu circle"></i>
                <span>服务</span>
            </a>
        </li>
        {{--
        <li>
            <a href="{{route('singlepage' , ['id' => 7 ])}}">
                <i class="iconfont icon-hongbao circle"></i>
                <span>返利规则</span>
            </a>
        </li>
        --}}
        <li style="margin-right: 0px;">
            <a href="{{route('singlepage' , ['id' => 2 ])}}">
                <i class="iconfont icon--kefu circle"></i>
                <span>联系我们</span>
            </a>
        </li>
    </ul>
</div>
@endsection

@section('script')
<script>
$(".track-search").click(function(){
    var no = $('#waybill').val();
    if( !no ) {
        layer.msg("请输入运单号或者柜号");
        return false ;
    }
    $("#trackform").submit();
});

$('.quick-order').click( function(){
    var from = $('#shipment').val()  ;
    var to = $('#destinationport').val()  ;
    if( !from ) {
        layer.msg("请选择出发港");
        return false ;
    }
    if( !to ) {
        layer.msg("请选择到达港");
        return false ;
    }
    if( from == to ) {
        layer.msg("出发港和目的港不能一样");
        return false ;
    }

    $('#demandaddform').submit();

} );
$('select').select2({
   matcher: function(term, text) {
       if ( typeof term.term == 'undefined' ) {
			return text ;
	   }
	   var attr = $(text.element).attr('alt');
	   attr = attr ? attr : '' ;
	   
       return text.text.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ||
			attr.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ? text : null ;
   }
});
</script>

@endsection
