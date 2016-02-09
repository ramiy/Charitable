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
        var $li = $el.closest('li');

        // already selected, quit early to prevent focus/change loop
        if( $li.hasClass( 'selected' ) ){
            return false; 
        }

        var $form = $el.closest('.charitable-form');

        $form.find('.donation-amount.selected').removeClass( 'selected' );
        $li.addClass( 'selected' );

        if ( $li.hasClass( 'custom-donation-amount' ) ) {
            $li.find( 'input.custom-donation-input' ).focus();
        }

        return false;
    },
    
    init : function() {
        var self = this;

        jQuery( '.donation-amount input:checked' ).each( function() {
            jQuery( this ).closest('li').addClass( 'selected' );
        });

        jQuery( '.charitable-form' ).on( 'click', '.donation-amount', function( event ) {
            self.selectOption( jQuery(this) );
        });

        jQuery( '.charitable-form' ).on( 'focus', 'input.custom-donation-input', function( event ) {
            jQuery(this).closest('li').find('input[name=donation_amount]').prop('checked', true).trigger('change');
        });
    }
};

/**
 * AJAX donation
 */
CHARITABLE.AJAXDonate = {

    onClick : function( form ) {
        var $form = jQuery( form );
        var data = $form.serializeArray().reduce( function( obj, item ) {
            obj[ item.name ] = item.value;
            return obj;
        }, {} );
        var coordinates = $form.position();
        var $modal = $form.parent( '#charitable-donation-form-modal' );

        $form.find( '.charitable-form-processing' ).show();        

        /* Cancel the default Charitable action, but pass it along as the form_action variable */       
        data.action = 'make_donation';
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
                if ( response.success ) {
                    window.location.href = response.redirect_to;
                }

                $form.find( '.charitable-form-processing' ).hide();

                if ( $form.find( '.charitable-form-errors').length ) {
                    $form.find( '.charitable-form-errors' ).remove(); 
                }
                
                $form.prepend( response.errors );    
                
                if ( $modal.length ) {
                    $modal.scrollTop( 0 );
                }
                else {
                    window.scrollTo( coordinates.left, coordinates.top );
                }                
            }
        }).fail(function (response, textStatus, errorThrown) {
            if ( window.console && window.console.log ) {
                console.log( response );
            }

            window.scrollTo( coordinates.left, coordinates.top );

        }).done(function (response) {

        });

        return false;
    },

    init : function() {
        var self = this;
        jQuery( 'body' ).on ( 'submit', '#charitable-donation-form[data-use-ajax=1]', function( event ) {                        
            return self.onClick( this );
        });
    }
};

/**
 * URL sanitization
 */
CHARITABLE.SanitizeURL = function(input) {
    var url = input.value.toLowerCase();

    if ( !/^https?:\/\//i.test( url ) && url.length > 0 ) {
        url = 'http://' + url;

        input.value = url;
    }
};

/**
 * Set up Lean Modal
 */
CHARITABLE.Modal = {
    init : function() {
        if ( jQuery.fn.leanModal ) {
            jQuery('[data-trigger-modal]').leanModal({
                closeButton : ".modal-close"
            });
        }
    }
};

/**
 * Payment method selection
 */
 CHARITABLE.PaymentMethodSelection = {

    loaded : false,

    getActiveMethod : function( $el ) {
        return jQuery( '#charitable-gateway-selector input[name=gateway]:checked' ).val();
    },

    hideInactiveMethods : function( active ) {
        var active = active || this.getActiveMethod();

        jQuery( '#charitable-gateway-fields .charitable-gateway-fields[data-gateway!=' + active + ']' ).hide();
    },

    showActiveMethod : function( active ) {
        jQuery( '#charitable-gateway-fields .charitable-gateway-fields[data-gateway=' + active + ']' ).show();
    },

    init : function() {
        var self = this, 
            $selector = jQuery( '#charitable-gateway-selector input[name=gateway]' );        

        /* If there is only one gateway, we don't need to do anything else. */
        if ( 0 === $selector.length ) {
            return;
        }

        self.hideInactiveMethods();

        if ( self.loaded ) {
            return;
        }

        jQuery( 'body' ).on( 'change', '#charitable-gateway-selector input[name=gateway]', function() {
            self.hideInactiveMethods();
            self.showActiveMethod( jQuery(this).val() );
        });

        self.loaded = true;
    }
 };


/**
 * Donation amount selection
 */
CHARITABLE.Accounting = {

    format_currency : function( price, currency_symbol ){

        if ( typeof currency_symbol === 'undefined' )
            currency_symbol = '';

        return accounting.formatMoney( price, {
                symbol : currency_symbol,
                decimal : CHARITABLE_VARS.currency_format_decimal_sep,
                thousand: CHARITABLE_VARS.currency_format_thousand_sep,
                precision : CHARITABLE_VARS.currency_format_num_decimals,
                format: CHARITABLE_VARS.currency_format  
        }).trim();

    },

    unformat_currency : function( price ){
        return Math.abs( parseFloat( accounting.unformat( price, CHARITABLE_VARS.currency_format_decimal_sep ) ) );
    },
    
    init : function() {
        var self = this;

        jQuery( 'body' ).on( 'blur', '.custom-donation-input', function( event ) {
            var value_now = self.unformat_currency( jQuery( this ).val() );
            if ( jQuery.trim( value_now ) > 0 ) {
                var formatted_total = self.format_currency( value_now );
                jQuery( this ).val( formatted_total );
            }
        });
    }
};

(function() {
    jQuery( document ).ready( function() {
        CHARITABLE.Toggle.init();

        CHARITABLE.DonationSelection.init();
        
        CHARITABLE.AJAXDonate.init();       

        CHARITABLE.PaymentMethodSelection.init();

        CHARITABLE.Modal.init();

        CHARITABLE.Accounting.init();
    });
})();