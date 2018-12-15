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
	                    		<a href="{{route('member.bank')}}">我的资料</a>
	                    </li>
	                </ul>
                </div>
                
                <div class="order_info" style="margin-top:20px;">
            		<div class="panel-body">
						<form class="form-horizontal" name="updateform" id="updateform" method="post">
							{{csrf_field()}}
	                        <div class="form-group">
	                            <label for="inputEmail3" class="col-sm-2 control-label">手机号码</label>
	                            <div class="col-sm-5">
									<input type="text" class="form-control" disabled value="{{$user->name}}" />
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label for="contact_name" class="col-sm-2 control-label">联系人</label>
	                            <div class="col-sm-5">
	                                <input type="text" class="form-control" value="{{$user->contact}}" id="contact_name" name="contact_name"/>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label for="qq" class="col-sm-2 control-label">QQ</label>
	                            <div class="col-sm-5">
	                                <input type="text" class="form-control" value="{{$user->qq}}" id="qq" name="qq"/>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">所有资金</label>
	                            <div class="col-sm-5">
									<input type="text" class="form-control" disabled value="{{$money}}" />
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">推荐人</label>
	                            <div class="col-sm-5">
									<input type="text" class="form-control" disabled value="{{$user->recuser->name or ''}}" />
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">微信绑定?</label>
	                            <div class="col-sm-5">
	                                <p class="form-control-static">{{ $user->outer_id ? '已绑定' : '未绑定' }}</p>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="col-sm-offset-2 col-sm-5">
	                                <button type="submit" class="btn btn-danger">保存资料</button>
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
