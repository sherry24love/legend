function recalc() {
	$('.table-price tbody tr').each(function( i ){
		var that = $(this);
		console.log( i );
		if( $(that).find('input') ) {
			$(that).find('select').eq(0).attr('name');
		}
	});
}

function del() {
	$('.btn-del').unbind('click').bind('click' , function(){
		var that = $(this);
		$(that).parents('tr').next().remove();
		$(that).parents('tr').remove();
		//重计算index
		recalc();
	});
}
		
function checkfill() {
	return false ;
	var last = $('.table-price tbody tr:odd:last');
	if( last.find('select').eq(0).val() == 0 ) {
		toastr.error('请选择驳船起运港，如果没有则选择与大船起运一致的港口');
		return true ;
	}
	if( last.find('select').eq(1).val() == 0 ) {
		toastr.error('请选择大船起运港');
		return true ;
	}
	if( last.find('select').eq(2).val() == 0 ) {
		toastr.error('请选择大船起运港');
		return true ;
	}
	if( last.find('select').eq(3).val() == 0 ) {
		toastr.error('请选择驳船目的港，如果没有则选择与大船目的一致的港口');
		return true ;
	}
	var has = false ;
	$('.table-price tbody tr:odd').each(function( i ){
		console.log( $(this) ) ;
		var that = $(this);
		if( $('.table-price tbody tr').length > 3 ) {
			if( $(that).find('input').length ) {
				if( that.find('select').eq(0).val() == last.find('select').eq(0).val() && that.find('select').eq(1).val() == last.find('select').eq(1).val() && that.find('select').eq(2).val() == last.find('select').eq(2).val() && that.find('select').eq(3).val() == last.find('select').eq(3).val() ) {
					//has = true ;
					toastr.error("本条航线价格已经存在");
					return false ;
				}
			}
		}
	});
	console.log( has );
	return has ;
}

		
$(document).on('submit' , 'form' , function(){
	var has = true ;
	$('.table-price tbody tr').each(function( i ){
		var that = $(this);
		if( $(that).find('input').length ) {
			if( that.find('select').eq(0).val() == 0 && that.find('select').eq(1).val() == 0 && that.find('select').eq(2).val() == 0 && that.find('select').eq(3).val() == 0 ) {
				//如果同时为0 则移除
				has = true ;
				that.remove();
			} else {
				//如果不同时为0 则考虑要提示用户
			}
		}
	});
});
$('select').select2();

$('.inputdate').datetimepicker({
	format:'YYYY-MM-DD' ,
	locale:'zh_CN'
});

del();

$('.btn-add').unbind('click').bind('click' , function(){
	var l = $('.table-price tbody tr:odd').length ;
	//检查上一个点是不是填完整了 且没有重复
	if( checkfill() ) {
		return false ;
	}
	$('.table-price tbody').append( $('#template-tr').val() );
	$('select').select2();

	$('.inputdate').datetimepicker({
		format:'YYYY-MM-DD' ,
		locale:'zh_CN'
	});
	del();

	recalc();
});
		
