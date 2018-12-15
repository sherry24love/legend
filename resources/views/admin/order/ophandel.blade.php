<div class="box">
	<div class="box-header">
		<div class="pull-right">
@if( $order->state == 0 )
<div class="btn-group pull-right" style="margin-right: 10px">
    <a data-href="{{route('admin.order.deal' , ['id' => $id ] )}}" class="btn btn-sm btn-twitter order-deal">
        &nbsp;&nbsp;处理
    </a>
</div>
@endif

@if( in_array( $order->state , [0,1] ) ) 
<div class="btn-group pull-right" style="margin-right: 10px">
    <a data-href="{{route('admin.order.back' , ['id'=> $id ] )}}" class="btn btn-sm btn-twitter order-back">
        &nbsp;&nbsp;返单
    </a>
</div>
@endif

@if( in_array( $order->state , [ 1 , 2 ] ) )
<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{{route('admin.order.import' , ['id' => $id ] )}}" class="btn btn-sm btn-twitter order-import">
        &nbsp;&nbsp;重新导入
    </a>
</div>
@endif

@if( $order->state == 2 )
<div class="btn-group pull-right" style="margin-right: 10px">
    <a data-href="{{route('admin.order.send', ['id' => $id ] )}}" class="btn btn-sm btn-twitter order-ok">
        &nbsp;&nbsp;出货
    </a>
</div>
@endif

@if( $order->state == 3 )
<div class="btn-group pull-right" style="margin-right: 10px">
    <a data-href="{{route('admin.order.take' , ['id' => $id ] )}}" class="btn btn-sm btn-twitter order-take">
        &nbsp;&nbsp;收款
    </a>
</div>
@endif

@if( in_array( $order->state , [ 0 , 1 , 2 ] ) )
<div class="btn-group pull-right" style="margin-right: 10px">
    <a data-href="{{route('admin.order.delete' , ['id' => $id ] )}}" class="btn btn-sm btn-danger order-fail">
        &nbsp;&nbsp;作废
    </a>
</div>
@endif


<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{{route('order.copy' , ['id' => $id ])}}" class="btn btn-sm btn-twitter">
        &nbsp;&nbsp;复制新增
    </a>
</div>

@if( in_array( $order->state , [ 2 , 3 , 4 ] ) )
<div class="btn-group pull-right" style="margin-right: 10px">
    <a data-href="{{route('admin.order.sendconfirm' , ['id' => $id ])}}" class="btn btn-sm btn-success order-sendconfirm">
        &nbsp;&nbsp;发送确认
    </a>
</div>
@endif

@if( $order->is_finished  == 0)
<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="/admin/order/{{$id}}/edit" class="btn btn-sm btn-twitter">
        <i class="fa fa-edit"></i>&nbsp;&nbsp;编辑
    </a>
</div>


<div class="btn-group pull-right" style="margin-right: 10px">
	<a data-href="{{route('admin.order.finished' , ['id' => $id ] )}}" class='btn btn-xs btn-danger order-tracedone'>完成追踪</a>&nbsp;
</div>
@endif

<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{{$back}}" class="btn btn-sm btn-success">
        &nbsp;&nbsp;返回
    </a>
</div>


<div class="btn-group" style="margin-right: 10px">
    <a class="btn">订单ID编号：{{$id}}</a>
</div>

        </div>
	</div>
</div>