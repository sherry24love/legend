$('.btn-deal').unbind('click').bind('click', function(){
	var that = $(this);
	layer.confirm("您确定要处理当前订单吗，一旦您处理后别的客服是不能处理本订单了？" , function( index ){
		layer.close( index );
		if( $(that).data('href') ) {
			$.ajax({
				'url': $(that).data('href') ,
				'type':'post' ,
				'dataType':'json' ,
				'data' :{
					_method:'put' ,
					_token:'{{csrf_token()}}'
				},
				success:function( data ) {
					if( data.errcode === 0 ) {
						toastr.success( data.msg );
						$.pjax({container:'#pjax-container', url: data.url });
					} else {
						toastr.error( data.msg );
						$.pjax.reload('#pjax-container');
					}
				}
			});
		}
		
	});
});
