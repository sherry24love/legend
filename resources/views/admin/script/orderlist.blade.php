//$.pjax({url: '{}', data:{ id:data.data.id }, container: '#pjax-container'})
$('.order-deal').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定要处理这个订单吗？接单后其他客服就不能处理这个订单了!" , function( index ){
		layer.close( index );
		$.getJSON( that.data('href' ) , { } , function( data ){
			$.pjax.reload('#pjax-container');
			if( data.errcode === 0 ) {
				toastr.success( data.msg );
			} else {
				toastr.error( data.msg );
			}
		});
		
	});
});

//发货
$('.order-ok').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定要修改本订单为出货状态吗？更改后就不能变更了!" , function( index ){
		layer.close( index );
		$.ajax( {
			url : that.data('href' ) ,
			data:{
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


//发货
$('.order-take').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定本订单收款了吗，修改后就不能变更了!" , function( index ){
		layer.close( index );
		$.ajax( {
			url : that.data('href' ) ,
			data:{
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

$('.order-tracedone').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.confirm("您确定本订单追踪完成了吗？点击确定后将不可再编辑了!" , function( index ){
		layer.close( index );
		$.ajax( {
			url : that.data('href' ) ,
			data:{
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


$('.order-fail').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定要作废这个订单吗？作废后订单就不可以操作了，请谨慎处理!" , function( index ){
		layer.close( index );
		$.ajax( {
			url : that.data('href' ) ,
			data:{
				_method:'delete' ,
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

$('.order-sendconfirm').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定要发送短信通知客户吗？请确认已经填写运单号码!" , function( index ){
		layer.close( index );
		$.ajax( {
			url : that.data('href' ) ,
			data:{
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
$('.order-back').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定要让用户重新修改订单信息吗!" , function( index ){
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

