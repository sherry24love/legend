@extends('layouts.layout')

@section('style')
<style type="text/css">
    .none{display: none;}
    .block{
        display: block;
    }
    #newBridge .icon-right-center {display: none;}
.nb-icon-inner-wrap {display: none;}
</style>
@endsection


@section('content')

<div class="container">
    <div class="row">
        @include('block.member_left')
		
        <div class="col-sm-10 npl npr">
            <div class="right_second">
            		@include('block.success')
            		@include('block.error')
                <div class="list-tab">
                    <ul class="order_list">
	                    <li class="active">
	                    		<a href="{{route('member.bank')}}">修改密码</a>
	                    </li>
	                </ul>
                </div>
                
                <div class="order_info" style="margin-top:20px;">
            		<div class="panel-body">
						<form method="post" id="modpass" name="modpass" class="form-horizontal">
						{{csrf_field()}}
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">手机号码</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" readonly="" value="{{$user->name}}" id="mob" name="mob" placeholder="手机号码"/>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">旧密码</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" value=""  maxlength="16"  id="old_pwd" name="old_pwd" placeholder="旧密码"/>
								</div>
							</div>
							<div class="form-group">
								<label for="contact_name" class="col-sm-2 control-label">新密码</label>
								<div class="col-sm-5">
									<input type="password" class="form-control" maxlength="16"  value="" id="pwd" name="pwd"/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-2">
									<span class="login-btn btn btn-danger" onclick="save()">立即修改</span>
								</div>
							</div>
						</form>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript">
$('#modpass').bootstrapValidator({
		message: '必填',
		feedbackIcons: {
		    valid: 'glyphicon glyphicon-ok',
		    invalid: 'glyphicon glyphicon-remove',
		    validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
		    old_pwd: {
		        validators: {
		            notEmpty: {
		                message: '请填写旧密码'
		            }
		        }
		    } ,
			pwd:{
				validators: {
		            notEmpty: {
		                message: '请填写新密码'
		            }
		        }
			}
		}
});
function save() {
	var bootstrapValidator = $("#modpass").data('bootstrapValidator');
	bootstrapValidator.validate();
	if(bootstrapValidator.isValid()) {
		$.ajax({
			url:location.href ,
			'type':'post' ,
			'dataType': 'json' ,
			'data' : $('#modpass').serialize() ,
			'success':function( data ){
				layer.msg( data.msg );
				
			}

		});
	}
}
</script>
@endsection
