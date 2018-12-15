@extends('wap.layout')

@section('content')

<div class="aui-content aui-margin-t-15">
    <ul class="aui-list aui-form-list">
    	<li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">开户人姓名</div>
                <div class="aui-list-item-input" >
                	<input type="text" placeholder="请输入持卡人姓名" id="name" value="" />
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">开户银行</div>
                <div class="aui-list-item-input" >
                	<select name="bank_id" id="bank_id" class="form-control">
                        @foreach( config('global.bank') as $k => $val )
                            <option value="{{$k}}">{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="aui-list-item-label aui-font-size-12">
                    	
                </div>
            </div>
        </li>
        <li class="aui-list-item">
            <div class="aui-list-item-inner">
                <div class="aui-list-item-label gray aui-font-size-14">银行卡号</div>
                <div class="aui-list-item-input" >
                    <input type="text" placeholder="请输入银行卡号" id="card_no" value="" />
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
	var name = $('#name').val();
	var bank_id = $('#bank_id').val();
    var card_no = $('#card_no').val();
	if( !name ) {
		toast.fail({
		    title:"请输入持卡人姓名",
		    duration:2000
		});
        return false ;
	}
	if( !bank_id ) {
		toast.fail({
		    title:"请选择开户行",
		    duration:2000
		});
        return false ;
	}
    if( !card_no ) {
        toast.fail({
            title:"请输入银行卡号",
            duration:2000
        });
        return false ;
    }

	$.ajax({
		url:"{{route('wap.bank.create')}}" ,
		type:"post" ,
		data:{
			name:name ,
			bank_id:bank_id ,
            card_no:card_no ,
			_token:"{{csrf_token()}}"
		},
		dataType:'json' ,
		success:function( data ){
            if(data.errcode === 0 ) {
                toast.success({title:data.msg}) ;
                setTimeout( function(){
                    location.href = "{{route('wap.member.bank')}}";
                } , 1500 );
            } else {
                toast.fail({title:data.msg}) ;
            }
		}
	});
});

</script>
@endsection