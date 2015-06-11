<?php
/**
 * The template used to display the donation amount form. Unlike the main donation form, this does not include any user fields.
 *
 * @author  Studio 164a
 * @since   1.0.0
 * @version 1.0.0
 */

$form = $view_args[ 'form' ];

if ( ! $form ) {
    return;
}
?>
<form method="post" id="charitable-donation-form" class="charitable-form charitable-form-amount">
    <?php 
    /**
     * Add the donation form amount field
     *
     * @hook    charitable_donation_form_amount
     */
    do_action( 'charitable_donation_form_amount', $form ); 
    ?>
    <div class="charitable-form-field charitable-submit-field">
        <button class="button" name="add-to-cart" data-charitable-ajax-donate><?php esc_attr_e( 'Add', 'charitable' ) ?></button>
        <input class="button button-primary alignright" type="submit" name="donate" value="<?php esc_attr_e( 'Proceed to Donation', 'charitable' ) ?>" />
    </div>
</form>