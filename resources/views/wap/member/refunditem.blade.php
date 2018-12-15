@foreach( $list->items() as $val )
<li class="aui-list-item">
    <div class="aui-media-list-item-inner">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-text">
                <div class="aui-list-item-title">
                    订单编号：{{$val->order->order_sn  ?  $val->order->order_sn  : '' }}
                </div>
            </div>
            <div class="aui-list-item-text">
                运单号：{{$val->order->waybill  ? $val->order->waybill : '' }}
            </div>
            <div class="aui-list-item-text">
                返利：{{$val->cash}}
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