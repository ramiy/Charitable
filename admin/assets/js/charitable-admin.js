( function($){

	var setup_charitable_ajax = function() {
		$('[data-charitable-action]').on( 'click', function( e ){
			var data 	= $(this).data( 'charitable-args' ) || {}, 
				action 	= 'charitable-' + $(this).data( 'charitable-action' );

			$.post( ajaxurl, 
				{
					'action'	: action,
					'data'		: data
				}, 
				function( response ) {
					console.log( "Response: " + response );
				} 
			);

			return false;
		} );
	};

	var setup_charitable_toggle = function() {
		$( '[data-charitable-toggle]' ).on( 'click', function( e ){
			var toggle_id 	= $(this).data( 'charitable-toggle' );

			$('#' + toggle_id).toggle();

			return false;
		} );
	};


	var setup_advanced_meta_box = function() {
		var $meta_box = $('#charitable-campaign-advanced-metabox');

		$meta_box.tabs();

		var min_height = $meta_box.find('.charitable-tabs').height();

		$meta_box.find('.ui-tabs-panel').each( function(){
			$(this).css( 'min-height', min_height );
		});
	};

	$(document).ready( function(){

		if ( $.fn.datepicker ) {

			$('.charitable-datepicker').datepicker( {
				dateFormat 	: 'DD, d MM, yy', 
				minDate 	: $(this).data('min-date') || '',
				beforeShow	: function( input, inst ) {
					$('#ui-datepicker-div').addClass('charitable-datepicker-table');
				}
			} );

			$('.charitable-datepicker').each( function(){				
				if ( $(this).data('date') ) {
					$(this).datepicker( 'setDate', $(this).data('date') );
				}

				if ( $(this).data('min-date') ) {
					$(this).datepicker( 'option', 'minDate', $(this).data('min-date') );
				}
			});
		}

		$('body.post-type-campaign .handlediv').remove();
		$('body.post-type-campaign .hndle').removeClass( 'hndle ui-sortable-handle' ).addClass( 'postbox-title' );

		setup_advanced_meta_box();

		setup_charitable_ajax();	
		setup_charitable_toggle();	
	});

})( jQuery );