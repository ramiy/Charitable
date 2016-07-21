CHARITABLE = window.CHARITABLE || {};

( function( exports, $ ){

    /**
     * Donation_Form expects a jQuery this.form object.
     */
    var Donation_Form = function( form ) {
        this.errors = [];
        this.form = form;

        var self = this;
        
        // Private event handlers
        var on_focus_custom_amount = function() {
            $( this ).closest('li').find( 'input[name=donation_amount]' ).prop( 'checked', true ).trigger( 'change' );

            self.form.off( 'focus', 'input.custom-donation-input' );

            $( this ).focus();
            
            self.form.on( 'focus', 'input.custom-donation-input', function( event ) {
                self.on_focus_custom_amount( $(this) );
            });
        };

        var on_change_payment_gateway = function() {
            self.hide_inactive_payment_methods();

            self.show_active_payment_methods( $(this).val() );
        };

        var on_submit = function() {

            var data = self.form.serializeArray().reduce( function( obj, item ) {
                obj[ item.name ] = item.value;
                return obj;
            }, {} );
            var coordinates = self.form.position();
            var $modal = self.form.parent( '#charitable-donation-form-modal' );

            self.form.find( '.charitable-form-processing' ).show();        

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

                    self.form.find( '.charitable-form-processing' ).hide();

                    if ( self.form.find( '.charitable-form-errors').length ) {
                        self.form.find( '.charitable-form-errors' ).remove(); 
                    }
                    
                    self.form.prepend( response.errors );    
                    
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
        };

        // Private object initialization
        var init = function() {

            // Init donation amount selection
            (function() {

                self.form.find( '.donation-amount input:checked' ).each( function() {
                    $( this ).closest('li').addClass( 'selected' );
                });

                self.form.on( 'click', '.donation-amount', function( event ) {
                    self.select_donation_amount( $(this) );
                });

                self.form.on( 'focus', 'input.custom-donation-input', on_focus_custom_amount );
            })();

            // Init payment method selection
            (function() {

                if ( 0 === self.get_all_payment_methods().length ) {
                    return;
                }

                self.hide_inactive_payment_methods();

                $( 'body' ).on( 'change', '#charitable-gateway-selector input[name=gateway]', on_change_payment_gateway );

            })();
    
            // Handle donation form submission            
            if ( 1 === self.form.data( 'use-ajax' ) ) {
                $( 'body' ).on( 'submit', self.form, on_submit );
            }

            // donation_selection_init();

            // gateway_select_init();

        }

        init();
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
     * @return  void
     */
    Donation_Form.prototype.select_donation_amount = function( $el ) {
        var $li = $el.closest('li');

        // Already selected, quit early to prevent focus/change loop
        if( $li.hasClass( 'selected' ) ){
            return false; 
        }

        this.form.find( '.donation-amount.selected' ).removeClass( 'selected' );
        
        $li.addClass( 'selected' );

        if ( $li.hasClass( 'custom-donation-amount' ) ) {
            $li.find( 'input.custom-donation-input' ).focus();
        }

        return false;
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

    /**
     * Set up Donation Form object.
     */
    $( document ).ready( function(){
        var $form = $( '#charitable-donation-form' );
        
        if ( $form.length ) {
            new CHARITABLE.Donation_Form( $form );
        }
    });

})( CHARITABLE, jQuery );