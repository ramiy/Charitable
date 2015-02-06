( function($){

	$(document).ready( function(){

		if ( $.fn.datepicker ) {
			$('.datepicker').datepicker( {
				dateFormat 	: 'DD, d MM, yy'
			} );

			$('.datepicker').each( function(){
				if ( $(this).data('date') ) {
					$(this).datepicker( 'setDate', $(this).data('date') );
				}
			});
		}

		$('body.post-type-campaign .handlediv').remove();
		$('body.post-type-campaign .hndle').removeClass( 'hndle ui-sortable-handle' ).addClass( 'postbox-title' );
	});

})( jQuery );