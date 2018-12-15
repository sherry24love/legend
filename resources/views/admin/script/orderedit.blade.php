$(document).on('click' , '.p-del' , function(){
	//删除事件
	var that = $(this);
	that.parents('.item').remove();
});


$('.p-new').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.open({
		   'title':'中药饮片选择' ,
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['560px', '240px'], //宽高
			id:'workTimeLayer' ,
			content: $('.prescription-tpl').html() ,
			btn:[ '确定' ] ,
			yes:function( index , lay ){
				var id = lay.find('select').val();
				var name = lay.find('select option:selected').text();
				console.log( name );
				var num = lay.find('input').val();
				num = parseInt( num );
				num = isNaN( num ) ? 0 : num ;
				if( !num ) {
					toastr.error('请填写药片重量');
					return false ;
				}
				var html = '<span class="item" data-id="'+ id +'" data-num="'+ num +'">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;'+ num +'克<i class="fa fa-trash p-del"></i></span>';
				var find = false ;
				$('.has-many-prescription-forms .item').each(function(){
				
					if( $(this).data('id') == id ) {
						console.log( $(this).data('id') );		
						$(this).replaceWith( html );
						find = true ;
						return false ;
					}
				});
				if( false === find ) {
					$('.has-many-prescription-forms').append( html );
				}
				layer.close( index );
			} ,
			success:function(){
				$('.prescription-select').select2();
			}
	});
		
});



$('.m-new').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.open({
		   'title':'中成药选择' ,
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['560px', '240px'], //宽高
			id:'workTimeLayer' ,
			content: $('.medicine-tpl').html() ,
			btn:[ '确定' ] ,
			yes:function( index , lay ){
				var id = lay.find('select').val();
				var name = lay.find('select option:selected').text();
				console.log( name );
				var num = lay.find('input').val();
				num = parseInt( num );
				num = isNaN( num ) ? 0 : num ;
				if( !num ) {
					toastr.error('请填写药品数量');
					return false ;
				}
				var html = '<span class="item" data-id="'+ id +'" data-num="'+ num +'">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;'+ num +'克<i class="fa fa-trash p-del"></i></span>';
				var find = false ;
				$('.has-many-medicine-forms .item').each(function(){
				
					if( $(this).data('id') == id ) {
						console.log( $(this).data('id') );		
						$(this).replaceWith( html );
						find = true ;
						return false ;
					}
				});
				if( false === find ) {
					$('.has-many-medicine-forms').append( html );
				}
				layer.close( index );
			} ,
			success:function(){
				$('.medicine-select').select2();
			}
	});
		
});




$('.s-new').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.open({
		   'title':'养生方选择' ,
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['560px', '240px'], //宽高
			id:'workTimeLayer' ,
			content: $('.secrettip-tpl').html() ,
			btn:[ '确定' ] ,
			yes:function( index , lay ){
				var id = lay.find('select').val();
				var name = lay.find('select option:selected').text();
				var num = lay.find('input').val();
				num = parseInt( num );
				num = isNaN( num ) ? 0 : num ;
				if( !num ) {
					toastr.error('请填写养生方数量');
					return false ;
				}
				var html = '<span class="item" data-id="'+ id +'" data-num="'+ num +'">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;'+ num +'克<i class="fa fa-trash p-del"></i></span>';
				var find = false ;
				$('.has-many-secrettip-forms .item').each(function(){
					if( $(this).data('id') == id ) {
						$(this).replaceWith( html );
						find = true ;
						return false ;
					}
				});
				if( false === find ) {
					$('.has-many-secrettip-forms').append( html );
				}
				layer.close( index );
			} ,
			success:function(){
				$('.secrettip-select').select2();
			}
	});
		
});



$('.show-image').unbind('click' ).bind('click' , function(){
	var that = $(this);
	if( that.data('src') ) {
		layer.photos({
			'json':{
			  "title": "", //相册标题
			  "id": 123, //相册id
			  "start": 0, 
			  "data": [   //相册包含的图片，数组格式
			    {
			      "alt": "图片名",
			      "pid": 666, //图片id
			      "src": that.data('src') , //原图地址
			      "thumb": that.data('src') 
			    }
			  ]
			}
						
		});
	}
	
});

$('form').on('submit' , function(){
	console.log( 'submit' );
	var prescription = [] ;
	$('.has-many-prescription-forms .item').each(function(){
		var item = {
			'id': $(this).data('id') ,
			'num' : $(this).data('num')
		};
		prescription.push( item );
	});
	$('#prescription').val( JSON.stringify( prescription ) ) ;
	
	var medicine = [] ;
	$('.has-many-medicine-forms .item').each(function(){
		var item = {
			'id': $(this).data('id') ,
			'num' : $(this).data('num')
		};
		medicine.push( item );
	});
	$('#medicine').val( JSON.stringify( medicine ) ) ;
	
	var secrettip = [] ;
	$('.has-many-secrettip-forms .item').each(function(){
		var item = {
			'id': $(this).data('id') ,
			'num' : $(this).data('num')
		};
		secrettip.push( item );
	});
	$('#secrettip').val( JSON.stringify( secrettip ) ) ;
	
});


$('.btn-check').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.confirm("您确定要通过当前订单的审核吗？" , function( index ){
			layer.close( index );
			$.post( that.data('href') , {'_method':'put' , '_token':LA.token } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});



$('.btn-offpay').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.confirm("在您点击确认线下收款前，请认真确认是否已经收到款项了？" , function( index ){
			layer.close( index );
			$.post( that.data('href') , {'_method':'put' , '_token':LA.token } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});



$('.btn-recivepay').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.confirm("您正将此订单改为货到付款，请确保有与用户确认付款方式？" , function( index ){
			layer.close( index );
			$.post( that.data('href') , {'_method':'put' , '_token':LA.token } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});




$('.btn-send').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.prompt({
		  formType: 2,
		  value: '',
		  title: '请输入运单编号',
		  area: ['400px', '50px'] //自定义文本域宽高
		}, function(value, index, elem){
		  	layer.close(index);
		  	if( !value ) {
		  		toastr.error("请输入运单号码");
		  		return false ;
		  	}
		  	$.post( that.data('href') , {'_method':'put' , '_token':LA.token , 'ship_no' : value } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});
