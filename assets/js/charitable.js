jQuery.noConflict();

CHARITABLE = {};

CHARITABLE.Toggle = {

	toggleTarget : function( event ) {
		var target = jQuery( this ).data( 'charitable-toggle' );

		jQuery( '#' + target ).toggleClass( 'charitable-hidden', jQuery( this ).is( ':checked' ) );

		if ( jQuery(this).is( 'a' ) ) {
			return false;
		}			
	}, 

	hideTarget : function( el ) {
		var target = jQuery( el ).data( 'charitable-toggle' );

		jQuery( '#' + target ).addClass( 'charitable-hidden' );
	},

	init : function() {
		var self = this;
		jQuery( '[data-charitable-toggle]' ).each( function() { 
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
		var input = jQuery( this ).find( 'input[type=radio]' ), 
			checked = ! input.attr( 'checked' );

		input.attr( 'checked', checked ); 

		jQuery( '.donation-amount.selected ').removeClass( 'selected' );
		jQuery( this ).addClass( 'selected' );

		if ( jQuery( this ).hasClass( 'custom-donation-amount' ) ) {				
			jQuery( this ).siblings( 'input[name=custom-donation-amount]' ).focus();
		}
	},
	
	init : function() {
		jQuery( '.donation-amount input:checked' ).each( function(){
			jQuery( this ).parent().addClass( 'selected' );
		});

		jQuery( '.donation-amount' ).on ( 'click', function() {
			this.DonationSelection.selectOption();
		});
	}
};

/**
 * AJAX donation
 */
CHARITABLE.AJAXDonate = {

	onClick : function( event ) {
 		var data = jQuery( event.target.form ).serializeArray().reduce( function( obj, item ) {
		    obj[ item.name ] = item.value;
		    return obj;
		}, {} );	 		

 		/* Cancel the default Charitable action, but pass it along as the form_action variable */	 	
 		data.action = 'add_donation';
 		data.form_action = data.charitable_action;			
		delete data.charitable_action;

		jQuery.ajax({
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
		jQuery( '[data-charitable-ajax-donate]' ).on ( 'click', function() {
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

(function() {
	jQuery( document ).ready( function() {
		CHARITABLE.Toggle.init();

		CHARITABLE.DonationSelection.init();

		CHARITABLE.AJAXDonate.init();
	});
})();

console.log( CHARITABLE );