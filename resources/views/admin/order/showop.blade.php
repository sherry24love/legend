@if( $order->order_status == 0 )
<div class="btn-group">
    <button class="btn btn-warning btn-check" data-href="{{route('admin.order.checkok' , ['id' => $order->id ] )}}">审核</button>
</div>
@endif



@if( $order->order_status == 1 )
<div class="btn-group">
    <button class="btn btn-warning btn-offpay" data-href="{{route('admin.order.payoffline' , ['id' => $order->id ] )}}">线下支付</button>
</div>

<div class="btn-group">
    <button class="btn btn-warning btn-recivepay" data-href="{{route('admin.order.payrecive' , ['id' => $order->id ] )}}">货到付款</button>
</div>
@endif

@if( $order->order_status == 2 )
<div class="btn-group">
    <button class="btn btn-warning btn-send" data-href="{{route('admin.order.send' , ['id' => $order->id ] )}}">发货</button>
</div>
@endif