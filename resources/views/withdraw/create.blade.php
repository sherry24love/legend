@extends('layouts.layout')

@section('style')
<style type="text/css">
    .none{display: none;}
    .block{
        display: block;
    }
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
	                    		<a href="{{route('member.withdraw')}}">提现记录</a>
	                    </li>
	                    <li class="active">
	                    		<a href="{{route('member.withdraw.create')}}" class=""><b>提现申请</b></a>
	                    </li>
	                   	
	                </ul>
                </div>
                
                <div class="order_info" style="margin-top:20px;">
                		<form name="bankForm" id="bankForm" method="post" class="form-horizontal">
                				{{csrf_field()}}
                				<div class="form-group">
								<label for="name" class="col-sm-2 control-label">可用金额</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" readonly value="{{$moeny or 0}}">
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">提现金额</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" name="cash" id="cash">
								</div>
							</div>
							<div class="form-group">
								<label for="bank_id" class="col-sm-2 control-label">银行卡</label>
								<div class="col-sm-5">
									<select name="bank_id" id="bank_id" class="form-control">
										@foreach( $bank as $val )
											<option value="{{$val->id}}">
												{{data_get( config('global.bank' ) , $val->bank_id )}}
												
												{{$val->name}}
												
												{{$val->card_no}}
												
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label"></label>
								<div class="col-sm-3">
									<a href="javascript:;" onclick="save();" class="btn btn-primary">申请</a>
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
		    cash: {
		        validators: {
		            notEmpty: {
		                message: '请填写提现金额'
		            } ,
		            numeric:{
		            		message:'提现金额只能是整数'
		            }
		        }
		    } ,
			bank_id:{
				validators: {
		            notEmpty: {
		                message: '请选择银行卡'
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
