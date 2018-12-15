var select = $('input[name="limit_date_type"]:checked').val();
if( 1 == select ) {
	$('.limit_date_end').parents('.form-group').show();
	$('.limit_max_day').parents('.form-group').hide();
}

if( 2 == select ) {
	$('.limit_max_day').parents('.form-group').show();
	$('.limit_date_end').parents('.form-group').hide();
}

$('input[name="limit_date_type"]').on('change' , function( data ){
	console.log( $(this).val() );
	console.log( data );
});

$('input[name="limit_date_type"]').on('ifChecked', function(event){
	select = event.target.defaultValue ;
	if( 1 == select ) {
		$('.limit_date_end').parents('.form-group').show();
		$('.limit_max_day').parents('.form-group').hide();
	}

	if( 2 == select ) {
		$('.limit_max_day').parents('.form-group').show();
		$('.limit_date_end').parents('.form-group').hide();
	}
		
});