@extends('layouts.layout')
@section('style')
<style>
body {
	background:#f2f2f2;
}
.main {
	width:1150px;
    margin:5px auto;
	background:#fff;
	padding:10px;
}
	
.bs-callout {
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #eee;
  border-left-width: 5px;
  border-radius: 3px;
}
.bs-callout h4 {
  margin-top: 0;
  margin-bottom: 5px;
}
.bs-callout p:last-child {
  margin-bottom: 0;
}
.bs-callout code {
  border-radius: 3px;
}

/* Tighten up space between multiple callouts */
.bs-callout + .bs-callout {
  margin-top: -5px;
}

/* Variations */
.bs-callout-danger {
  border-left-color: #ce4844;
}
.bs-callout-danger h4 {
  color: #ce4844;
}
.bs-callout-warning {
  border-left-color: #aa6708;
}
.bs-callout-warning h4 {
  color: #aa6708;
}
.bs-callout-info {
  border-left-color: #1b809e;
}
.bs-callout-info h4 {
  color: #1b809e;
}
li.address-li {
	margin-top:10px;
}

</style>

@endsection


@section('content')
<div class="main wtxq">
    <form id="checkin" method="post" class="form-horizontal" action="">
    	{{csrf_field()}}
		<div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
			<h4 class="cdiv" cdiv="div03"><a href="javascript:void(0);" class="pull-right soh">收起</a>货物信息</h4>
		</div>
		<div class="div03">

			<div class="form-group">
				<label for="exampleInputName2" class="col-sm-1 control-label"><span class="key">*</span>起运港</label>
                <div class="col-sm-3">
                    <select class="startPort form-control" name="shipment" id="shipment">
                        <option value="">起运港</option>
    						@foreach( $ports as $k=> $val ) 
							<option value="{{data_get( $val , 'id') }}" alt="{{data_get( $val , 'short_py') }}"
							@if( request()->input('shipment') == data_get( $val , 'id') ) selected @endif 
							@if( data_get( $order , 'shipment') == data_get( $val , 'id') ) selected @endif 

							>{{data_get( $val , 'name') }}</option>
						@endforeach	
                    </select>
                </div>
            </div>
			<div class="form-group">
				<label for="exampleInputName2" class="col-sm-1 control-label"><span class="key">*</span>目的港</label>
                <div class="col-sm-3">
                   
                    <select class="endPort form-control n-key" name="destinationport" id="destinationport">
                        <option value="">目的港</option>
						@foreach( $ports as $k=> $val ) 
							<option value="{{data_get( $val , 'id') }}" alt="{{data_get( $val , 'short_py') }}"
							@if( request()->input('destinationport') == data_get( $val , 'id') ) selected @endif 
							@if( data_get( $order , 'destinationport') == data_get( $val , 'id') ) selected @endif 
							>{{data_get( $val , 'name') }}</option>
						@endforeach	
                    </select>
                </div>
            </div>
            <div class="form-group">
				<label for="exampleInputName2" class="col-sm-1 control-label">船公司</label>
                <div class="col-sm-3">
                   
                    <select class="endPort form-control n-key" name="company_id" id="company_id">
                        <option value="0">请选择</option>
						@foreach( $company as $k=> $val ) 
							<option value="{{$k}}" 
							@if( data_get( $order , 'company_id') == $k ) selected @endif 
							@if( request()->input('company_id') == $k ) selected @endif 
							>{{$val}}</option>
						@endforeach	
                    </select>
                </div>
                <span class="col-sm-4 help-block">
					如果没有特殊要求可不选择
				</span>
            </div>
            <div class="form-group">
				<label for="exampleInputName2" class="col-sm-1 control-label">船名</label>
                <div class="col-sm-3">
                   
                    <select class="endPort form-control n-key" name="ship_id" id="ship_id">
                        <option value="0">待确认</option>
						@foreach( $ship as $k=> $val ) 
							<option value="{{$k}}" 
							@if( data_get( $order , 'company_id') == $k ) selected @endif 
							@if( request()->input('ship_id') == $k ) selected @endif 
							>{{$val}}</option>
						@endforeach	
                    </select>
                </div>
                <span class="col-sm-4 help-block">
					如果没有特殊要求可不选择
				</span>
            </div>
            <div class="form-group">
				<label for="exampleInputName2" class="col-sm-1 control-label">航次</label>
                <div class="col-sm-3">
                   
                    <select class="endPort form-control n-key" name="flight_id" id="flight_id">
                        <option value="0">待确认</option>
						@foreach( $flight as $k=> $val ) 
							<option value="{{$k}}" 
							@if( data_get( $order , 'company_id') == $k ) selected @endif 
							@if( request()->input('flight_id') == $k ) selected @endif  
							>{{$val}}</option>
						@endforeach	
                    </select>
                </div>
                <span class="col-sm-4 help-block">
					如果没有特殊要求可不选择
				</span>
            </div>
            <div class="form-group">
				<label for="transport_protocol" class="col-sm-1 control-label">
					<span class="key">*</span>运输协议
				</label>
				<div class="col-sm-3">
					<select name="transport_protocol" id="transport_protocol" class="form-control">
						@foreach( config('global.transport_protocol') as $k => $val )
							<option value="{{$k}}"
							@if( data_get( $order , 'transport_protocol') == $k ) selected @endif 
							@if( $k == 4 ) selected @endif 
							>{{$val}}</option>
						@endforeach
					</select>
				</div>
			</div>
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label"><span class="key">*</span>货物类别</label>
                <div class="col-sm-8 radio">
	                	@foreach( config('global.goods_kind') as $k => $val )
	                	<label class="radio-inline">
	                	<input type="radio" name="goods_kind" id="goods_kind" value="{{$k}}"
	                	@if( data_get( $order , 'goods_kind') == $k ) checked @endif 
	                	>{{$val}}
	                </label>
	                @endforeach
               	</div>
            </div>
			
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label"><span class="key">*</span>箱货信息</label>
                <div class="col-sm-10">
                    <table class="table">
                        <thead>
                            <tr class="good_style2" height="23">
                                <th width="152">货物名称</th>
                                <th width="100">箱型</th>
                                <th width="85">箱量</th>
                                <th width="85">总件数</th>
                                <th width="113">单柜毛重（吨）</th>
                                <th width="113">总体积（m³）</th>
                                <th width="">包装类型</th>
                            </tr>
                        </thead>
                        <tbody class="goods_info">
                            <tr class="good_style3" height="35">
                                <td>
                                    <input type="text" placeholder="必填项" class="form-control goods_name" 
                                    name="goods['name'][]" maxlength="6"
                                    value="{{data_get( data_get( $order , 'goods' ) , 'name' )}}"
                                    >
                                </td>
                                <td>
                                    <select name="goods[box_type][]" class="form-control box_type">
                                    	@foreach( config('global.box_type') as $k => $val )
                                            <option value="{{$k}}"
                                            @if( data_get( data_get( $order , 'goods' ) , 'box_type' ) == $k ) selected @endif

                                            >{{$val}}</option>
                                    @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" placeholder="必填" name="goods[box_num][]" class="form-control box_num" maxlength="10" 
                                    value="{{data_get( data_get( $order , 'goods' ) , 'box_num' )}}"
                                    >
                                </td>
                                <td>
                                    <input type="text" placeholder="" name="goods[total_num][]" class="form-control total_num" maxlength="8" 
                                    value="{{data_get( data_get( $order , 'goods' ) , 'total_num' )}}"
                                    >
                                </td>
                                <td>
                                    <input type="text" placeholder="必填" name="goods[weight][]" class="form-control weight" maxlength="5" 
                                    value="{{data_get( data_get( $order , 'goods' ) , 'weight' )}}"
                                    >
                                </td>
                                <td>
                                    <input type="text" class="form-control cubage" placeholder="" name="goods[cubage][]" maxlength="5" 
                                    value="{{data_get( data_get( $order , 'goods' ) , 'cubage' )}}"
                                    >
                                </td>
                                <td>
                                    <input type="text" class="form-control package_type" placeholder="必填" name="goods[package_type][]" maxlength="10" 
                                    value="{{data_get( data_get( $order , 'goods' ) , 'package' )}}"
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--
                <div class="col-sm-1">
                    <a href="javascript:void(0);" class="add_box">+录入箱号</a>
                </div>
                -->
            </div>
		</div>
        <div class="div02">
            <div class="row nml nmr" style="border-top:3px solid #1b809e;padding-top: 15px; padding-bottom: 15px;">
            </div>
        </div>
        
		<div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
			<h4 class="cdiv" cdiv="div03"><a href="javascript:" class="entrust-control btn btn-xs btn-danger pull-right">从模板选择</a>委托人信息</h4>
		</div>
		<div class="entrust checkin-select" style="display: none;">
			<ul>
			@if(  $entrust->isEmpty() ) 
				<li>
					您还没有添加过模板，您可以在本次订单提交的时候选择存为模板！
				</li>
			@else 
				@foreach( $entrust as $val )
					<li>
						<input type="radio" name="entrust_id" value="0" 
						data-fullname="{{$val->name}}" 
						data-contactname="{{$val->contact}}" 
						data-mobile="{{$val->mobile}}" /> &nbsp;{{$val->name}}/{{$val->contact}}/{{$val->mobile}}
					</li>
				@endforeach
			@endif
			</ul>
		</div>
		<div class="entrust checkin-create">
			<div class="form-group">
				<label for="entrust_fullname" class="col-sm-2 control-label"><span class="key">*</span>委托人全称</label>
				<div class="col-sm-4">
					<input type="text" class="form-control n-key" name="entrust_fullname" id="entrust_fullname" maxlength="30" 
					value="{{data_get( data_get( $order , 'entrust') , 'name' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="entrust_contactname" class="col-sm-2 control-label"><span class="key">*</span>委托联系人</label>
				<div class="col-sm-4">
					<input type="text" class="form-control n-key" name="entrust_contactname" id="entrust_contactname" maxlength="30" 
					value="{{data_get( data_get( $order , 'entrust') , 'contact' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="entrust_mobile" class="col-sm-2 control-label"><span class="key">*</span>委托人手机</label>
				<div class="col-sm-4">
					<input class="form-control n-key" type="text" name="entrust_mobile" id="entrust_mobile" maxlength="11" 
					value="{{data_get( data_get( $order , 'entrust') , 'mobile' )}}" 
					>
				</div>	
			</div>
		</div>
		
		<div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
			<h4 class="cdiv" cdiv="div02"><a href="javascript:" class="sender-control btn btn-xs btn-danger pull-right">从模板选择</a>发货人信息</h4>
		</div>
		<div class="sender checkin-select" style="display: none;">
			<ul>
			@if( $sender->isEmpty() ) 
				<li>
					您还没有添加过模板，您可以在本次订单提交的时候选择存为模板！
				</li>
			@else 
				@foreach( $sender as $val )
				<li>
					<input type="radio" name="sender_id" value="0" 
					data-fullname="{{$val->name}}" 
					data-contactname="{{$val->contact_name}}" 
					data-mobile="{{$val->mobile}}" 
					data-email="{{$val->email}}" 
					data-address="{{$val->address}}" 
					/> &nbsp;{{$val->name}}/{{$val->contact_name}}/{{$val->mobile}}/{{$val->email}}/{{$val->address}}
				</li>
				@endforeach
			@endif
				
			</ul>
		</div>
		<div class="sender checkin-create">
			<div class="form-group">
				<label for="sender_fullname" class="col-sm-2 control-label">
					<span class="key">*</span>发货人名称 :
				</label>
				<div class="col-sm-4">
					<input type="text" name="sender_fullname" id="sender_fullname" class="form-control" placeholder="公司名称/个人姓名" maxlength="50" 
					value="{{data_get( data_get( $order , 'sender') , 'name' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="sender_contactname" class="col-sm-2 control-label">
					<span class="key">*</span>发货联系人 :
				</label>
				<div class="col-sm-4">
					<input type="text" name="sender_contactname" class="form-control" id="sender_contactname" maxlength="50" 
					value="{{data_get( data_get( $order , 'sender') , 'contact_name' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="sender_mobile" class="col-sm-2 control-label">
					<span class="key">*</span>联系电话 :
				</label>
				<div class="col-sm-4">
					<input type="text" class="form-control n-key" name="sender_mobile" id="sender_mobile" maxlength="14" 
					value="{{data_get( data_get( $order , 'sender') , 'mobile' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="sender_email" class="col-sm-2 control-label">
					联系邮箱 :
				</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="sender_email" id="sender_email" maxlength="25" 
					value="{{data_get( data_get( $order , 'sender') , 'email' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="sender_address" class="col-sm-2 control-label">
					<span class="key" id="sender_address_tip"></span>
					装货地址 :
				</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="sender_address" id="sender_address" maxlength="25"  value="{{data_get( data_get( $order , 'sender') , 'address' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="sender_date" class="col-sm-2 control-label">
					<span class="key" id="sender_date_tip">*</span>
					装货日期：
				</label>
				<div class="col-sm-4">
					<input id="sender_date" type="text" readonly="" data-date-format="yyyy-mm-dd" class="date form-control datepicker" name="sender_date" value="{{request()->input('date')}}">
				</div>
			</div>
		</div>

		<div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
			<h4 class="cdiv" cdiv="div05"><a href="javascript:" class="recevier-control btn btn-xs btn-danger pull-right">从模板选择</a>收货人信息</h4>
		</div>
		<div class="recevier checkin-select" style="display: none;">
			<ul>
			@if(  $recevier->isEmpty() ) 
				<li>
					您还没有添加过模板，您可以在本次订单提交的时候选择存为模板！
				</li>
			@else 
				@foreach( $recevier as $val )
				<li>
					<input type="radio" name="recevier_id" value="0" 
					data-fullname="{{$val->name}}" 
					data-contactname="{{$val->contact_name}}" 
					data-mobile="{{$val->mobile}}" 
					data-email="{{$val->email}}" 
					data-address="{{$val->address}}" 
					data-idno="{{$val->id_no}}"
					/> &nbsp;{{$val->name}}/{{$val->contact_name}}/{{$val->mobile}}/{{$val->email}}/{{$val->address}}/{{$val->id_no}}
				</li>
				@endforeach
			@endif
				
			</ul>
		</div>
		<div class="recevier checkin-create">
			<div class="form-group">
				<label for="recevier_fullname" class="col-sm-2 control-label">
					<span class="key">*</span>收货人全称 :
				</label>
				<div class="col-sm-4">
					<input type="text" id="recevier_fullname" class="form-control" name="recevier_fullname" placeholder="收货公司/个人真实姓名" maxlength="50" 
					value="{{data_get( data_get( $order , 'recevier') , 'name' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="recevier_contactname" class="col-sm-2 control-label">
					<span class="key">*</span>收货联系人 :
				</label>
				<div class="col-sm-4">
					<input type="text" id="recevier_contactname" class="form-control n-key" name="recevier_contactname" maxlength="50" 
					value="{{data_get( data_get( $order , 'recevier') , 'contact_name' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="recevier_mobile" class="col-sm-2 control-label">
					<span class="key">*</span>收货人电话 :
				</label>
				<div class="col-sm-4">
					<input type="text" id="recevier_mobile" class="form-control n-key" name="recevier_mobile" maxlength="14" 
					value="{{data_get( data_get( $order , 'recevier') , 'mobile' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="recevier_email" class="col-sm-2 control-label">
					收货人邮箱 :
				</label>
				<div class="col-sm-4">
					<input type="text" id="recevier_email" class="form-control n-key" name="recevier_email" maxlength="25" 
					value="{{data_get( data_get( $order , 'recevier') , 'email' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="recevier_address" class="col-sm-2 control-label">
					<span class="key" id="recevier_address_tip"></span>
					收货人地址 :
				</label>
				<div class="col-sm-4">
					<input type="text" id="recevier_address" class="form-control" placeholder="到门柜收货地址必填" name="recevier_address" maxlength="25" 
					value="{{data_get( data_get( $order , 'recevier') , 'address' )}}" 
					>
				</div>
			</div>
			<div class="form-group">
				<label for="recevier_id_no" class="col-sm-2 control-label">
					收货人身份证号码 :
				</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="recevier_id_no" placeholder="" name="recevier_id_no" maxlength="25" 
					value="{{data_get( data_get( $order , 'recevier') , 'id_no' )}}" 
					>
				</div>
				<span class="col-sm-4 help-block">
					收货人为个人时请填写证件号码
				</span>
			</div>
		</div>
		<div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
			备注</h4>
		</div>
		<div class="div04">
			<div class="form-group">
				<label for="recevier_fullname" class="col-sm-2 control-label">
					用户备注 :
				</label>
				<div class="col-sm-4">
					<textarea class="form-control" name="remark" placeholder="请填写备注">{!!$order->remark or ''!!}</textarea>
				</div>
			</div>
		</div>
		<div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
			价格</h4>
		</div>
		<div class="div04">
			<div class="form-group">
				<label class="col-sm-2 control-label">
					预计海运费 :
				</label>
				<label class="col-sm-4 control-label" style="text-align: left;color:red;" id="price_display">
					待定
				</label>
				<input type="hidden" value="0" name="ship_cost" id="ship_cost" />
				<span id="helpBlock" class="help-block">价格以平台最后确认为准</span>
			</div>
		</div>
		<div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
			<h4 class="cdiv" cdiv="div04"><a href="javascript:" class="soh pull-right">收起</a>保险信息</h4>
		</div>
		<div class="div04">
			<div class="form-group">
				<label for="need_insure" class="col-sm-2 control-label">
					是否需要保险：
				</label>
				<div class="col-sm-4 radio">
					<label class="radio-inline">
						<input type="radio" name="need_insure" value="1" @if( data_get( $order ,'enable_ensure' ) == 1 ) checked @endif >购买
					</label>
					<label class="radio-inline">
						<input type="radio" name="need_insure" value="2" @if( data_get( $order ,'enable_ensure' ) == 2 ) checked @endif  >不需要
					</label>
				</div>
			</div>

			<div class="form-group">
				<label for="insure_name" class="col-sm-2 control-label">
					被保险人名称：
				</label>
				<div class="col-sm-2">
					<input type="text" name="insure_name" class="form-control" id="insure_name" maxlength="20" 
					value="{{data_get( $order , 'ensure_name' )}}"
					>
				</div>
				<div class="col-sm-8">
					<span id="helpBlock" class="help-block">如不购买保险，将以订舱时船公司确认的最低保险为准。</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8 col-sm-offset-2">
					<span id="helpBlock" class="help-block">限制承保货物：石材、现金等有价票券、金银玉器、艺术品等、二手货物、动植物、易燃、易爆、易腐及易变质物品。</span>
				</div>
			</div>
			<div class="form-group">
				<label for="insure_goods_worth" class="col-sm-2 control-label">
					保险金额：
				</label>
				<div class="col-sm-4">
					<input type="text" name="insure_goods_worth" class="form-control" id="insure_goods_worth" maxlength="8" 
					value="{{data_get( $order , 'insure_goods_worth' )}}"
					><span class="help-block">数值区间1万-5千万</span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">
					免赔条件：
				</label>
				<div class="col-sm-8">
					<span class="help-block">
						短量免赔：散装货物每次事故短量免赔为保险金额的0.3%；<br>
						破碎免赔：每次事故绝对免赔额为人民币5000元/箱或保险金额的10%，两者取高；<br>
						湿损免赔：每次事故绝对免赔额为人民币5000元/箱或保险金额的10%，两者取高；<br>
						其他货损免赔：其他货损每次事故绝对免赔额为人民币800元/箱；本免赔的计算单位为单个集装箱。
					</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8 col-sm-offset-2">
					<span class="help-block">
						短量免赔：散装货物每次事故短量免赔为保险金额的0.3%；
					</span>
				</div>
			</div>
		</div>
		<input type="hidden" id="is_default" name="is_default" value="0">

		<div class="form-group">
			<div class="col-sm-8 col-sm-offset-2">
				<a href="javascript:" class="btn btn-danger submit" id="sub_wts">提交委托书</a>
				<span class="wtxx1" style="display:none">
					<input type="checkbox" id="isagree" value="1">我已阅读并同意<a href="{{route('singlepage' , ['id' => 9 ])}}" target="_blank">《声明条款》</a>
				</span>
			</div>
		</div>
   </form>
</div>
@endsection

@section('script')
<link href="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC3/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC3/js/bootstrap-datepicker.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap-datepicker/1.7.0-RC2/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="{{asset('js/bootstrapValidator.min.js')}}"></script>
<script>
$(document).ready(function(){
	$('select').select2({
		matcher: function(term, text) {
		       if ( typeof term.term == 'undefined' ) {
					return text ;
			   }
			   var attr = $(text.element).attr('alt');
			   attr = attr ? attr : '' ;
		       return text.text.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ||
					attr.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ? text : null ;
		   }
	});
	$('.datepicker').datepicker({
	    language:'zh-CN'
	});
	$("#transport_protocol").change(function () {
        var v = $(this).val();
        switch (v) {
            case "1":
                $("#recevier_address_tip").html("");
                $("#sender_address_tip").html("");
                break;
            case "2":
                $("#recevier_address_tip").html("");
                $("#sender_address_tip").html("*");
                break;
            case "3":
                 $("#recevier_address_tip").html("*");
                $("#sender_address_tip").html("");
                break;
            case "4":
                $("#recevier_address_tip").html("*");
                $("#sender_address_tip").html("*");
                break;
            default:
                $("#recevier_address_tip").html("");
                $("#sender_address_tip").html("");
                break;
        }
    });
	
	function validate() {
	
	}
	
	$('#sub_wts').on('click' , function(){
		//检查起始港
		var shipment = $('#shipment').val();
		var destinationport = $('#destinationport').val();
		if( !shipment ) {
			layer.msg("请选择出发地港口");
			return false ;
		}
		if( !destinationport ) {
			layer.msg("请选择出目的地港口");
			return false ;
		}
		if( shipment == destinationport ) {
			layer.msg('出发港口不能和目的地港口一致') ;
			return false ;
		}
		var type = $('input[name="goods_kind"]:checked').length ;
		if( type == 0 ) {
			layer.msg('请选择货物类型') ;
			return false ;
		}
		var verify = true ;
		$('.goods_info tr').each(function(){
			console.log( 1 );
			console.log( $(this).find('.goods_name').val() );
			if( true == verify && $(this).find('.goods_name').val().trim() =='' ) {
				layer.msg('请填写货物名称') ;
				verify = false ;
				return false ;
			}
			if( true == verify && !$(this).find('.box_num').val() ) {
				layer.msg('请填写货物箱量') ;
				verify = false ;
				return false ;
			}
			if( true == verify && !$(this).find('.weight').val() ) {
				layer.msg('请填写货物单柜毛重') ;
				verify = false ;
				return false ;
			}
			if( true == verify && !$(this).find('.package_type').val() ) {
				layer.msg('请填写货物包装类型') ;
				verify = false ;
				return false ;
			}

		});
		if( false == verify ) {
			return false ;
		}
		//
		if( !$('#entrust_fullname').val() ) {
			layer.msg('请填写委托人名称') ;
			return false ;
		}
		if( !$('#entrust_contactname').val() ) {
			layer.msg('请填写委托联系人') ;
			return false ;
		}
		if( !$('#entrust_mobile').val() ) {
			layer.msg('请填写委托人手机') ;
			return false ;
		}
		//发货人
		if( !$('#sender_fullname').val() ) {
			layer.msg('请填写发货人名称') ;
			return false ;
		}

		if( !$('#sender_contactname').val() ) {
			layer.msg('请填写发货人联系人') ;
			return false ;
		}
		if( !$('#sender_mobile').val() ) {
			layer.msg('请填写发货人手机') ;
			return false ;
		}
		if( $('#transport_protocol').val() == 2 || $('#transport_protocol').val() == 4) {
			if( !$('#sender_address').val() ) {
				layer.msg('请填写装货地址') ;
				return false ;
			}
		}
		
		if( !$('#sender_date').val() ) {
			layer.msg('请填写装货日期') ;
			return false ;
		}
		//收货人
		if( !$('#recevier_fullname').val() ) {
			layer.msg('请填写收货人名称') ;
			return false ;
		}

		if( !$('#recevier_contactname').val() ) {
			layer.msg('请填写收货联系人') ;
			return false ;
		}
		if( !$('#recevier_mobile').val() ) {
			layer.msg('请填写收货人手机') ;
			return false ;
		}


		/**
		if( !$('#recevier_email').val() ) {
			layer.msg('请填写收货人邮箱') ;
			return false ;
		}
		**/
		if( $('#transport_protocol').val() == 3 || $('#transport_protocol').val() == 4) {
			if( !$('#recevier_address').val() ) {
				layer.msg('请填写收货地址') ;
				return false ;
			}
		}
		/**
		if( !$('#recevier_id_no').val() ) {
			layer.msg('请填写收货人证件号码') ;
			return false ;
		}
		**/

		if( $('input[name="need_insure"]:checked').length == 0 ) {
			layer.msg('请选择是否需要保险!') ;
			return false ;
		}

		function checkin() {
			var data = $('#checkin').serialize() ;
			$.ajax({
				'url':location.href ,
				'data':data ,
				'type':'post' ,
				'dataType':'json' ,
				'success':function( data ){
					layer.msg( data.msg );
					if( data.errcode === 0 ) {
						setTimeout( function(){
							location.href = "{{route('member.order')}}";
						} , 1500 );
					}
				}
			});
		}


		//提示是否要加入模板
		layer.confirm("您是否需要把本次订单信息添加到订单模板中" , function( index ){
			layer.close( index );
			$('#is_default').val( 1 ) ;
			checkin();
		} , function( index ){
			layer.close( index );
			$('#is_default').val( 0 ) ;
			checkin();
		});

	});


	$('.entrust-control').click( function(){
		$('.entrust.checkin-select').toggle();
	});

	$('.sender-control').click( function(){
		$('.sender.checkin-select').toggle();
	} );

	$('.recevier-control').click( function(){
		$('.recevier.checkin-select').toggle();
	} );

	$('input[name="entrust_id"]').click( function(){
		$('#entrust_fullname').val( $(this).data('fullname') );
		$('#entrust_contactname').val( $(this).data('contactname') );
		$('#entrust_mobile').val( $(this).data('mobile') );
		$('.entrust.checkin-select').toggle();
	});
	
	$('input[name="sender_id"]').click( function(){
		$('#sender_fullname').val( $(this).data('fullname') );
		$('#sender_contactname').val( $(this).data('contactname') );
		$('#sender_mobile').val( $(this).data('mobile') );
		$('#sender_address').val( $(this).data('address') );
		$('.sender.checkin-select').toggle();
	});

	$('input[name="recevier_id"]').click( function(){
		$('#recevier_fullname').val( $(this).data('fullname') );
		$('#recevier_contactname').val( $(this).data('contactname') );
		$('#recevier_mobile').val( $(this).data('mobile') );
		$('#recevier_address').val( $(this).data('address') );
		$('#recevier_id_no').val( $(this).data('idno') );
		$('.recevier.checkin-select').toggle();
	});

	//当船公司发生变化时 改新船信息

	$('#company_id').on('change' , function(){
		var id = $(this).val();
		if( id ) {
			$.get( "{{route('getship')}}" , {id:id} , function( data){
				console.log( data );
				if( data.errcode === 0 ) {
					var html = '<option value="0" >待确认</option>' ;
					for( var i in data.data ) {
						html += '<option value="' + i + '" >'+ data.data[i] +'</option>' ;
					}
					$('#ship_id').html( html );
				} else {
					$('#ship_id').html("<option value='0'>待确认</option>");
				}
			} , 'json') ;
		} else {
			$('#ship_id').html("<option value='0'>待确认</option>");
		}
		//请空航次信息
		$('#flight_id').html("<option value='0'>待确认</option>");
	});

	//当船信息更改时  修改航次信息
	$('#ship_id').on('change' , function(){
		var id = $(this).val();
		if( id ) {
			var fromPort = $('#shipment').val();
			var toPort = $('#destinationport').val();
			$.get( "{{route('getflight')}}" , {id:id , 'from' : fromPort , 'to' : toPort } , function( data){
				console.log( data );
				if( data.errcode === 0 ) {
					var html = '<option value="0" >待确认</option>' ;
					for( var i in data.data ) {
						html += '<option value="' + i + '" >'+ data.data[i] +'</option>' ;
					}
					$('#flight_id').html( html );
				} else {
					$('#flight_id').html("<option value='0'>待确认</option>");
				}
			} , 'json') ;
		} else {
			//清空航次信息
			$('#flight_id').html("<option value='0'>待确认</option>");
		}
	});
	//每隔500MS 查询一次价格
	setInterval( function(){
		var fromPort = $('#shipment').val();
		var toPort = $('#destinationport').val();
		var company_id = $('#company_id').val();
		var ship_id = $('#ship_id').val();
		var flight_id = $('#flight_id').val();
		var box_type = $('.box_type').val();
		var box_num = $('.box_num').val();
		if( !fromPort ) {
			$("#price_display").html('待定');
			$("#ship_cost").val( 0 );
			return false ;
		}
		if( !toPort ) {
			$("#price_display").html('待定');
			$("#ship_cost").val( 0 );
			return false ;
		}
		if( !company_id ) {
			$("#price_display").html('待定');
			$("#ship_cost").val( 0 );
			return false ;
		}
		if( !ship_id ) {
			$("#price_display").html('待定');
			$("#ship_cost").val( 0 );
			return false ;
		}
		if( !flight_id ) {
			$("#price_display").html('待定');
			$("#ship_cost").val( 0 );
			return false ;
		}
		if( !box_type ) {
			$("#price_display").html('待定');
			$("#ship_cost").val( 0 );
			return false ;
		}
		if( !box_num ) {
			$("#price_display").html('待定');
			$("#ship_cost").val( 0 );
			return false ;
		}
		$.get("{{route('checkprice')}}" , {
			'from':fromPort , 
			'to':toPort , 
			'company_id' : company_id ,
			'ship_id' : ship_id ,
			'flight_id': flight_id ,
			'box_type' : box_type ,
			'box_num' : box_num 
		} , function( data ){
			if( data.errcode === 0 ) {
				if( data.data > 0 ) {
					$("#price_display").html( data.data );
					$("#ship_cost").val( data.data );
				} else {
					$("#price_display").html('待定');
					$("#ship_cost").val( 0 );
				}

			} else {
				$("#price_display").html('待定');
				$("#ship_cost").val( 0 );
			}
		} , 'json');
	} , 500 );
});
	
	
</script>
@endsection
