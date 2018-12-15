<div class="banner-left">
	<div class="order_box">
		<div role="tabpanel" class="place_order" id="place_order">
			<h2>快速下单</h2>
			<form method="get" name='demandaddform' class="form-horizontal" id="demandaddform" action="{{route('checkin')}}">
			
				<div class="form-group">
					<div class="col-sm-8">
					<select class="startPort form-control"  name="shipment" id="shipment">
						<option value="" >起始港</option>
						@foreach( $ports as $val ) 
							<option value="{{data_get( $val , 'id') }}" alt="{{ data_get( $val , 'short_py') }}" >{{ data_get( $val , 'name' )}}</option>
						@endforeach	
					</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-8">
					<select class="endPort form-control" name="destinationport" id="destinationport">
						<option value="" >目的港</option>
						@foreach( $ports as $k=> $val ) 
							<option value="{{data_get( $val , 'id') }}" alt="{{ data_get( $val , 'short_py') }}" >{{ data_get( $val , 'name' )}}</option>
						@endforeach	
					</select>
					</div>
					<div class="col-sm-2">
						<input type="button" class="btn btn-danger quick-order" value="快速下单"/>
					</div>
				</div>
			</form>
			<hr/>
			<div class="track_order">
				<h2>货物追踪</h2>
				<form method="get" name='demandaddform' class="form-horizontal" id="trackform" action="{{route('track')}}">
					<div class="form-group">
						<div class="col-sm-8">
							<input type="text" class="form-control" name="waybill" id="waybill" value="" placeholder="请输入运单号或柜号" />
						</div>
						<div class="col-sm-2">
							<input type="button" class="cursor btn btn-danger track-search" value="货物追踪"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>