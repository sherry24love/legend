@extends('wap.layout')

@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">银行卡管理</div>
    <a class="aui-pull-right aui-btn" href="{{route('wap.bank.create')}}">
        <i class="aui-iconfont aui-icon-plus"></i>
    </a>
</header>

@endsection


@section('content')
<div id="main">
    <ul class="addresslist" id="address_list">
    @foreach( $bank->items() as $val )
        <li class="noarrow">
            <div class="addresstxt">
                <div class="select">
                <h4>{{$val->name}}</h4>
                <p>{{data_get( config('global.bank') , $val->bank_id ) }}</p>
                <p>{{$val->card_no}}</p>
                </div>
                <a class="editbtn" href="{{route('wap.bank.edit' , ['id'=> $val->id ] )}}" style="height:50%;"></a>
                <a class="newdelbtn" data-href="{{route('wap.bank.delete' , ['id'=> $val->id ] )}}" style="height:50%;"></a>
            </div>
        </li>
    @endforeach
     </ul>
</div>
@endsection

@section('footer')
@endsection


@section('script')
<script>
var toast = new auiToast({});
$('.newdelbtn').on('click' , function(){
    var url = $(this).data('href');
    if( confirm("您确定要删除这张银行卡信息吗？") ) {
        $.getJSON( url  , {} , function( data ){
            if( data.errcode === 0 ) {
                toast.success({
                    'title': data.msg
                });
                setTimeout( function(){
                    location.reload();
                } , 1500 ) ;
            }
            toast.fail({
                'title':data.msg
            }) ;
        });
    }
});
</script>
@endsection