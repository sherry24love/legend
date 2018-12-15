@foreach( $list->items() as $val )
<li class="aui-list-item">
    <div class="aui-media-list-item-inner">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-text">
                <div class="aui-list-item-title">
                    订单编号：{{$val->order->order_sn  ? substr_replace( $val->order->order_sn , '****' , 4 , 4 ) : '' }}
                </div>
                <div class="aui-list-item-right aui-font-size-14">{{$val->order->goods->box_num}}柜</div>
            </div>
            <div class="aui-list-item-text">
                运单号：{{$val->order->waybill  ? substr_replace( $val->order->waybill , '****' , 4 , 4 ) : '' }}
            </div>  
            <div class="aui-list-item-text">
                奖励：{{$val->cash}}
            </div>  
            <div class="aui-list-item-text">
                预计到账：{{$val->expect}}
            </div>            
        </div>
    </div>
    <div class="aui-info f12" style="padding-top:0">
        <div class="aui-info-item aui-font-size-14">
            {{str_limit( $val->created_at , 10 , '' )}}
        </div>
        <div class="btn-box">
            <div class="aui-text-danger" >
                {{$val->status == 1 ? '已确认' : '待确认'}}
            </div>
        </div>
    </div>
</li>
@endforeach