CHARITABLE = window.CHARITABLE || {};

/**
 * Set up Donation_Form object.
 */
( function( exports, $ ){

    /**
     * Donation_Form expects a jQuery this.form object.
     */
    var Donation_Form = function( form ) {
        this.errors = [];
        this.form = form;

        var self = this;
        var $body = $( 'body' );

        /**
         * Focus event handler on custom donation amount input field.
         * 
         * @access  private
         */
        var on_focus_custom_amount = function() {
            
            $( this ).closest( 'li' ).find( 'input[name=donation_amount]' ).prop( 'checked', true ).trigger( 'change' );

            $body.off( 'focus', 'input.custom-donation-input', on_focus_custom_amount );

            $( this ).focus();
            
            $body.on( 'focus', 'input.custom-donation-input', on_focus_custom_amount );
        
        };

        /**
         * Focus event handler for changes to custom donation amount input field.
         * 
         * @access  private
         */
        var on_change_custom_donation_amount = function() {

            var unformatted = self.unformat_amount( $( this ).val() );

            if ( $.trim( unformatted ) > 0 ) {
                $( this ).val( self.format_amount( unformatted ) );
            }

        };

        /**
         * Select a donation amount.
         *
         * @return  void
         */
        var on_select_donation_amount = function() {

            var $li = $( this ).closest( 'li' );

            // Already selected, quit early to prevent focus/change loop
            if ( $li.hasClass( 'selected' ) ) {
                return;
            }

            $li.parents( '#charitable-donation-form' ).find( '.donation-amount.selected' ).removeClass( 'selected' );
            
            $li.addClass( 'selected' );

            if ( $li.hasClass( 'custom-donation-amount' ) ) {
                $li.find( 'input.custom-donation-input' ).focus();
            }
        };

        /**
         * Change event handler for payment gateway selector.
         * 
         * @access  private
         */
        var on_change_payment_gateway = function() {
        
            self.hide_inactive_payment_methods();

            self.show_active_payment_methods( $(this).val() );
        
        };

        /**
         * Flag to prevent the on_submit handler from sending multiple concurrent AJAX requests
         *
         * @access  private
         */
        var submit_processing = false;

        /**
         * Submit event handler for donation form.
         * 
         * @access  private
         */
        var on_submit = function() {
            if ( submit_processing ) {
                return false;
            }

            submit_processing = true;

            var $form = $( this );
            var data = $form.serializeArray().reduce( function( obj, item ) {
                obj[ item.name ] = item.value;
                return obj;
            }, {} );
            var coordinates = $form.position();
            var $modal = $form.parents( '.charitable-modal' );
            var $spinner = $form.find( '.charitable-form-processing' );
            var $donate_btn = $form.find( 'button[name="donate"]' );

            $donate_btn.hide();
            $spinner.show();

            /* Cancel the default Charitable action, but pass it along as the form_action variable */       
            data.action = 'make_donation';
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

                    if ( response.success ) {
                        window.location.href = response.redirect_to;
                    }
                    else {
                        $donate_btn.show();
                        $spinner.hide();

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
                }
            }).fail(function (response, textStatus, errorThrown) {
                if ( window.console && window.console.log ) {
                    console.log( response );
                }

                $donate_btn.show();
                $spinner.hide();

                window.scrollTo( coordinates.left, coordinates.top );

            }).always(function (response) {
                submit_processing = false;
            });

            return false;
        
        };

        /**
         * Initialization that should happen for every new form object.
         *
         * @return  void
         */
        var init_each = function() {

            self.form.find( '.donation-amount input:checked' ).each( function() {
                $( this ).closest( 'li' ).addClass( 'selected' );
            });

            if ( self.get_all_payment_methods().length ) {
                self.hide_inactive_payment_methods();
                self.form.on( 'change', '#charitable-gateway-selector input[name=gateway]', on_change_payment_gateway );
            }
        }

        /**
         * Set up event handlers for donation forms.
         *
         * Note: This function is only ever designed to run once.
         *
         * @return  void
         */
        var init = function() {

            // Init donation amount selection
            $body.on( 'click', '.donation-amount', on_select_donation_amount );
            $body.on( 'focus', 'input.custom-donation-input', on_focus_custom_amount );

            // Init currency formatting        
            $body.on( 'blur', '.custom-donation-input', on_change_custom_donation_amount );

            // Handle donation form submission            
            if ( 1 === self.form.data( 'use-ajax' ) ) {
                $body.on( 'submit', '#charitable-donation-form', on_submit );
            }

            CHARITABLE.forms_initialized = true;
        }

        init_each();

        if ( false === CHARITABLE.forms_initialized ) {
            init();
        } 
    };

    /**
     * Return the submitted email address.
     *
     * @return  string
     */
    Donation_Form.prototype.get_email = function() {
        return this.form.find( '[name=email]' ).val();
    };

    /**
     * Get the submitted amount, taking into account both the custom & suggested donation fields.
     *
     * @return  string
     */
    Donation_Form.prototype.get_amount = function() {
        var amount = suggested = parseFloat( this.form.find( '[name=donation_amount]:checked' ).val() );

        if ( isNaN( suggested ) ) {
            amount = parseFloat( this.form.find( '[name=custom_donation_amount]' ).val() );
        }

        if ( isNaN( amount ) || amount <= 0 ) {
            amount = 0;
        }

        return amount;
    };

    /**
     * Get a description of the donation.
     *
     * @return  string
     */
    Donation_Form.prototype.get_description = function() {
        return this.form.find( '[name=description]' ).val() || '';
    };

    /**
     * Get credit card number.
     *
     * @return  string
     */
    Donation_Form.prototype.get_cc_number = function() {
        return this.form.find( '#charitable_field_cc_number input' ).val() || '';
    };

    /**
     * Get credit card CVC number.
     *
     * @return  string
     */
    Donation_Form.prototype.get_cc_cvc = function() {
        return this.form.find( '#charitable_field_cc_cvc input' ).val() || '';
    };

    /**
     * Get credit card expiry month.
     *
     * @return  string
     */
    Donation_Form.prototype.get_cc_expiry_month = function() {
        return this.form.find( '#charitable_field_cc_expiration select.month' ).val() || '';
    };

    /**
     * Get credit card expiry year.
     *
     * @return  string
     */
    Donation_Form.prototype.get_cc_expiry_year = function() {
        return this.form.find( '#charitable_field_cc_expiration select.year' ).val() || '';
    };

    /**
     * Clear credit card fields. 
     *
     * This is used by gateways that create tokens through Javascript (such as Stripe), to 
     * avoid credit card details hitting the server.
     *
     * @return  void
     */
    Donation_Form.prototype.clear_cc_fields = function() {
        this.form.find( '#charitable_field_cc_number input, #charitable_field_cc_name input, #charitable_field_cc_cvc input, #charitable_field_cc_expiration select' ).removeAttr( 'name' );
    };

    /**
     * Return the selected payment method.
     *
     * @return  string
     */
    Donation_Form.prototype.get_payment_method = function() {
        return this.form.find( '[type=hidden][name=gateway], [name=gateway]:checked' ).val() || '';
    };

    /**
     * Return all payment methods.
     *
     * @return  object
     */
    Donation_Form.prototype.get_all_payment_methods = function() {
        return this.form.find( '#charitable-gateway-selector input[name=gateway]' );
    }

    /**
     * Hide inactive payment methods.
     *
     * @return  void
     */
    Donation_Form.prototype.hide_inactive_payment_methods = function() {
        var active = this.get_payment_method();

        this.form.find( '.charitable-gateway-fields[data-gateway!=' + active + ']' ).hide();
    };

    /**
     * Show active payment methods.
     *
     * @return  void
     */
    Donation_Form.prototype.show_active_payment_methods = function( active ) {
        var active = active || this.get_payment_method();

        this.form.find( '.charitable-gateway-fields[data-gateway=' + active + ']' ).show();
    };    

    /**
     * Select a donation amount.
     *
     * @param   int price
     * @param   string symbol
     * @return  string
     */
    Donation_Form.prototype.format_amount = function( price, symbol ){
        if ( typeof symbol === 'undefined' )
            symbol = '';

        return accounting.formatMoney( price, {
                symbol : symbol,
                decimal : CHARITABLE_VARS.currency_format_decimal_sep,
                thousand: CHARITABLE_VARS.currency_format_thousand_sep,
                precision : CHARITABLE_VARS.currency_format_num_decimals,
                format: CHARITABLE_VARS.currency_format  
        }).trim();

    };

    /**
     * Select a donation amount.
     *
     * @param   int price
     * @return  string
     */
    Donation_Form.prototype.unformat_amount = function( price ) {
        return Math.abs( parseFloat( accounting.unformat( price, CHARITABLE_VARS.currency_format_decimal_sep ) ) );
    };

    /**
     * Add an error message.
     *
     * @param   string message
     * @return  void
     */
    Donation_Form.prototype.add_error = function( message ) {
        this.errors.push( message );
    };

    /**
     * Return the errors.
     *
     * @return  []
     */
    Donation_Form.prototype.get_errors = function() {
        return this.errors;
    };

    /**
     * Prints out a nice string describing the errors.
     * 
     * @return  string
     */
    Donation_Form.prototype.get_error_message = function() {
        return this.errors.join( ' ' );
    };

    /**
     * Make sure that the submitted amount is valid.
     *
     * @return  boolean
     */
    Donation_Form.prototype.is_valid_amount = function() {
        return this.get_amount() > 0;
    };

    /**
     * Verifies the submission and returns true if it all looks ok.
     *
     * @param   this.form The submitted form.
     * @return  boolean
     */
    Donation_Form.prototype.validate = function() {
        var valid = true;
    
        if ( false === this.is_valid_amount() ) {
            valid = false;
            this.add_error( CHARITABLE_VARS.error_invalid_amount );
        }

        return valid;
    };

    exports.Donation_Form = Donation_Form;

})( CHARITABLE, jQuery );

/**
 * Set up Toggle object.
 */
( function( exports, $ ){

    var Toggle = function() {

        /**
         * Hide toggle target.
         */
        var hide_target = function() {
            $( '#' + $(this).data('charitable-toggle') ).addClass( 'charitable-hidden' );
        };

        /**
         * Toggle event handler for any fields with the [data-charitable-toggle] attribute.
         *
         * @access  private
         */
        var on_toggle = function() {

            var $this = $( this ),
                target = $this.data( 'charitable-toggle' );

            $( '#' + target ).toggleClass( 'charitable-hidden', $this.is( ':checked' ) );

            return false;

        };

        // Initialization only required once.
        $( 'body' ).on( 'click', '[data-charitable-toggle]', on_toggle );

        // Initialization that will be performed everytime
        return function() {    
            $( '[data-charitable-toggle]' ).each( hide_target );
        }
    }    

    exports.Toggle = Toggle();     

})( CHARITABLE, jQuery );

/**
 * Set up Charitable helper functions.
 */
( function( exports, $ ) {

    var Helpers = function() {

        /**
         * Sanitize URLs.
         */
        this.sanitize_url = function( input ) {
            
            var url = input.value.toLowerCase();

            if ( !/^https?:\/\//i.test( url ) && url.length > 0 ) {
                url = 'http://' + url;

                input.value = url;
            }
        }

    };

})( CHARITABLE, jQuery );

/**
 * URL sanitization. 
 *
 * This is provided for backwards compatibility.
 */
CHARITABLE.SanitizeURL = function( input ) {
    CHARITABLE.Helpers.sanitize_url( input );
};

/***
 * Finally queue up all the scripts.
 */
( function( $ ) {

    /**
     * Prevent re-initializing form handlers.
     */
    CHARITABLE.forms_initialized = false;

    $( document ).ready( function() {

        var $form = $( '#charitable-donation-form' );

        if ( $form.length ) {
            new CHARITABLE.Donation_Form( $form );
        }

        CHARITABLE.Toggle();

    });

})( jQuery );
