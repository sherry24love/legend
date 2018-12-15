@extends('wap.layout')

@section('content')
<div id="main" class="aui-margin-b-50">
	<ul class="aui-list aui-form-list">
	<form id="checkin-goods">
		{{csrf_field()}}
		<li class="aui-list-item">
            <div class="aui-list-item-inner aui-list-item-arrow" id="shipmentBtn">
                <div class="aui-list-item-label gray aui-font-size-14">起运港</div>
                <div class="aui-list-item-input aui-padded-r-15" >
                	<span class="aui-pull-right aui-padded-r-15">{{$from->name or '请选择'}}</span>
                	<input type="hidden" value="{{$from->id or 0}}" id="shipment" name="shipment" >
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner aui-list-item-arrow" id="destinationportBtn">
                <div class="aui-list-item-label gray aui-font-size-14">目的港</div>
                <div class="aui-list-item-input aui-padded-r-15" >
                	<span class="aui-pull-right aui-padded-r-15">{{$to->name or '请选择'}}</span>
                	<input type="hidden" value="{{$to->id or 0}}" id="destinationport" name="destinationport" >
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">船公司名称</div>
                <div class="aui-list-item-input" >
                    <select name="company_id" id="company_id">
                        <option value="0" >请选择</option>
                        @foreach( $company as $k=> $val ) 
                            <option value="{{$k}}" 
                            @if( request()->input('company_id') == $k ) selected @endif  
                            >{{$val}}</option>
                        @endforeach 
                    </select>
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">船名</div>
                <div class="aui-list-item-input" >
                    <select name="ship_id" id="ship_id">
                        <option value="0" >待确认</option>
                        @foreach( $ship as $k=> $val ) 
                            <option value="{{$k}}" 
                            @if( request()->input('ship_id') == $k ) selected @endif 
                            >{{$val}}</option>
                        @endforeach 
                    </select>
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">航次</div>
                <div class="aui-list-item-input" >
                    <select name="flight_id" id="flight_id">
                        <option value="0" >待确认</option>
                        @foreach( $flight as $k=> $val ) 
                            <option value="{{$k}}" 
                            @if( request()->input('flight_id') == $k ) selected @endif  
                            >{{$val}}</option>
                        @endforeach 
                    </select>
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">运输协议</div>
                <div class="aui-list-item-input" >
                	<select name="transport_protocol" id="transport_protocol">
                	@foreach( config('global.transport_protocol') as $k => $val )
                        <option value="{{$k}}" 
                       
                        @if( request()->input('box_type') ) 
                            @if( request()->input('box_type') == $k ) selected @endif 
                        @else
                             @if( $k == 4 ) selected @endif 
                        @endif
                        >{{$val}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14" >货物类别</div>
                <div class="aui-list-item-input">
                <select name="goods_kind" id="goods_kind" >
            	@foreach( config('global.goods_kind') as $k => $val )
                    <option value="{{$k}}">{{$val}}</option>
                @endforeach
                </select>
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">货物名称</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请填写货物名称" id="goods_name" name="goods_name" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14" >箱型</div>
                <div class="aui-list-item-input">
                <select id="goods_box_type" name="goods_box_type">
            	@foreach( config('global.box_type') as $k => $val )
                    <option value="{{$k}}">{{$val}}</option>
                @endforeach
                </select>
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">箱量</div>
                <div class="aui-list-item-input" >
                	<input type="number" placeholder="请填写货箱量" id="goods_box_num" name="goods_box_num" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">单柜毛重（吨）</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请填写单柜毛重" id="goods_weight" name="goods_weight" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">包装类型</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请填写货物包装类型" id="goods_package_type" name="goods_package_type" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">总件数</div>
                <div class="aui-list-item-input" >
                	<input type="number" placeholder="请填写件数（选填）" id="goods_total_num" name="goods_total_num" value="" />
                </div>
            </div>
        </li>
        
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">总体积（m³）</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请填写货物总体积（选填）" id="goods_cubage" name="goods_cubage" value="" />
                </div>
            </div>
        </li>
        
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">备注</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写备注（选填）" id="remark" name="remark" value="" />
                </div>
            </div>
        </li>
        <!--
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">海运费</div>
                <div class="aui-list-item-input" >
                    <input type="text" readonly id="ship_cost_dispaly" value="待定" />
                    <input type="hidden" readonly id="ship_cost" name="ship_cost" value="0" />
                </div>
            </div>
        </li>
        -->
        </form>
    </ul>
    <div class="aui-padded-b-10 aui-padded-t-10 aui-padded-l-10 aui-padded-r-10">
    	<a class="aui-btn aui-btn-warning aui-btn-block" id="next">下一步</a>
    </div>
</div>
<div class="openwin-layer" id="top" style="display:none;background: #fff;min-height:100%;padding-bottom:45px;">
	<div class="aui-content aui-margin-b-15">
		<div class="aui-searchbar" style="position: fixed;top:2rem;width:100%;left:0;background:#fff;z-index:999;margin:0;padding:.25rem 0;">
		    <div class="aui-searchbar-input">
		        <i class="aui-iconfont aui-icon-search"></i>
		            <input type="text" placeholder="请输入中文或英文字母" id="search-input" >
		    </div>
		</div>
    	<ul class="aui-list aui-list-in" id="ports-list" style="margin-top:55px;">
    @foreach( $ports as $k => $v )
    	<li class="aui-list-header alpha">
            {{ $k }}
        </li>
    	@foreach( $v as $val )
        <li class="aui-list-item aui-list-item-middle" data-id="{{ data_get( $val , 'id' ) }}" data-name="{{ data_get( $val , 'name' ) }}" data-py="{{ data_get( $val , 'short_py') }}">
            <div class="aui-list-item-inner aui-list-item-arrow">
                <div class="aui-list-item-title">{{ data_get( $val , 'name' ) }}</div>
        </li>
        @endforeach
    @endforeach
     	</ul>
     </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
var ports = {!! json_encode( $ports ) !!};
var old = $('#ports-list').html();
function tip( msg ) {
	var toast = new auiToast();
	toast.fail({
		'title':msg
	});
}
var type = '' ;
$('#shipmentBtn').click(function(){
	type = 'shipmentBtn' ;
	$('#search-input').val('');
	filter();
    $( '#top' ).toggle();
});

$('#destinationportBtn').click(function(){
	type = 'destinationportBtn' ;
	$('#search-input').val('');
	filter();
    $( '#top' ).toggle();
});


var bind_name = 'input';
if (navigator.userAgent.indexOf("MSIE") != -1){
　　 bind_name = 'propertychange';
}

$('#search-input').bind( bind_name, filter );


function filter(){
	var word = $('#search-input').val().toUpperCase();
	var f = word.substring( 0 , 1 );
	$('#ports-list').html( old );
	$('#ports-list li').show();
    $('#ports-list li').not('.alpha').click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        $( '#' + type ).find('span').html( name );
        $( '#' + type ).find('input').val( id );
        $( '#top' ).hide();
    });
	if( !word ) {
		return false ;
	}
    var html = "";
    if( typeof( ports[ word ] ) != 'undefined' )  {
        //找到一组可以直接用来显示的字母
        for( var i in ports[word ] ) {
            html += '<li class="aui-list-item aui-list-item-middle" data-id="' + ports[ word ][i].id ;
            html += '" data-name="' + ports[ word ][i].name + '" data-py="' + ports[ word ][i].short_py + '">';
            html += '<div class="aui-list-item-inner aui-list-item-arrow">';
            html += '<div class="aui-list-item-title">' + ports[ word ][i].name + '</div>';
            html += '</li>';
        }
        //再检查其他的里面是不是有包含这个的
        for( var i in ports ) {
            if( i != word ) {
                for( var j in ports[i] ) {
                    if( ( ports[i][j].short_py && ports[i][j].short_py.toUpperCase().indexOf( word ) >= 0 ) || ports[i][j].name.toUpperCase().indexOf( word ) >= 0 ) {
                        html += '<li class="aui-list-item aui-list-item-middle" data-id="' + ports[ i ][j].id ;
                        html += '" data-name="' + ports[ i ][j].name + '" data-py="' + ports[ i ][j].short_py + '">';
                        html += '<div class="aui-list-item-inner aui-list-item-arrow">';
                        html += '<div class="aui-list-item-title">' + ports[ i ][j].name + '</div>';
                        html += '</li>';
                    }
                }
            }
        }
    } else {
        //找不到一组可以直接用来显示的字母
        for( var i in ports ) {
            for( var j in ports[i] ) {
                if( ( ports[i][j].short_py && ports[i][j].short_py.toUpperCase().indexOf( word ) >= 0 ) || ports[i][j].name.toUpperCase().indexOf( word ) >= 0 ) {
                    html += '<li class="aui-list-item aui-list-item-middle" data-id="' + ports[ i ][j].id ;
                    html += '" data-name="' + ports[ i ][j].name + '" data-py="' + ports[ i ][j].short_py + '">';
                    html += '<div class="aui-list-item-inner aui-list-item-arrow">';
                    html += '<div class="aui-list-item-title">' + ports[ i ][j].name + '</div>';
                    html += '</li>';
                }
            }
        }

    }
    $('#ports-list').html( html );
    $('#ports-list li').not('.alpha').click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        $( '#' + type ).find('span').html( name );
        $( '#' + type ).find('input').val( id );
        $( '#top' ).hide();
    });
    /**
	$('#ports-list li').each( function(){
		var li = $(this);
		if( $(li).hasClass( 'alpha' ) ) {
			$(li).hide();
		} else {
			if( $(li).data('py').toUpperCase().indexOf( word ) >= 0 || $(li).data('name').toUpperCase().indexOf( word ) >= 0 ) {
				$(li).show();
			} else {
				$(li).hide();
			}
		}
		
	});
    **/
}

$('#next').click(function(){
		var shipment = $('#shipment').val();
		var destinationport = $('#destinationport').val();
		if( !shipment ) {
			tip("请选择出发地港口");
			return false ;
		}
		if( !destinationport ) {
			tip("请选择出目的地港口");
			return false ;
		}
		if( shipment == destinationport ) {
			tip('出发港口不能和目的地港口一致') ;
			return false ;
		}
		var type = $('#goods_kind').val() ;
		if( type == 0 ) {
			tip('请选择货物类型') ;
			return false ;
		}
		var goods_name = $('#goods_name').val().trim() ;
		if( goods_name =='' ) {
			tip('请填写货物名称') ;
			return false ;
		}
		var box_num = $('#goods_box_num').val().trim() ;
		if( box_num == '' ) {
			tip('请填写货物箱数') ;
			return false ;
		}
		var total_num = $('#goods_total_num').val().trim();
		if(  total_num == '' ) {
			//tip('请填写货物总件数') ;
			//return false ;
		}
		var cubage = $('#goods_cubage').val().trim();
		if( '' == cubage ) {
			//tip('请填写货物总体积') ;
			//return false ;
		}
		var package_type = $('#goods_package_type').val().trim();
		if( package_type == '' ) {
			tip('请填写货物包装类型') ;
			return false ;
		}
		$.ajax({
			url:"{{route('wap.checkin')}}" ,
			type:'post' ,
			dataType:'json' ,
			data: $('#checkin-goods').serialize(),
			success:function( data ){
				if( data.errcode === 0 ) {
					location.href = data.url ;
				} else {
					tip( data.msg );
				}
			}

		});

});	

$('#company_id').on('change' , function(){
    var id = $(this).val();
    if( id ) {
        $.get( "{{route('getship')}}" , {id:id} , function( data){
            console.log( data );
            if( data.errcode === 0 ) {
                var html = '<option value="0" >待确认</option>' ;
                for( var i in data.data ) {
                    html += '<option value="' + i + '" >'+ data.data[i] +'</option>' ;
                }
                $('#ship_id').html( html );
            } else {
                $('#ship_id').html("<option value='0'>待确认</option>");
            }
        } , 'json') ;
    } else {
        $('#ship_id').html("<option value='0'>待确认</option>");
    }
    //请空航次信息
    $('#flight_id').html("<option value='0'>待确认</option>");
});

//当船信息更改时  修改航次信息
$('#ship_id').on('change' , function(){
    var id = $(this).val();
    if( id ) {
        var fromPort = $('#shipment').val();
        var toPort = $('#destinationport').val();
        $.get( "{{route('getflight')}}" , {id:id , 'from' : fromPort , 'to' : toPort } , function( data){
            console.log( data );
            if( data.errcode === 0 ) {
                var html = '<option value="0" >待确认</option>' ;
                for( var i in data.data ) {
                    html += '<option value="' + i + '" >'+ data.data[i] +'</option>' ;
                }
                $('#flight_id').html( html );
            } else {
                $('#flight_id').html("<option value='0'>待确认</option>");
            }
        } , 'json') ;
    } else {
        //清空航次信息
        $('#flight_id').html("<option value='0'>待确认</option>");
    }
});
//每隔500MS 查询一次价格
setInterval( function(){
    var fromPort = $('#shipment').val();
    var toPort = $('#destinationport').val();
    var company_id = $('#company_id').val();
    var ship_id = $('#ship_id').val();
    var flight_id = $('#flight_id').val();
    var box_type = $('.box_type').val();
    var box_num = $('.box_num').val();
    if( !fromPort ) {
        $("#ship_cost_dispaly").html('待定');
        $("#ship_cost").val( 0 );
        return false ;
    }
    if( !toPort ) {
        $("#ship_cost_dispaly").html('待定');
        $("#ship_cost").val( 0 );
        return false ;
    }
    if( !company_id ) {
        $("#ship_cost_dispaly").html('待定');
        $("#ship_cost").val( 0 );
        return false ;
    }
    if( !ship_id ) {
        $("#ship_cost_dispaly").html('待定');
        $("#ship_cost").val( 0 );
        return false ;
    }
    if( !flight_id ) {
        $("#ship_cost_dispaly").html('待定');
        $("#ship_cost").val( 0 );
        return false ;
    }
    if( !box_type ) {
        $("#ship_cost_dispaly").html('待定');
        $("#ship_cost").val( 0 );
        return false ;
    }
    if( !box_num ) {
        $("#ship_cost_dispaly").html('待定');
        $("#ship_cost").val( 0 );
        return false ;
    }
    $.get("{{route('checkprice')}}" , {
        'from':fromPort , 
        'to':toPort , 
        'company_id' : company_id ,
        'ship_id' : ship_id ,
        'flight_id': flight_id ,
        'box_type' : box_type ,
        'box_num' : box_num 
    } , function( data ){
        if( data.errcode === 0 ) {
            if( data.data > 0 ) {
                $("#ship_cost_dispaly").html( data.data );
                $("#ship_cost").val( data.data );
            } else {
                $("#ship_cost_dispaly").html('待定');
                $("#ship_cost").val( 0 );
            }

        } else {
            $("#ship_cost_dispaly").html('待定');
            $("#ship_cost").val( 0 );
        }
    } , 'json');
} , 500 );
</script>



@endsection