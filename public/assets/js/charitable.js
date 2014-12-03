var CHARITABLE = ( function( $ ){

	/**
	 * Toggle handler
	 */
	var Toggle = {

		toggleTarget : function( event ) {
			var target = $( this ).data( 'charitable-toggle' );

			$( '#' + target ).toggleClass( 'charitable-hidden' );

			if ( $(this).is( 'a' ) ) {
				return false;
			}			
		}, 

		hideTarget : function( el ) {
			var target = $( el ).data( 'charitable-toggle' );

			$( '#' + target ).addClass( 'charitable-hidden' );
		},

		init : function() {
			$( '[data-charitable-toggle]' ).each( function() { 
				Toggle.hideTarget( this ); 
			} )  
			.on( 'click', Toggle.toggleTarget );
		}
	};

	/**
	 * Donation amount selection
	 */
	var Donation_Selection = {

		selectOption : function( event ) {
			var input = $( this ).find( 'input' ), 
				checked = ! input.attr( 'checked' );

			input.attr( 'checked', checked ); 

			$( '.donation-amount.selected ').removeClass( 'selected' );
			$( this ).addClass( 'selected' );
		},

		hideInputs : function( el ) {
			$( el ).find( 'input' ).hide();
		},

		init : function() {
			$( '.donation-amount' ).each( function() {
				Donation_Selection.hideInputs( this );
			} )
			.on ( 'click', Donation_Selection.selectOption );
		}
	};

	$( document ).ready( function() {
		Toggle.init();

		Donation_Selection.init();
	} );

	/**
	 * Public API of the CHARITABLE object.
	 */
	return {};

} )( jQuery );