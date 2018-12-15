@extends('wap.layout')

@section('header')
<header class="aui-bar aui-bar-nav bg-red">
    <a class="aui-pull-left aui-btn" href="javascript:history.back()">
        <span class="aui-iconfont aui-icon-left"></span>
    </a>
    <div class="aui-title">填写保险信息</div>
</header>

@endsection

@section('content')
<div id="main">
    <ul class="aui-list aui-form-list">
    <form id="checkin-goods">
        {{csrf_field()}}
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">是否需要保险</div>
                <div class="aui-list-item-input" >
                    <input type="checkbox" class="aui-checkbox" id="enable_ensure" name="enable_ensure" value="1" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">被保险人名称</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写被保险人名称" id="ensure_name" name="ensure_name" value="" />
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">保险金额</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请填写保险金额" id="ensure_goods_worth" name="ensure_goods_worth" value="" />
                </div>
            </div>
        </li>
        <input type="hidden" name="is_default" id="is_default" value="0" />
    </form>
    </ul>

    <div class="aui-content-padded">
    <p>
     如不购买保险，将以订舱时船公司确认的最低保险为准。
    </p>
    <p>
    保险说明：
    </p>
    <p>
   
    限制承保货物：石材、现金等有价票券、金银玉器、艺术品等、二手货物、动植物、易燃、易爆、易腐及易变质物品。<br/>
    保险金额：数值区间1万-5千万<br/>
    短量免赔：散装货物每次事故短量免赔为保险金额的0.3%；<br>
    破碎免赔：每次事故绝对免赔额为人民币5000元/箱或保险金额的10%，两者取高；<br>
    湿损免赔：每次事故绝对免赔额为人民币5000元/箱或保险金额的10%，两者取高；<br>
    其他货损免赔：其他货损每次事故绝对免赔额为人民币800元/箱；本免赔的计算单位为单个集装箱。
    </p>
    </div>
    <div class="aui-padded-b-10 aui-padded-t-10 aui-padded-l-10 aui-padded-r-10">
        <a class="aui-btn aui-btn-warning aui-btn-block" id="next">提交订单</a>
    </div>
</div>
@endsection

@section('footer')
@endsection


@section('script')
<script type="text/javascript" src="{{asset('mobile/js/aui-popup.js')}}"></script>
<script>
var popup = new auiPopup();
var toast = new auiToast({});
var d = new auiDialog();

$('#next').click(function(){
    var enable_ensure = $('#enable_ensure').prop('checked') ? 1 : 0 ;
    var ensure_name = $('#ensure_name').val().trim();
    var ensure_goods_worth = $('#ensure_goods_worth').val().trim();
    
    if( enable_ensure ) {
        if( !ensure_name ) {
            toast.fail({
                'title':'请填写被保险人名称'
            });
            return false ;
        }
        if( !ensure_goods_worth ) {
            toast.fail({
                'title':'请填写保险金额'
            });
            return false ;
        }
    }
    d.alert({
            title:"弹出提示",
            msg:'是否要将本次订单信息设置为模板',
            buttons:['否','是']
    },function(ret){
        if( ret.buttonIndex == 2 ) {
            $('#is_default').val( 1 );
        } else {
            $('#is_default').val( 0 );
        }
        $.ajax({
            url:"{{route('wap.checkin.submit')}}" ,
            type:'post' ,
            dataType:'json' ,
            data: $('#checkin-goods').serialize(),
            success:function( data ){
                if( data.errcode === 0 ) {
                    location.href = "{{route('wap.member.order')}}" ;
                } else {
                    toast.fail({ title:data.msg } );
                }
            }

        });

    });

});

</script>
@endsection