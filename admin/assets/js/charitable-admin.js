( function($){

	var setup_advanced_meta_box = function() {
		var $meta_box = $('#charitable-campaign-advanced-metabox');

		$meta_box.tabs();

		var min_height = $meta_box.find('.charitable-tabs').height();

		$meta_box.find('.ui-tabs-panel').each( function(){
			$(this).css( 'min-height', min_height );
		});
	}

	$(document).ready( function(){

		if ( $.fn.datepicker ) {
			$('.charitable-datepicker').datepicker( {
				dateFormat 	: 'DD, d MM, yy', 
				beforeShow	: function( input, inst ) {
					$('#ui-datepicker-div').addClass('charitable-datepicker-table');
				}
			} );

			$('.charitable-datepicker').each( function(){
				if ( $(this).data('date') ) {
					$(this).datepicker( 'setDate', $(this).data('date') );
				}
			});
		}

		$('body.post-type-campaign .handlediv').remove();
		$('body.post-type-campaign .hndle').removeClass( 'hndle ui-sortable-handle' ).addClass( 'postbox-title' );

		setup_advanced_meta_box();
	});

})( jQuery );