$('.wd-deal').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定要为本提现记录申请提现吗!" , function( index ){
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

$('.wd-ok').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定要给本用户提现吗？请确保银行转账成功!" , function( index ){
		layer.close( index );
		layer.prompt({
			  formType: 2,
			  value: '',
			  title: '请输入打款凭证编号',
			  area: ['400px', '150px']
		}, function(value, index, elem){
			  layer.close(index);
			  $.ajax( {
				url : that.data('href' ) ,
				data:{
					_token : LA.token ,
					value:value 
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
});

$('.wd-fail').on('click' , function(){
	var that = $(this);
	layer.confirm("您确定不给本用户提现吗？请输入不提现或失败的理由!" , function( index ){
		layer.close( index );
		layer.prompt({
			  formType: 2,
			  value: '',
			  title: '请输入不成功的理由',
			  area: ['400px', '150px']
		}, function(value, index, elem){
			  layer.close(index);
			  $.ajax( {
				url : that.data('href' ) ,
				data:{
					_token : LA.token ,
					value:value 
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
});