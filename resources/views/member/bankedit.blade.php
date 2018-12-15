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
	                    <li class="">
	                    		<a href="{{route('member.bank')}}">我的银行卡</a>
	                    </li>
	                    <li class="active">
	                    		<a class=""><b>编辑银行卡</b></a>
	                    </li>
	                   	
	                </ul>
                </div>
                
                <div class="order_info" style="margin-top:20px;">
                		<form name="bankForm" id="bankForm" method="post" class="form-horizontal">
                				{{csrf_field()}}
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">持卡姓名</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" name="name" id="name" value="{{$bank->name}}">
								</div>
							</div>
							<div class="form-group">
								<label for="bank_id" class="col-sm-2 control-label">开户行</label>
								<div class="col-sm-5">
									<select name="bank_id" id="bank_id" class="form-control">
										@foreach( config('global.bank') as $k => $val )
											<option value="{{$k}}" @if( $bank->bank_id == $k ) selected @endif >{{$val}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="card_no" class="col-sm-2 control-label">账号</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" name="card_no" id="card_no" value="{{$bank->card_no}}">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label"></label>
								<div class="col-sm-3">
									<a href="javascript:;" onclick="save();" class="btn btn-primary">修改</a>
								</div>
							</div>
						</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript">
$('#bankForm').bootstrapValidator({
		message: '必填',
		feedbackIcons: {
		    valid: 'glyphicon glyphicon-ok',
		    invalid: 'glyphicon glyphicon-remove',
		    validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
		    name: {
		        validators: {
		            notEmpty: {
		                message: '请填写持卡人姓名'
		            }
		        }
		    } ,
			bank_id:{
				validators: {
		            notEmpty: {
		                message: '请选择开户银行'
		            }
		        }
			} ,
			card_no:{
				validators: {
		            notEmpty: {
		                message: '请填写银行卡号'
		            }
		        }
			}
		}
});
function save() {
	var bootstrapValidator = $("#bankForm").data('bootstrapValidator');
	bootstrapValidator.validate();
	if(bootstrapValidator.isValid()) {
		document.getElementById('bankForm').submit();
	}
}
</script>
@endsection
