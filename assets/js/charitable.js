var $ = jQuery;

CHARITABLE = {};

CHARITABLE.Toggle = {

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
		var self = this;
		$( '[data-charitable-toggle]' ).each( function() { 
			self.hideTarget( this ); 
		} )  
		.on( 'click', function( event ) {
			self.Toggle.toggleTarget( event ) 
		} );
	}
};

/**
 * Donation amount selection
 */
CHARITABLE.DonationSelection = {

	selectOption : function( event ) {
		var input = $( this ).find( 'input[type=radio]' ), 
			checked = ! input.attr( 'checked' );

		input.attr( 'checked', checked ); 

		$( '.donation-amount.selected ').removeClass( 'selected' );
		$( this ).addClass( 'selected' );

		if ( $( this ).hasClass( 'custom-donation-amount' ) ) {				
			$( this ).siblings( 'input[name=custom-donation-amount]' ).focus();
		}
	},
	
	init : function() {
		$( '.donation-amount input:checked' ).each( function(){
			$( this ).parent().addClass( 'selected' );
		});

		$( '.donation-amount' ).on ( 'click', function() {
			this.DonationSelection.selectOption();
		});
	}
};

/**
 * AJAX donation
 */
CHARITABLE.AJAXDonate = {

	onClick : function( event ) {
 		var data = $( event.target.form ).serializeArray().reduce( function( obj, item ) {
		    obj[ item.name ] = item.value;
		    return obj;
		}, {} );	 		

 		/* Cancel the default Charitable action, but pass it along as the form_action variable */	 	
 		data.action = 'add_donation';
 		data.form_action = data.charitable_action;			
		delete data.charitable_action;

		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: CHARITABLE_VARS.ajaxurl,
			xhrFields: {
				withCredentials: true
			},
			success: function (response) {
			}
		}).fail(function (response) {
			if ( window.console && window.console.log ) {
				console.log( response );
			}
		}).done(function (response) {

		});

		return false;
	},

	init : function() {
		$( '[data-charitable-ajax-donate]' ).on ( 'click', function() {
			this.AJAXDonate.onClick() 
		});
	}
};

/**
 * URL sanitization
 */
CHARITABLE.SanitizeURL = function(input) {
	var url = input.value.toLowerCase();

	if ( !/^https?:\/\//i.test( url ) ) {
	    url = 'http://' + url;

	    input.value = url;
	}
};

(function($, CHARITABLE) {
	$( document ).ready( function() {
		CHARITABLE.Toggle.init();

		CHARITABLE.DonationSelection.init();

		CHARITABLE.AJAXDonate.init();
	});
})(jQuery, CHARITABLE);

console.log( CHARITABLE );