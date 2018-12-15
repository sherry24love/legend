$('.menu-input input').on('click' , function(){
	var that = $(this);
	var parentId = $(that).data('parent_id');
	if( $(this).prop('checked') ) {
		
		if( $('input[data-id="'+ parentId +'"]').length > 0 ) {
			var p2 = $('input[data-id="'+ parentId +'"]') ;
			$( p2 ).prop('checked' , true ) ;
			var p2_parentId = $(p2).data('parent_id');
			if( $('input[data-id="'+ p2_parentId +'"]').length > 0 ) {
				var p2 = $('input[data-id="'+ p2_parentId +'"]') ;
				$( p2 ).prop('checked' , true ) ;
			}
		}
	
		$(that).next().find('input').prop('checked' , true );
	} else {
		$(that).next().find('input').prop('checked' , false );
	}
	
	
});

@if( isset( $menus ) && $menus )

var menus = {!! json_encode( $menus ) !!} ;

for( var i in menus ) {
	$('input[data-id="' + menus[i] + '"]').prop('checked' , true );
	var that = $('input[data-id="' + menus[i] + '"]') ;
	var parentId = $(that).data('parent_id');
	if( $('input[data-id="'+ parentId +'"]').length > 0 ) {
		var p2 = $('input[data-id="'+ parentId +'"]') ;
		$( p2 ).prop('checked' , true ) ;
		var p2_parentId = $(p2).data('parent_id');
		if( $('input[data-id="'+ p2_parentId +'"]').length > 0 ) {
			var p2 = $('input[data-id="'+ p2_parentId +'"]') ;
			$( p2 ).prop('checked' , true ) ;
		}
	}
}


@endif