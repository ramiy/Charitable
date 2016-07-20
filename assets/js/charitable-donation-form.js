CHARITABLE = window.CHARITABLE || {};

( function( exports, $ ){

    /**
     * Donation_Form expects a jQuery this.form object.
     */
    var Donation_Form = function( form ) {
        this.errors = [];
        this.form = form;
        
        // Private object initialization
        var init = function( self ) {

            self.form.find( '.donation-amount input:checked' ).each( function() {
                $( this ).closest('li').addClass( 'selected' );
            });

            self.form.on( 'click', '.donation-amount', function( event ) {
                self.select_donation_amount( $(this) );
            });

            self.form.on( 'focus', 'input.custom-donation-input', function( event ) {
                self.on_focus_custom_amount( $(this) );
            });

        }

        init( this );
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
     * Handle focus event for custom amount field.
     *
     * @return  void
     */
    Donation_Form.prototype.on_focus_custom_amount = function( $el ) {
        var self = this;

        $el.closest('li').find( 'input[name=donation_amount]' );.prop( 'checked', true ).trigger( 'change' );

        this.form.off( 'focus', 'input.custom-donation-input' );

        $el.focus();
        
        this.form.on( 'focus', 'input.custom-donation-input', function( event ) {
            self.on_focus_custom_amount( $(this) );
        });
    }

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