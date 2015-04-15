<?php
/**
 * The template used to display the profile form.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

$form 	= $view_args[ 'form' ];
$donor	= new Charitable_Donor( wp_get_current_user() );

/**
 * @hook 	charitable_user_profile_before
 */
do_action('charitable_user_profile_before');

?>
<form method="post" id="charitable-profile-form" class="charitable-form">
	<?php 
	/**
	 * @hook 	charitable_form_before_fields
	 */
	do_action( 'charitable_form_before_fields', $form ) ?>
	
	<div class="charitable-form-fields cf">

	<?php 

	foreach ( $form->get_fields() as $key => $field ) :

		do_action( 'charitable_form_field', $field, $key, $form );

	endforeach;

	?>
	
	</div>

	<?php
	/**
	 * @hook 	charitable_form_after_fields
	 */
	do_action( 'charitable_user_profile_after_fields', $form );

	?>
	<div class="charitable-form-field charitable-submit-field">
		<input class="button button-primary" type="submit" name="update-profile" value="<?php esc_attr_e( 'Update', 'charitable' ) ?>" />
	</div>
</form>
<?php

/**
 * @hook 	charitable_user_profile_after
 */
do_action('charitable_user_profile_after');