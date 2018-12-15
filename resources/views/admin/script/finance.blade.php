$('.select-org').select2();

$('.month').datetimepicker({
	'format':'YYYY-MM' ,
	'locale':'zh-CN' 
});

$('#checkall').on('click' , function(){
	var check = $(this).prop('checked');
	$('.thead tbody input[type="checkbox"]').prop( 'checked' , check );
});

$('.thead tbody input[type="checkbox"]').on('click' , function(){
	if( $('.thead tbody input[type="checkbox"]').length == $('.thead tbody input[type="checkbox"]:checked').length ) {
		$('#checkall').prop( 'checked' , true );
	}else {
		$('#checkall').prop( 'checked' , false );
	}
});

//
$('.grid-batch-check').on('click' , function(){
	var id = [] ;
	$('.thead tbody input[type="checkbox"]:checked').each(function(){
		id.push( $(this).val() );
	});
	if( id.length == 0 ) {
		toastr.error('请选择要付款的项');
		return false ;
	}
	layer.confirm("您确定要转结选中的款项吗？" , function( index ){
		layer.close( index );
		checkFinance( id );
		
	});
});

$('.btn-check').on('click' , function(){
	var id = [] ;
	var that = $(this);
	layer.confirm("本月挂号费共"+ $(that).data('total') +" ,您确定已经转结给医院吗？" , function( index ){
		layer.close( index );
		if( $(that).data('id') ) {
			id.push( $(that).data('id') ) ;
			checkFinance( id );
		}
	});
	

});

function checkFinance( id ) {
	$.ajax({
		'url':"{{route('admin.finance.check')}}" ,
		'type':'post' ,
		'dataType':'json' ,
		'data' :{
			_method:'put' ,
			_token:'{{csrf_token()}}' ,
			id : id 
		},
		success:function( data ) {
			if( data.errcode === 0 ) {
				toastr.success( data.msg );
				$.pjax.reload('#pjax-container');
			} else {
				toastr.error( data.msg );
			}
		}
	});
}
