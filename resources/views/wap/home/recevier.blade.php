@extends('wap.layout')

@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">填写收货人</div>
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
                <div class="aui-list-item-label gray aui-font-size-14">收货人名称</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写收货人名称" id="recevier_name" name="recevier_name" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">收货联系人</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写收货联系人" id="recevier_contact" name="recevier_contact" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">收货联系电话</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写收货联系电话" id="recevier_mobile" name="recevier_mobile" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">收货联系邮箱</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写收货联系邮箱" id="recevier_email" name="recevier_email" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">收货地址</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写收货地址" id="recevier_address" name="recevier_address" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">收货人证件号码</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="收货人为个人请填写" id="recevier_idno" name="recevier_idno" value="" />
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
    <ul class="addresslist" id="recevier-list">
    @foreach( $recevier as $val )
        <li class="recevier-item" 
        data-name="{{$val->name}}" 
        data-contact="{{ $val->contact_name }}" 
        data-mobile="{{$val->mobile}}" 
        data-email="{{$val->email}}" 
        data-address="{{$val->address}}" 
        data-idno="{{$val->id_no}}"

        >
            <div class="addresstxt">
                <div class="select">
                <h4>{{$val->name}}</h4>
                <p>{{ $val->contact_name }}</p>
                <p>{{$val->mobile}}</p>
                <p>{{$val->address}}</p>
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
$('.recevier-item').click(function(){
    $('#recevier_name').val( $(this).data('name') );
    $('#recevier_contact').val( $(this).data('contact') );
    $('#recevier_mobile').val( $(this).data('mobile') );
    $('#recevier_email').val( $(this).data('email') );
    $('#recevier_address').val( $(this).data('address') );
    $('#recevier_idno').val( $(this).data('idno') );
    $( '#top' ).hide();
});

$('#next').click(function(){
    var name = $('#recevier_name').val().trim();
    var contact = $('#recevier_contact').val().trim();
    var mobile = $('#recevier_mobile').val().trim();
    var email = $('#recevier_email').val().trim();
    var address = $('#recevier_address').val().trim();
    var idno = $('#recevier_idno').val().trim();
    if( !name ) {
        toast.fail({
            'title':'请填写收货人名称'
        });
        return false ;
    }
    if( !contact ) {
        toast.fail({
            'title':'请填写收货联系人'
        });
        return false ;
    }
    if( !mobile ) {
        toast.fail({
            'title':'请填写收货人联系电话'
        });
        return false ;
    }
    /**
    if( !email ) {
        toast.fail({
            'title':'请填写收货人联系邮箱'
        });
        return false ;
    }
    **/
    if( !address ) {
        toast.fail({
            'title':'请填写收货地址'
        });
        return false ;
    }
    /**
    if( !idno ) {
        toast.fail({
            'title':'请填写收货人证件号码'
        });
        return false ;
    }
    **/
    $.ajax({
        url:"{{route('wap.checkin.recevier')}}" ,
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