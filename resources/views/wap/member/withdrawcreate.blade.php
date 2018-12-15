@extends('wap.layout')

@section('content')

<div class="aui-content aui-margin-t-15">
    <ul class="aui-list aui-form-list">
    	<li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">可提现金额</div>
                <div class="aui-list-item-input" >
                	{{$money}}
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">银行卡</div>
                <div class="aui-list-item-input" >
                	<select name="bank_id" id="bank_id" class="form-control">
                        @foreach( $bank as $val )
                            <option value="{{$val->id}}">
                                {{$val->name}}
                                
                                {{$val->card_no}}
                                
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">提现金额</div>
                <div class="aui-list-item-input" >
                    <input type="number" placeholder="请输入提现金额" id="cash" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                        
                </div>
            </div>
        </li>
    </ul>
    <div class="aui-margin-15">
    	<div class="aui-btn aui-btn-danger aui-btn-block" id="save">保存</div>
    </div>
</div>

@endsection

@section('footer')
@endsection

@section('script')
<script type="text/javascript">
var toast = new auiToast();
$('#save').click(function(){
	var bank_id = $('#bank_id').val();
    var cash = $('#cash').val();
	if( !bank_id ) {
		toast.fail({
		    title:"请选择提现的银行卡",
		    duration:2000
		});
        return false ;
	}
    if( !cash ) {
        toast.fail({
            title:"请输入提现金额",
            duration:2000
        });
        return false ;
    }

	$.ajax({
		url:"{{route('wap.withdraw.create')}}" ,
		type:"post" ,
		data:{
			bank_id:bank_id ,
            cash:cash ,
			_token:"{{csrf_token()}}"
		},
		dataType:'json' ,
		success:function( data ){
            if(data.errcode === 0 ) {
                toast.success({title:data.msg}) ;
                setTimeout( function(){
                    location.href = "{{route('wap.member.withdraw')}}";
                } , 1500 );
            } else {
                toast.fail({title:data.msg}) ;
            }
		}
	});
});

</script>
@endsection