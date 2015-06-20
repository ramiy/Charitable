jQuery.noConflict();

CHARITABLE = {};

CHARITABLE.Toggle = {

	toggleTarget : function( $el ) {
		var target = $el.data( 'charitable-toggle' );

		jQuery( '#' + target ).toggleClass( 'charitable-hidden', $el.is( ':checked' ) );

		return false;
	}, 

	hideTarget : function( $el ) {
		var target = $el.data( 'charitable-toggle' );

		jQuery( '#' + target ).addClass( 'charitable-hidden' );
	},

	init : function() {
		var self = this;		
		jQuery( '[data-charitable-toggle]' ).each( function() { 
			return self.hideTarget( jQuery( this ) ); 
		} )  
		.on( 'click', function( event ) {
			return self.toggleTarget( jQuery( this ) ); 
		} );
	}
};

/**
 * Donation amount selection
 */
CHARITABLE.DonationSelection = {

	selectOption : function( $el ) {
		var $input = $el.find( 'input[type=radio]' ), 
			checked = ! $input.attr( 'checked' );

		$input.attr( 'checked', checked ); 

		jQuery( '.donation-amount.selected ').removeClass( 'selected' );
		$el.addClass( 'selected' );

		if ( $el.hasClass( 'custom-donation-amount' ) ) {				
			$el.siblings( 'input[name=custom-donation-amount]' ).focus();
		}
	},
	
	init : function() {
		var self = this;
		jQuery( '.donation-amount input:checked' ).each( function(){
			jQuery( this ).parent().addClass( 'selected' );
		});

		jQuery( '.donation-amount' ).on ( 'click', function() {
			self.selectOption( jQuery(this) );
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
		var self = this;
		jQuery( '[data-charitable-ajax-donate]' ).on ( 'click', function( event ) {
			return self.onClick( event );
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