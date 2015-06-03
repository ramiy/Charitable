<?php
/**
 * The template used to display the default form.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

$form 			= $view_args[ 'form' ];
$user_fields 	= $form->get_user_fields();
$user 			= wp_get_current_user();

if ( ! $form ) {
	return;
}
?>
<form method="post" id="charitable-donation-form" class="charitable-form">
	<?php 
	/**
	 * @hook 	charitable_form_before_fields
	 */
	do_action( 'charitable_form_before_fields', $form );

	/**
	 * @hook 	charitable_donation_form_amount
	 */
	do_action( 'charitable_donation_form_amount', $form ); 

	/**
	 * @hook 	charitable_donation_form_user_fields
	 */
	do_action( 'charitable_donation_form_user_fields', $form );

	/**
	 * @hook 	charitable_form_after_fields
	 */
	do_action( 'charitable_form_after_fields', $form );

	?>
	<div class="charitable-form-field charitable-submit-field">
		<input class="button button-primary" type="submit" name="donate" value="<?php esc_attr_e( 'Donate', 'charitable' ) ?>" />
	</div>
</form>