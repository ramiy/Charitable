var CHARITABLE = ( function( $ ){

	/**
	 * Toggle handler
	 */
	var Toggle = {

		toggleTarget : function( event ) {
			var target = $( this ).data( 'charitable-toggle' );

			$( '#' + target ).toggleClass( 'charitable-hidden', $( this ).is( ':checked' ) );

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
			var input = $( this ).find( 'input[type=radio]' ), 
				checked = ! input.attr( 'checked' );

			input.attr( 'checked', checked ); 

			$( '.donation-amount.selected ').removeClass( 'selected' );
			$( this ).addClass( 'selected' );

			if ( false === $( this ).hasClass( 'custom-donation-amount' ) ) {
				// $( '#custom-donation-amount-field' ).addClass( 'charitable-hidden' );
			}
			else {
				$( this ).siblings( 'input[name=custom-donation-amount]' ).focus();
			}
		},
		
		init : function() {
			$( '.donation-amount' ).on ( 'click', Donation_Selection.selectOption );
		}
	};

	/**
	 * AJAX donation
	 */
	var AJAX_Donate = {

		onClick : function( event ) {
	 		var data = $( event.target.form ).serializeArray().reduce( function( obj, item ) {
			    obj[ item.name ] = item.value;
			    return obj;
			}, {} );	 		

			data.action = 'add_donation';

			$.ajax({
				type: "POST",
				data: data,
				dataType: "json",
				url: CHARITABLE_VARS.ajaxurl,
				xhrFields: {
					withCredentials: true
				},
				success: function (response) {
					console.log( response );
				}
			}).fail(function (response) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			}).done(function (response) {

			});

       		// $.post( CHARITABLE.ajaxurl, data, function( response ) {
	            
	        //     console.log( response );

	        // });

			return false;
		},

		init : function() {
			$( '[data-charitable-ajax-donate]' ).on ( 'click', AJAX_Donate.onClick );
		}
	}


	$( document ).ready( function() {
		Toggle.init();

		Donation_Selection.init();

		AJAX_Donate.init();
	} );

	/**
	 * Public API of the CHARITABLE object.
	 */
	return {};

} )( jQuery );