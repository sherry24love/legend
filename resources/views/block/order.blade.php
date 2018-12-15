<h1>立即下单</h1>
<form action="{{route('checkin')}}" class="form-horizontal" id="demandaddform">
    <div class="form-group quick-search">
        <label class="col-sm-3 control-label">起始港</label>
        <div class="col-sm-9" style="padding-left:0px;">
            <select class="form-control" name="shipment" id="shipment">
                <option value="">起始港</option>
                @foreach( $ports as $k=> $val ) 
					<option value="{{data_get( $val , 'id') }}" alt="{{ data_get( $val , 'short_py') }}" >{{ data_get( $val , 'name' )}}</option>
				@endforeach	
            </select>
        </div>
    </div>
    <div class="form-group quick-search">
        <label class="col-sm-3 control-label">目的港</label>
        <div class="col-sm-9" style="padding-left:0px;">
            <select class="form-control" name="destinationport" id="destinationport">
                <option value="">目的港</option>
                @foreach( $ports as $k=> $val ) 
					<option value="{{data_get( $val , 'id') }}" alt="{{ data_get( $val , 'short_py') }}" >{{ data_get( $val , 'name' )}}</option>
				@endforeach	
            </select>
        </div>
    </div>
    <div class="form-group quick-search">
        <div class="col-sm-offset-8 col-sm-4" style="padding-left:0px;padding-right:0px;">
            <a href="javascript:void(0);" class="btn btn-danger quick-order" style="padding:6px 25px;" >快速下单</a>
        </div>
    </div>
</form>