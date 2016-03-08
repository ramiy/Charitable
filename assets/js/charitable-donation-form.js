CHARITABLE = window.CHARITABLE || {};

( function( exports, $ ){

    /**
     * Donation_Form expects a jQuery this.form object.
     */
    var Donation_Form = function( form ) {
        this.errors = [];
        this.form = form;
    };

    Donation_Form.prototype.get_email = function() {
        return this.form.find( '[name=email]' ).val();
    };

    /**
     * Get the submitted amount, taking into account both the custom & suggested donation fields.
     *
     * @return  string
     */
    Donation_Form.prototype.get_amount = function() {
        var amount = suggested = parseFloat( this.form.find( '[name=donation_amount]:selected' ).val() );

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
     * Validate the email address.
     *
     * @param 
     */

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