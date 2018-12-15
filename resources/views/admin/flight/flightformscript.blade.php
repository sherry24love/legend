$('.form-history-back').unbind('click').bind('click', function () {
    event.preventDefault();
    history.back(1);
});

$('select').select2();

$('#appendPort').unbind('click').bind('click' , function(){
	$('#passthroug_ports').append( $('#template').text() );
	$('.date').datetimepicker({
		'format':'YYYY-MM-DD HH:mm' ,
		'locale':'zh_CN'
	});
	$('.btn-del').on('click' , function(){
		$(this).parents('.form-group').remove();
	});
});

$('.btn-del').unbind('click').bind('click' , function(){
	$(this).parents('.form-group').remove();
});

$('.date').datetimepicker({
	'format':'YYYY-MM-DD HH:mm' ,
	'locale':'zh_CN'
});

$('form').on('submit' , function(e){
	var verify = true ;
	var str = ",";
	
	//验证船名
	if( verify && !$('#ship_id').val() ) {
		toastr.error("请选择船名");
		verify = false ;
	}
	
	if( verify && !$('#no').val().trim() ) {
		toastr.error("请填写航次名称");
		verify = false ;
	}
	console.log( $('#from_port_id').val() );
	if( verify && $('#from_port_id').val() == 0 ) {
		toastr.error("请选择起运港口");
		verify = false ;
	}
	str += $('#from_port_id').val() + ',' ;
	if( verify &&  $('#to_port_id').val()  == 0 ) {
		toastr.error("请选择目的港口");
		verify = false ;
	}
	str += $('#to_port_id').val() + ',' ;
	if( verify &&  $('#to_port_id').val()  == $('#from_port_id').val() ) {
		toastr.error("起运港口和目的港口不能一样");
		verify = false ;
	}

	if( verify &&  !$('#from_port_plan_date').val().trim() ) {
		toastr.error("请选择预计离港日期");
		verify = false ;
	}

	if( verify &&  !$('#to_port_plan_date').val().trim() ) {
		toastr.error("请选择预计到港日期");
		verify = false ;
	}

	//检查途径港是不是相同

	if( verify ) {
		$('#passthroug_ports select').each(function(){
			var s = $(this).val();
			if( !s ) {
				verify = false ;
				toastr.error("请选途径港");
				return false ;
			}
			if( str.indexOf( ',' + s +',' ) >-1 ) {
				verify = false ;
				toastr.error("途径港有重复");
				return false ;
			}
			str += s + ',' ;
		});
	}


	if( verify === false ) {
		e.stopPropagation();
		e.preventDefault();
		return false ;
	}
	
});