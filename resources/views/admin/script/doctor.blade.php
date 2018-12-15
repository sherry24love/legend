$('.grid-row-auth-allow').unbind('click').bind('click', function(){
	var that = $(this);
	layer.confirm("您确定已经核实当前医生的信息真实，并且将医生设置为认证医生吗？" , function( index ){
		layer.close( index );
		if( $(that).data('id') ) {
			$.ajax({
				'url':"{{route('admin.doctor.allow')}}" ,
				'type':'post' ,
				'dataType':'json' ,
				'data' :{
					_method:'put' ,
					_token:'{{csrf_token()}}' ,
					id : $(that).data('id') 
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
		
	});
});

$('.grid-row-auth-disallow').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.confirm("您确定已经核实当前医的信息为虚假，并将医生设置为审核不通过吗" , function( index ){
		layer.close( index );
		if( $(that).data('id') ) {
			$.ajax({
				'url':"{{route('admin.doctor.disallow')}}" ,
				'type':'post' ,
				'dataType':'json' ,
				'data' :{
					_method:'put' ,
					_token:'{{csrf_token()}}' ,
					id : $(that).data('id') 
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
	});
	

});


