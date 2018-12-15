<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">编辑航次</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
				<a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
				<a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-edit"></i>&nbsp;返回</a>
			</div>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
	<form action="{{route('admin.flight.store')}}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container="">

        <div class="box-body">

            
                <div class="fields-group">
                    <div class="form-group 1">
						<label for="ship_id" class="col-sm-2 control-label">船名</label>
						<div class="col-sm-8">

							<select class="form-control ship_id" style="width: 100%;" name="ship_id" id="ship_id" >
								@foreach( $ship as $k => $val )
									<option value="{{$k}}" >{{$val}}</option>
								@endforeach
							</select>

						</div>
					</div>
					
					<div class="form-group 1">
						<label for="no" class="col-sm-2 control-label">航次名称</label>
						<div class="col-sm-8">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
								<input type="text" id="no" name="no" value="" class="form-control no" placeholder="输入航次名称" />
							</div>
						</div>
					</div>
					<div class="form-group 1">
						<label for="no" class="col-sm-2 control-label"></label>
						<div class="col-sm-8">
							<div class="row">
								<label class="col-sm-3 control-label" style="text-align:center;" >港口名称</label>
								<label class="col-sm-2 control-label" style="text-align:center;" >预计到港时间</label>
								<label class="col-sm-2 control-label" style="text-align:center;" >实际到港时间</label>
								<label class="col-sm-2 control-label" style="text-align:center;" >预计离港时间</label>
								<label class="col-sm-2 control-label" style="text-align:center;" >实际离港时间</label>
							</div>
						</div>
					</div>
					<div class="form-group 1">
						<label for="no" class="col-sm-2 control-label">起运港</label>
						<div class="col-sm-8">
							<div class="row">
								<div class="col-sm-3">
									<select class="form-control" style="width:100%;" name="from_port_id" id="from_port_id" >
									<option value="0" >请选择起运港</option>
									@foreach( $ports as $k => $val )
										<option value="{{$k}}" >{{$val}}</option>
									@endforeach
									</select>
								</div>
								<label class="col-sm-2 control-label">
									
								</label>
								<label class="col-sm-2 control-label">
									
								</label>
								<div class="col-sm-2">
									<div class="input-group">
										<input type="text" id="from_port_plan_date" name="from_port_plan_date" value="" class="form-control date" placeholder="请选择预计离港时间" />
									</div>
								</div>
								<div class="col-sm-2">
									<div class="input-group">
										<input type="text" id="from_port_actual_date" name="from_port_actual_date" value="" class="form-control date" placeholder="请选择实际离港时间" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<a class="btn btn-primary" id="appendPort">添加途经港</a>
						</div>
					</div>
					<div class="" id="passthroug_ports" >
						
					</div>
					<div class="form-group 1">
						<label for="no" class="col-sm-2 control-label">目的港</label>
						<div class="col-sm-8">
							<div class="row">
								<div class="col-sm-3">
									<select class="form-control" style="width:100%;" name="to_port_id" id="to_port_id"  >
									<option value="0" >请选择目的港</option>
										@foreach( $ports as $k => $val )
											<option value="{{$k}}" >{{$val}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-sm-2">
									<div class="input-group">
										<input type="text" id="to_port_plan_date" name="to_port_plan_date" value="" class="form-control date" placeholder="请选择预计到港时间" />
									</div>
								</div>
								<div class="col-sm-2">
									<div class="input-group">
										<input type="text" id="to_port_actual_date" name="to_port_actual_date" value="" class="form-control date" placeholder="请选择实际到港时间" />
									</div>
								</div>
							</div>
						</div>
					
					</div>
                </div>
            

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="col-sm-2">
			</div>
			<div class="col-sm-2">
				<div class="btn-group pull-left">
					<button type="reset" class="btn btn-warning pull-right">{{ trans('admin::lang.reset') }}</button>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="btn-group pull-right">
					<button type="submit" class="btn btn-info pull-right">{{ trans('admin::lang.submit') }}</button>
				</div>
			</div>

		</div>
        <!-- /.box-footer -->
	</form>
	<textarea style="display:none;" id="template" >
		<div class="form-group 1">
			<label for="no" class="col-sm-2 control-label">途经港</label>
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-3">
						<select class="form-control" style="width:100%;" name="port_id[]"  >
							@foreach( $ports as $k => $val )
								<option value="{{$k}}" >{{$val}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-2">
						<div class="input-group">
							<input type="text" id="to_port_leave_plan_date" name="arrive_plan_date[]" value="" class="form-control date" placeholder="请选择预计到港时间" />
						</div>
					</div>
					<div class="col-sm-2">
						<div class="input-group">
							<input type="text" id="to_port_leave_plan_date" name="arrive_actual_date[]" value="" class="form-control date" placeholder="请选择预计到港时间" />
						</div>
					</div>
					<div class="col-sm-2">
						<div class="input-group">
							<input type="text" id="from_port_leave_plan_date" name="leave_plan_date[]" value="" class="form-control date" placeholder="请选择预计离港时间" />
						</div>
					</div>
					<div class="col-sm-2">
						<div class="input-group">
							<input type="text" id="from_port_leave_actual_date" name="leave_actual_date[]" value="" class="form-control date" placeholder="请选择实际离港时间" />
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<a class="btn btn-danger btn-del">删除</a>
			</div>
		</div>
	</textarea>
</div>