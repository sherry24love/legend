<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">订单编辑</h3>

        <div class="box-tools">
            
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" method="POST" >
        <div class="box-body">
        
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        
            <li class="active">
                <a href="#tab-patients" data-toggle="tab">
                    患者信息 <i class="fa fa-exclamation-circle text-red hide"></i>
                </a>
            </li>
            <li class="">
                <a href="#tab-prescription" data-toggle="tab">
                    中药饮片信息 <i class="fa fa-exclamation-circle text-red hide"></i>
                </a>
            </li>
            <li class="">
                <a href="#tab-medicine" data-toggle="tab">
                    中成药信息 <i class="fa fa-exclamation-circle text-red hide"></i>
                </a>
            </li>
            <li class="">
                <a href="#tab-secrettip" data-toggle="tab">
                    养生方信息 <i class="fa fa-exclamation-circle text-red hide"></i>
                </a>
            </li>
        

    </ul>
    <div class="tab-content fields-group">
			<!-- 订单基本信息 -->
            <div class="tab-pane active" id="tab-patients">
                <div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">
			    <label for="consignee" class="col-sm-2 control-label">
			    	<i style="color:red;">&nbsp;*&nbsp;</i>
			    </label>
			
			    <div class="col-sm-8">
			
			        @include('admin::form.error')
			
			        <div class="input-group">
			
			            @if ($prepend)
			            <span class="input-group-addon">{!! $prepend !!}</span>
			            @endif
			
			            <input {!! $attributes !!} />
			
			            @if ($append)
			                <span class="input-group-addon clearfix">{!! $append !!}</span>
			            @endif
			
			        </div>
			
			        @include('admin::form.help-block')
			
			    </div>
			</div>
            </div>
            
            <div class="tab-pane " id="tab-prescription">
                
            </div>
            
            <div class="tab-pane " id="tab-medicine">
                
            </div>
            
            <div class="tab-pane " id="tab-secrettip">
                
            </div>

    </div>
</div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-sm-2">

            </div>
            <div class="col-sm-8">

                <div class="btn-group pull-right">
    <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> 提交">提交</button>
</div><div class="btn-group pull-left">
    <button type="reset" class="btn btn-warning">撤销</button>
</div>

            </div>

        </div>
		<!-- - 隐藏的信息- -->

        <!-- /.box-footer -->
    </form>
</div>