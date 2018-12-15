@foreach( $order->items() as $val )
    <li class="aui-list-item">
        <div class="list-state">
            <div class="shop-name">{{$val->fromport->name or ''}}-{{$val->toport->name or ''}}</div>
            <div class="gs-state txt-red">{{data_get( config('global.order_state') , $val->state)}}</div>
        </div>
        <div class="aui-media-list-item-inner">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-text">
                	<div class="aui-list-item-title">
                        订单编号：{{$val->order_sn ? $val->order_sn : '暂无'}}
                    </div>
                    <div class="aui-list-item-title">
                        运单号：{{$val->waybill ? $val->waybill : '暂无'}}
                    </div>
                </div>
                <div class="aui-list-item-text">
                        {{$val->company->name or '待确定船公司' }}&nbsp;/&nbsp;{{$val->ship->name or '待确定船名'}}&nbsp;/&nbsp;{{$val->voyage ? $val->voyage : '待确定航次'}}
                </div>
                
                <div class="aui-list-item-text aui-margin-t-5">
                    柜量/柜型：{{$val->goods->box_num}} * {{ data_get( config( 'global.box_type' ) , $val->goods->box_type , '' )}}
                </div>
                <div class="aui-list-item-text aui-margin-t-5">
                    运输协议：{{data_get( config('global.transport_protocol') , $val->transport_protocol)}}
                </div>
                <div class="aui-list-item-text aui-margin-t-5">
                    货主：{{data_get( $val , 'owner' )}}
                </div>
            </div>
        </div>
        <div class="aui-info f12" style="padding-top:0">
            <div class="aui-info-item aui-font-size-14">
                {{$val->created_at}}
            </div>
            <div class="btn-box">
                <div class="aui-btn aui-btn-danger aui-btn-outlined btn-detail" data-href="{{route('wap.member.orderdetail' , ['id' => $val->id ])}}">查看详情</div>
            </div>
        </div>
    </li>
@endforeach