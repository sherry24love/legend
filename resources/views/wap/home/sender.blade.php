@extends('wap.layout')

@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">填写发货人</div>
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
                <div class="aui-list-item-label gray aui-font-size-14">发货人名称</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写发货人名称" id="sender_name" name="sender_name" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">发货联系人</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写发货联系人" id="sender_contact" name="sender_contact" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">发货联系电话</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写发货联系电话" id="sender_mobile" name="sender_mobile" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">发货联系邮箱</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写发货联系邮箱" id="sender_email" name="sender_email" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">装货地址</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写装货地址" id="sender_address" name="sender_address" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">装货日期</div>
                <div class="aui-list-item-input" >
                    <input type="date" placeholder="请填写装货日期" id="sender_date" name="sender_date" value="" />
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
    <ul class="addresslist" id="sender-list">
    @foreach( $sender as $val )
        <li class="sender-item" 
        data-name="{{$val->name}}" 
        data-contact="{{ $val->contact_name }}" 
        data-mobile="{{$val->mobile}}" 
        data-email="{{$val->email}}" 
        data-address="{{$val->address}}" 

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
$('.sender-item').click(function(){
    $('#sender_name').val( $(this).data('name') );
    $('#sender_contact').val( $(this).data('contact') );
    $('#sender_mobile').val( $(this).data('mobile') );
    $('#sender_email').val( $(this).data('email') );
    $('#sender_address').val( $(this).data('address') );
    $( '#top' ).hide();
});

$('#next').click(function(){
    var name = $('#sender_name').val().trim();
    var contact = $('#sender_contact').val().trim();
    var mobile = $('#sender_mobile').val().trim();
    var email = $('#sender_email').val().trim();
    var address = $('#sender_address').val().trim();
    var date = $('#sender_date').val().trim();
    if( !name ) {
        toast.fail({
            'title':'请填写发货人名称'
        });
        return false ;
    }
    if( !contact ) {
        toast.fail({
            'title':'请填写发货联系人'
        });
        return false ;
    }
    if( !mobile ) {
        toast.fail({
            'title':'请填写发货人联系电话'
        });
        return false ;
    }
    /**
    if( !email ) {
        toast.fail({
            'title':'请填写发货人联系邮箱'
        });
        return false ;
    }
    **/
    if( !address ) {
        toast.fail({
            'title':'请填写发货地址'
        });
        return false ;
    }
    if( !date ) {
        toast.fail({
            'title':'请填写装货日期'
        });
        return false ;
    }
    $.ajax({
        url:"{{route('wap.checkin.sender')}}" ,
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