$('.allow').click( function(){
	var that = $(this);
	layer.confirm("您确定要同意本订单的信息变更吗，在同意前请确认信息无误!" , function( index ){
		layer.close( index );
		$.ajax( {
			url : that.data('href' ) ,
			data:{
				_method:'PUT' ,
				_token : LA.token 
			} ,
			dataType:'json' ,
			method:'post' ,
			success:function( data ){
				$.pjax.reload('#pjax-container');
				if( data.errcode === 0 ) {
					toastr.success( data.msg );
				} else {
					toastr.error( data.msg );
				}
			}
		});
		
	});

});


$('.disallow').click(function(){
	var that = $(this);
	layer.confirm("您确定要不通过本订单的信息变更吗？" , function( index ){
		layer.close( index );
		$.ajax( {
			url : that.data('href' ) ,
			data:{
				_method:'PUT' ,
				_token : LA.token 
			} ,
			dataType:'json' ,
			method:'post' ,
			success:function( data ){
				$.pjax.reload('#pjax-container');
				if( data.errcode === 0 ) {
					toastr.success( data.msg );
				} else {
					toastr.error( data.msg );
				}
			}
		});
		
	});

});