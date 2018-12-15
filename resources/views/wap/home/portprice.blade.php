@extends('wap.layout')

@section('style')
<style>
body {
	height:100%;
}
.topic-more {
	text-align: center;
}
.topic-more .arrow-left {
	border-top: 1px solid #b7b7b7;
    border-right: 1px solid #b7b7b7;
	display: inline-block;
    width: .25rem;
    height: .25rem;
    transform: rotate(45deg);
    vertical-align: .1rem;
    margin-left: .125rem;
}
.bd-tab .active {
    color: #f0464d;
    border-bottom: 2px solid #f0464d;
}
</style>
@endsection

@section('header')

@endsection

@section('content')
	<script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=XzgwMDgzNTE2OF80NzY5MDdfODAwODM1MTY4Xw"></script>
	<section class="aui-content">
	<div class="aui-padded-10">
		<div class="search-box">
			<div class="search-line">
				<div class="s-item" onclick="showCity(this)">
					<div class="name">起运</div>
					<input type="text" class="aui-text-center name" placeholder="{{$fromPort->name or ''}}" id="fromport" />
					<input type="hidden" class="aui-text-center id" value="{{$fromPort->id or 0}}" id="input_fromport" />
				</div>
				<div class="c-ico" id="changeFromTo"></div>
				<div class="s-item" onclick="showCity(this)">
					<div class="name">目的</div>
					<input type="text" class="aui-text-center name" placeholder="{{$toPort->name or ''}}" id="toport" />
					<input type="hidden" class="aui-text-center id" value="{{$toPort->id or 0}}" id="input_toport" />
				</div>
			</div>
			<div class="search-line">
				<div class="s-item arrow" for="date">
					<div class="name" style="width: 4.5rem;margin-right: .5rem;" >装货时间</div>
					<input type="date" value="" id="date" onchange="changeDate( this )" style="line-height: 2rem;text-align:center;width:55%;" />
					<i class="aui-iconfont aui-icon-close d-close" style="display:none;"></i>
				</div>
			</div>
			<div class="aui-padded-t-10 aui-padded-l-15 aui-padded-r-15">
				<a href="javascript:void(0)" class="red-btn search-port">搜索</a>
			</div>
		</div>
	<div id="list-content">
	@if( !$fromPort && !$toPort )
		@if( $hot->isNotEmpty())
		<div class="title"><span>热门航线</span></div>
		<div class="bd-tab">
			<div class="swiper-container">
				<div class="swiper-wrapper">
			@foreach( config('global.flight_type') as $k => $val )
		    	<div class="swiper-slide" data-id="{{$k}}">{{$val}}</div>
		    @endforeach
		    	</div>
		    </div>
	    </div>
		<div class="bg-white" id="flight-hot-content">
		@foreach( $hot as $v )
			<div class="tj">
				<div class="tj-logo">
				@if( $v->link_type == 0 )
					<a href="javascript:void(0);">
				@endif
				@if( $v->link_type == 1 )
					<a href="{{$v->link}}" target="_blank">
				@endif
					<img src="{{  $v->cover ? asset( $v->cover ) : asset( 'images/bd-r-logo.jpg' )}}">
					</a>
				</div>
				<div class="tj-txt" onclick="location.href='{{route('wap.portprice' , ['fromport' => $v->from_port , 'toport' => $v->to_port ] )}}'">
					<div class="tj-item">
						<span>起运港口：</span><span class="big-txt">{{$v->fromPort->name or '' }}</span>
					</div>
					<div class="tj-item">
						<span>目的港口：</span><span class="big-txt">{{$v->toPort->name or '' }}</span>
					</div>
					<div class="tj-item">
						<span>参考价格：</span><span class="red-big-txt">{{$v->price > 0 ? $v->price : '待定'}}</span>
					</div>
					<div class="tj-item">
						<span>有效期：</span><span class="big-txt">
						@if($v->available_from && $v->available_from != '0000-00-00 00:00:00')
						{{ date('y-m-d' , strtotime( $v->available_from ) )}}
						@endif
						/
						@if( $v->available_to && $v->available_to != '0000-00-00 00:00:00')
						{{ date('y-m-d' , strtotime( $v->available_to ) )}}
						@else
						不限
						@endif
						</span>
					</div>
				</div>
			</div>
		@endforeach
		</div>
		@endif

		@each('wap.home.portpriceitem' , $recommend , 'flight')
	@endif	
	</div>
	
	</div>

<div class="city-cont" style="display: none;">
	<div class="city-title" style="text-align: center;font-size: .8rem;font-weight: bold;padding-left: 3rem;"><span id="panel-title">港口</span>  <span class="sel-end" onclick="closeSel()">完成</span></div>
	<div class="hot-port">
		<div class="city-title">热门港口</div>
		<ul class="city-list">
		@foreach( $hot_port as $val )
			<li style="cursor:pointer;" data-id="{{$val->id}}" data-name="{{$val->name}}"><span class="city" data-id="{{$val->id}}">{{$val->name}}</span></li>
		@endforeach
		</ul>
	</div>
	<div class="city-word">
		<div class="city-title">试试城市首字母检索</div>
		<ul class="city-list" id="word_list">
			
		</ul>
	</div>
	<div class="ports-lists" style="display: none;">
		<div class="city-title">港口</div>
		<ul class="city-list">
		@foreach( $ports as $v )
			<li data-id="{{$v->id}}" data-py="{{$v->short_py}}" data-name="{{$v->name}}"><span class="city" >{{$v->name}}</span></li>
		@endforeach
		</ul>
	</div>
</div>
</section>

@endsection

@section('footer')
@endsection

@section('script')
<link rel="stylesheet" type="text/css" href="{{asset('js/swiper/css/swiper.min.css')}}" />
<script type="text/javascript" src="{{asset('js/swiper/js/swiper.jquery.min.js')}}"></script>
<script type="text/javascript">
var ports = [] ;
@if( $ports ) 
	ports = {!!$ports->toJson()!!}
@endif
function tip( msg ) {
	var toast = new auiToast();
	toast.fail({
		'title':msg
	});
}
function showCity(it){
	$('.s-item').removeClass('active')
	$(it).addClass('active');
	if( $(it).find('#fromport').length > 0 ) {
		$('#panel-title').html("请输入起运港");
	} else {
		$('#panel-title').html("请输入目的港");
	}
	var html = '' ;
	for( var i = 65 ; i < 91 ; i++ ) {
		if( i != 73 ) {
			html +='<li style="cursor:pointer;"><span class="word">'+ String.fromCharCode( i ) +'</span></li>';
		}
	}
	$('#word_list').html( html );
	$('.hot-port').show();
	$('.city-word').show();
	$('.ports-lists').hide();
	$('body').css('overflow-y' , 'hidden').height( $(window).height() );
	$('.aui-content').height( $(window).height() );
	$('#list-content').hide();
	$('.city-cont').addClass('show').height( $(window).height() - 67).show();
	if( navigator.userAgent.indexOf('Mac') ) {

	}
}

function closeSel(){
	$('.city-cont').removeClass('show').hide();
	$('.s-item').removeClass('active');
	$('body').css('overflow-y' , 'auto');
	$('body').css('height' , 'auto');
	$('#list-content').show();
	$('.aui-content').css('height' , 'auto');
}
var type = '' ;

$('#ports-list li').not('.alpha').click(function(){
    var id = $(this).data('id');
    var name = $(this).data('name');
    $( '#' + type ).attr('placeholder' , name );
    $( '#input_' + type ).val( id );
    $( '#top' ).hide();
});

var bind_name = 'input';
if (navigator.userAgent.indexOf("MSIE") != -1){
　　 bind_name = 'propertychange';
}
function changeDate( obj ) {
	if( $(obj).val() != '' ) {
		if( navigator.userAgent.indexOf('Mac') == -1 ) {
			$('.d-close').show();	
		}
	} else {
		$('.d-close').hide();
	}
}

$(function(){
	var mySwiper = new Swiper ('.swiper-container', {
		slidesPerView:4
	});

	$(document).on('click' , '.swiper-slide' , function( e ){
		var id = $(this).data('id');
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		$.get("{{route('wap.flight.hot')}}" , {id:id} , function( data ){
			$('#flight-hot-content').html( data );
			$('.swiper-slide').each(function( i ){
				if( $(this).hasClass('active') ) {
					mySwiper.slideTo( i );
				}
			})
		});
	});

	$('.swiper-slide').eq(0).trigger('click');


	$(document).on('click' , '.d-close' , function( ){
		$('#date').val("");
		$(this).hide();
	});
	$(document).on('click' , '.hot-port .city-list li,.ports-lists .city-list li' , function(){
		var thisTxt = $(this).text();
		var id = $(this).data('id');
		$('.s-item.active').find('input.name').attr('placeholder' ,thisTxt).val("");
		$('.s-item.active').find('input.id').val(id);
		var html = '' ;
		for( var i = 65 ; i < 91 ; i++ ) {
			if( i != 73 ) {
				html +='<li style="cursor:pointer;" ><span class="word">'+ String.fromCharCode( i ) +'</span></li>';
			}
		}
		$('#word_list').html( html );
		$('.hot-port').show();
		$('.city-word').show();
		$('.ports-lists').hide();
		if($('.s-item').eq(1).hasClass('active')){
			closeSel()
		}else{
			$('.s-item').removeClass('active')
			$('.s-item').eq(1).addClass('active')
			$('#panel-title').html("请输入目的港");

		}
	});

	$(document).on('click' , '.word' , function(){
		var c = $(this).text();
		c = c.toUpperCase();
		$('.city_word').addClass('city-word-result').removeClass('city_word');
		html ='<li class="current" style="cursor:pointer;"><span class="city">'+ c +'</span></li>';
		html += '<li style="cursor:pointer;" ><span class="city backword">A-Z</span></li>';
		$('#word_list').html( html );
		html = '';
		for( var i = 0 ; i< ports.length ; i++ ) {
			if( ports[i].short_py && ports[i].short_py.toUpperCase().indexOf( c ) === 0 ) {
				html +='<li style="cursor:pointer;" data-id="'+ ports[i].id +'" data-py="'+ ports[i].short_py +'" data-name="'+ ports[i].name +'"><span class="city" >'+ ports[i].name +'</span></li>';
			}
		}
		$('.ports-lists .city-list').html( html );

		$('.ports-lists').show();
	});

	$(document).on('click' , '.backword' , function(){
		var html = '' ;
		for( var i = 65 ; i < 91 ; i++ ) {
			if( i != 73 ) {
				html +='<li style="cursor:pointer;" ><span class="word">'+ String.fromCharCode( i ) +'</span></li>';
			}
		}
		$('#word_list').html( html );
		$('.ports-lists').hide();
	});
	
	$(window).resize(function(){
		if( $('.city-cont').hasClass('show') ) {
			$('body').css('overflow-y' , 'hidden').height( $(window).height() );
			$('.aui-content').height( $(window).height() );
			$('#list-content').hide();
			$('.city-cont').addClass('show').height( $(window).height() - 67);
		}
	});
})

$('#fromport , #toport').bind( bind_name, filter );


function filter( evt ){

	var word = $(this).val().toUpperCase();
	var f = word ;
	
	if( !word ) {
		return false ;
	}
	var html = "";
	var secondHtml = "";
	for( var i = 0 ; i< ports.length ; i++ ) {
		if( ports[i].short_py && ports[i].short_py.toUpperCase().indexOf( f ) === 0 || ports[i].name.toUpperCase().indexOf( word ) === 0 ) {
			html +='<li style="cursor:pointer;" data-id="'+ ports[i].id +'" data-py="'+ ports[i].short_py +'" data-name="'+ ports[i].name +'"><span class="city" >'+ ports[i].name +'</span></li>';
		}
		if( ports[i].short_py && ports[i].short_py.toUpperCase().indexOf( f ) > 0 || ports[i].name.toUpperCase().indexOf( word ) > 0 ) {
			secondHtml +='<li style="cursor:pointer;" data-id="'+ ports[i].id +'" data-py="'+ ports[i].short_py +'" data-name="'+ ports[i].name +'"><span class="city" >'+ ports[i].name +'</span></li>';
		}

	}
	$('.ports-lists .city-list').html( html + secondHtml );
	$('.hot-port').hide();
	$('.city-word').hide();
	$('.ports-lists').show();
}
var load = false ;
//搜索
$('.search-port').on('click' , function(){
	var fromport = $('#input_fromport').val();
	$('#input_fromport').attr('placeholder' , fromport ) ;
	var toport = $('#input_toport').val();
	$('#input_fromport').attr('placeholder' , toport ) ;
	var date = $('#date').val();
	if( !fromport ) {
		tip('请选择起运港');
		return false ;
	}
	if( !toport ) {
		tip('请选择目的港');
		return false ;
	}
	if( fromport == toport ) {
		tip('起运港不能和目的港相同');
		return false ;
	}
	if( !date ) {
		//tip('请选装货日期');
		//return false ;
	}
	load = true ;
    $.get( "{{route('wap.searchport')}}" , {'page' : 1 , 'fromport' : fromport , 'toport' : toport , 'date' : date } , function( data ){
        load = false ;
        $('#list-content').html( data );
    });
});

$('.load-more').click(function(){
    var that = $(this);
    var currentPage = $(this).attr('current-page') ;
    currentPage = parseInt( currentPage );
    var maxPage = $(this).attr('max-page') ;
    maxPage = parseInt( maxPage );
    if( false === load && currentPage < maxPage ) {
        load = true ;
        $.get( "{{route('wap.searchport')}}" , {'page' : currentPage+1 } , function( data ){
            load = false ;
            $(that).attr('current-page' , currentPage + 1 );
            $('ul.aui-list').append( data );
            if( maxPage == currentPage+1 ) {
                $(that).remove();
            }
        });
    }

});

$('#changeFromTo').on('click' , function(){
	var fromport = $('#input_fromport').val();
	var toport = $('#input_toport').val();
	$('#input_fromport').val(toport);
	$('#input_toport').val(fromport);
	var fromportName = $('#fromport').attr('placeholder');
	var toportName = $('#toport').attr('placeholder');
	$('#fromport').attr('placeholder' , toportName);
	$('#toport').attr('placeholder' , fromportName );
});

@if( $fromPort && $toPort )
	$('.search-port').trigger('click');
@endif


</script>

@endsection