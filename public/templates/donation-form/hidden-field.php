<?php
/**
 * The template used to display text form fields.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

$form = charitable_get_current_donation_form();

if ( ! $form ) {
	return;
}

$field = $form->get_current_field();

$value = isset( $field['value'] ) ? $field['value'] : '';
?>
<input type="hidden" name="<?php echo $field['key'] ?>" value="<?php echo $value ?>" />