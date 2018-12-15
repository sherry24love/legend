<div class="title"><span>航线列表</span></div>
@if( $page->count() > 0 )
	@foreach( $page->items() as $val )
	<?php
		$dates = array();
		if( $val->flight && $val->flight->dates ) {
			foreach( $val->flight->dates as $v ) {
				$dates[ $v->port_id ] = $v ;
			}
		}
	?>
	<div class="bg-white">
		<div class="hx-box">
			<div class="hx-logo">
				<img src="{{ data_get( data_get( $company , $val->flight->ship->company_id ) , 'cover' ) ? asset( data_get( data_get( $company , $val->flight->ship->company_id ) , 'cover' ) ) : asset('images/logo.jpg') }}">
				{{ data_get( data_get( $company , $val->flight->ship->company_id ) , 'name' ) }}
			</div>
			<div class="hx-info">
				<div class="hx-item">
					<h3>{{data_get( data_get( $ports , $val->from_port_id ) , 'name' )}}</h3>
					<p class="aui-margin-t-5">预计开船</p>
					<p class="red">{{ data_get( $val , 'from_port_leave_time' ) ? data_get( $val , 'from_port_leave_time' ) : '待定' }}</p>
					<p class="aui-margin-t-5">船名/航次</p>
					<p class="hx-c">{{$val->flight->ship->name}}/{{$val->flight->no }}</p>
				</div>
				<div class="hx-ico"></div>
				<div class="hx-item">
					<h3>{{data_get( data_get( $ports , $val->to_port_id ) , 'name' )}}</h3>
					<p class="aui-margin-t-5">预计到港</p>
					<p class="red">{{ data_get( $val , 'to_barge_port_arrive_time' ) ? data_get( $val , 'to_barge_port_arrive_time' ) : '待定' }}</p>
					<p class="aui-margin-t-5">航程</p>
					<p class="hx-c">
						@if( data_get( $val , 'from_port_leave_time' ) && data_get( $val , 'to_barge_port_arrive_time' ) )
						{{ strtotime( data_get( $val , 'to_barge_port_arrive_time' ) ) / 86400 - strtotime( data_get( $val , 'from_port_leave_time' ) ) / 86400 }} 天
						@else
						待定
						@endif
					</p>
				</div>
			</div>
		</div>
		<div class="hx-btn">
			<div class="btn-item">
				<h4>20GP</h4>
				@if( $val->price_20gp > 0 )
					<a href="{{route('wap.checkin' , ['shipment' => $val->from_port_id  , 'destinationport' => $val->to_port_id  , 'date' => request()->input('date') , 'box_type' => 1 , 'company_id' => $val->flight->ship->company_id , 'ship_id' => $val->flight->ship->id  , 'flight_id' => $val->flight_id ] )}}" class="hx-red-btn" >{{$val->price_20gp}}</a>
				@else
					<a id="{{$val->id}}"  class="hx-yellow-btn">咨询客服</a>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                        BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: '{{$val->id}}'});
                                });
                            </script>
				@endif
			</div>
			<div class="btn-item">
				<h4>40GP</h4>
				@if( $val->price_40gp > 0 )
					<a href="{{route('wap.checkin' , ['shipment' => $val->from_port_id  , 'destinationport' => $val->to_port_id  , 'date' => request()->input('date') , 'box_type' => 1 , 'company_id' => $val->flight->ship->company_id , 'ship_id' => $val->flight->ship->id  , 'flight_id' => $val->flight_id ] )}}" class="hx-red-btn" >{{$val->price_40gp}}</a>
				@else
					<a id="{{$val->id}}"  class="hx-yellow-btn">咨询客服</a>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                        BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: '{{$val->id}}'});
                                });
                            </script>
				@endif
			</div>
			<div class="btn-item">
				<h4>40HQ</h4>
				@if( $val->price_40hq > 0 )
					<a href="{{route('wap.checkin' , ['shipment' => $val->from_port_id  , 'destinationport' => $val->to_port_id  , 'date' => request()->input('date') , 'box_type' => 1 , 'company_id' => $val->flight->ship->company_id , 'ship_id' => $val->flight->ship->id  , 'flight_id' => $val->flight_id ] )}}" class="hx-red-btn" >{{$val->price_40hq}}</a>
				@else
					<a id="{{$val->id}}"  class="hx-yellow-btn">咨询客服</a>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                        BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: '{{$val->id}}'});
                                });
                            </script>
				@endif
			</div>
		</div>
	</div>
	@endforeach
@else
	<div class="nodata">
		<div class="nodata-pic"><img src="{{asset('mobile/images/nodata.png')}}"></div>
		<h4>对不起，没搜索到您需要的航线</h4>
		<div class="nodata-btn">
					<a id="qq-serve"  class="big-yellow-btn">咨询客服</a>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                        BizQQWPA.addCustom({aty: '0', a: '0', nameAccount: 800835168, selector: 'qq-serve'});
                                });
                            </script>
			<a href="{{route('wap.checkin' , ['shipment' => request()->input('fromport') , 'destinationport' => request()->input('toport') , 'date' => request()->input('date')])}}" class="big-red-btn">直接订舱</a>
		</div>
	</div>
@endif

	@if( $page->lastPage() > 1 )
    <a class="load-more" data-href="{{$page->next()}}" current-page="{{$page->currentPage()}}" max-page="{{$page->lastPage()}}">点击查看更多</a>
    @endif
