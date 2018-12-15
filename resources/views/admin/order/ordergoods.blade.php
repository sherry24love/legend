<style>
ul.has-many-prescription-forms {
	padding-left:0px;
}

ul.has-many-prescription-forms:after {
	display:block;
	height:1px;
	line-height:1px;
	clear:both;
}

.has-many-prescription-forms span.item {
	padding:5px;
	line-height:25px;
	margin-left:5px;
	background:#f3f2f2;
	
}
.select2-dropdown {
	z-index:30000000;
}
</style>
<hr style="margin-top: 0px;">

<div id="has-many-prescription" class="has-many-prescription">
	<input type="hidden" id="prescription" name="prescription"  >
    <ul class="has-many-prescription-forms">

        
@foreach( $prescription as $val )
			<span class="item" data-id="{{data_get( $val , 'id')}}" data-num="{{data_get( $val , 'num')}}">
    			{{data_get( $val , 'goods_name')}}
    			&nbsp;&nbsp;&nbsp;&nbsp;
    			{{data_get( $val , 'num' )}}{{data_get( $val , 'measure_unit')}}
    			<i class="fa fa-trash p-del"></i>
    		</span>
@endforeach	
    </ul>
<hr style="margin-top: 0px;">

    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-8">
            <div class="add btn btn-success btn-sm p-new"><i class="fa fa-save"></i>&nbsp;{{ trans('admin::lang.new') }}</div>
        </div>
    </div>

</div>

<template class="prescription-tpl">
<form class="form-horizontal">
<p class="text-info">如果选择已经存在的药片则已新的药片数量覆盖原有的药片信息</p>
<div class="form-group">
	<label class="col-sm-4 control-label">饮片名称</label>
	<div class="col-sm-6">
            <select class="form-control prescription-select">
            @foreach( $prescription_products as $k => $val )
            	<option value="{{$k}}" >{{$val}}</option>
            @endforeach
            </select>
    </div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">饮片重量（克）</label>
	<div class="col-sm-6">
    	<input type="text" class="form-control" />
    </div>
</div>
</form>
</template>