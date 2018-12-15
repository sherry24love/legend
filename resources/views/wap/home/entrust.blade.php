@extends('wap.layout')

@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">填写委托人</div>
    <a class="aui-pull-right aui-btn" id="select-template">
        <i class="aui-iconfont">选择模板</i>
    </a>
</header>

@endsection


@section('content')
<div id="main">
    <ul class="aui-list aui-form-list">
    <form id="checkin-goods">
        {{csrf_field()}}
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">委托人名称</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写委托人名称" id="entrust_name" name="entrust_name" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">委托联系人</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写委托联系人" id="entrust_contact" name="entrust_contact" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">委托人电话</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写委托人电话" id="entrust_mobile" name="entrust_mobile" value="" />
                </div>
            </div>
        </li>
    </form>
    </ul>
    <div class="aui-padded-b-10 aui-padded-t-10 aui-padded-l-10 aui-padded-r-10">
        <a class="aui-btn aui-btn-warning aui-btn-block" id="next">下一步</a>
    </div>
</div>
<div class="openwin-layer" id="top" style="display:none;">
    <ul class="addresslist" id="entrust-list">
    @foreach( $entrust as $val )
        <li class="entrust-item" data-name="{{$val->name}}" data-contact="{{ $val->contact }}" data-mobile="{{$val->mobile}}">
            <div class="addresstxt">
                <div class="select">
                <h4>{{$val->name}}</h4>
                <p>{{ $val->contact }}</p>
                <p>{{$val->mobile}}</p>
                </div>
            </div>
        </li>
    @endforeach
     </ul>
</div>
@endsection

@section('footer')
@endsection


@section('script')
<script type="text/javascript" src="{{asset('mobile/js/aui-popup.js')}}"></script>
<script>
var popup = new auiPopup();
var toast = new auiToast({});

$('#select-template').click(function(){
    $( '#top' ).toggle();
});
$('.entrust-item').click(function(){
    $('#entrust_name').val( $(this).data('name') );
    $('#entrust_contact').val( $(this).data('contact') );
    $('#entrust_mobile').val( $(this).data('mobile') );
    $( '#top' ).hide();
});

$('#next').click(function(){
    var name = $('#entrust_name').val().trim();
    var contact = $('#entrust_contact').val().trim();
    var mobile = $('#entrust_mobile').val().trim();
    if( !name ) {
        toast.fail({
            'title':'请填写委托人名称'
        });
        return false ;
    }
    if( !contact ) {
        toast.fail({
            'title':'请填写委托联系人'
        });
        return false ;
    }
    if( !mobile ) {
        toast.fail({
            'title':'请填写委托人联系电话'
        });
        return false ;
    }
    $.ajax({
        url:"{{route('wap.checkin.entrust')}}" ,
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

</script>
@endsection