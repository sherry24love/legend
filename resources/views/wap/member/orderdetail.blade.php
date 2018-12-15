@extends('wap.layout')

@section('content')

    <div class="addresslist noarrow" style="padding: .25rem">
        <ul>
            <li class="noborder">
                <div class="address-info">
                    <h4>委托人：{{$entrust->name or ''}}  {{$entrust->mobile or ''}}</h4>
                    <p>委托联系人：{{$entrust->contact or ''}}</p>
                </div>
            </li>
        </ul>
    </div>
    <div class="addresslist noarrow" style="padding: .25rem">
        <ul>
            <li class="noborder">
                <div class="address-info">
                    <h4>发货人：{{$sender->name or ''}}  {{$sender->mobile or ''}}</h4>
                    <p>发货联系人：{{$sender->contact_name or ''}}</p>
                    <p>发货联系邮箱：{{$sender->email or ''}}</p>
                    <p>发货地址：{{$sender->address or ''}}</p>
                    <p>装货日期：{{ str_limit( $sender->load_date , 10 , '' )}}</p>
                </div>
            </li>
        </ul>
    </div>
    <div class="addresslist noarrow" style="padding: .25rem">
        <ul>
            <li class="noborder">
                <div class="address-info">
                    <h4>收货人：{{$recevier->name or ''}}  {{$recevier->mobile or ''}}</h4>
                    <p>收货联系人：{{$recevier->contact_name or ''}}</p>
                    <p>收货联系邮箱：{{$recevier->email or ''}}</p>
                    <p>收货人证件号码：{{$recevier->id_no or ''}}</p>
                    <p>收货地址：{{$recevier->address or ''}}</p>
                </div>
            </li>
        </ul>
    </div>
    <div class="dm_list bg-white">
        <ul>
            <li>
                <div class="listinfo">
                    <div class="goodsname">
                    {{$order->goods->name or ''}}&nbsp;/&nbsp;
                    {{$order->goods->box_num or 0 }} * {{data_get( config('global.box_type' ) , $order->goods->box_type )}}&nbsp;/&nbsp;
                    @if($order->goods->weight)
                    {{$order->goods->weight}}
                    @else
                    0
                    @endif
                    (t)
                    &nbsp;/&nbsp;
                    @if($order->goods->cubage)
                    {{$order->goods->cubage}}
                    @else
                    0
                    @endif
                    (m<sup>3</sup>)
                    @if( $order->goods->package) 
                    &nbsp;/&nbsp;
                    {{$order->goods->package}}
                    @endif
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="order-det-info">
    	<div class="info-info">
            <span>订单编号</span>
            <span>{{$order->order_sn}}</span>
        </div>
        <div class="info-info">
            <span>运单号</span>
            <span>{{$order->waybill}}</span>
        </div>
        <div class="info-info">
            <span>运输条款</span>
            <span >{{data_get( config('global.transport_protocol') , $order->transport_protocol )}}</span>
        </div>  
        <div class="info-info">
            <span>货主</span>
            <span >{{data_get( $order , 'owner' )}}</span>
        </div>
        <div class="info-info">
            <span>起止港口</span>
            <span> 
            @if( $order->barge_port ) 
            {{$order->fromport->name or ''}}&nbsp;->&nbsp; 
            {{$order->bargeport->name or ''}}&nbsp;->&nbsp; 
            @else 
            {{$order->fromport->name or ''}}&nbsp;->&nbsp; 
            @endif 
            @if( $order->barge_to_port )
			{{$order->bargetoport->name or ''}}&nbsp;->&nbsp;
			{{$order->toport->name or ''}}
			@else 
			{{$order->toport->name or ''}}
			@endif 
			</span>
        </div>
        <div class="info-info">
            <span>拖车费</span>
            <span>{{$order->trailer_cost or ''}}</span>
        </div>
        <div class="info-info">
            <span>海运费</span>
            <span>{{$order->ship_cost or ''}}</span>
        </div>
        <div class="info-info">
            <span>其他费用</span>
            <span>{{$order->other_cost or '' }}</span>
        </div>
        <div class="info-info">
            <span>费用说明</span>
            <span>{{$order->costinfo ? $order->costinfo : '' }}</span>
        </div>
        <div class="info-info">
            <span>保险信息</span>
            <span>{{$order->enable_ensure  == 1  ? '需要保险' : '不需要保险' }}</span>
        </div>
        @if( $order->enable_ensure == 1 )
        <div class="info-info">
            <span>保险人</span>
            <span>{{$order->ensure_name or ''}}</span>
        </div>
        <div class="info-info">
            <span>保险额度</span>
            <span>{{ $order->insure_goods_worth or '' }}</span>
        </div>
        @endif
        <div class="info-info">
            <span>下单时间</span>
            <span>{{$order->created_at}}</span>
        </div>
        @if( $order->barge_port )
        <div class="info-info">
            <span>预计驳船离港时间</span>
            <span>{{$order->barge_plan_time}}</span>
        </div>
        <div class="info-info">
            <span>实际驳船离港时间</span>
            <span>{{$order->barge_time}}</span>
        </div>
        @endif
        <div class="info-info">
            <span>预计大船离港时间</span>
            <span>
            @if( $order->start_time != '0000-00-00 00:00:00')
            {{str_limit( $order->start_time , 16 , '' )}}
            @endif
            </span>
        </div> 
        <div class="info-info">
            <span>预计大船到港时间</span>
            <span>
            @if( $order->end_time != '0000-00-00 00:00:00')
                {{str_limit( $order->end_time , 16 , '' )}}
            @endif
            </span>
        </div> 
        @if( $order->barge_to_port )
        <div class="info-info">
            <span>预计驳船到港时间</span>
            <span>{{$order->barge_to_plan_time}}</span>
        </div>
        <div class="info-info">
            <span>实际驳船到港时间</span>
            <span>{{$order->barge_to_time}}</span>
        </div>
        <div class="info-info">
            <span>目的港驳船船名/航次</span>
            <span>{{$order->barge_to_flight}}</span>
        </div>
        @endif
        <div class="info-info">
            <span>备注</span>
            <span>{{$order->remark}}</span>
        </div>
        <div class="info-info">
            <span>派送</span>
            <span>{{$order->barge_to_remark}}</span>
        </div>
        @if( $order->rebate > 0 )
        <div class="info-info">
            <span>返利</span>
            <span>
                {{$order->rebate}}
            </span>
        </div> 
        @endif
@if( $order->state > 0 && $order->state != 9 && $order->waybill )
<div class="aui-padded-15" style="display: flex;">
    <a class="aui-btn aui-btn-danger aui-btn-block aui-margin-r-5" href="{{route('wap.track' , ['waybill' => $order->waybill ])}}">货物追踪</a>
</div>
@endif
    </div>

@endsection

@section('footer')
@endsection


@section('script')
<script>
var load = false ;
$('.load-more').click(function(){
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

$(document).on('tap' , '.btn-detail' , function(){
    var url = $(this).data('href');
    if( url ) {
        location.href = url ;
    }
});
</script>
@endsection