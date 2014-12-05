<?php
/**
 * The template used to display textarea fields.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

$form 			= charitable_get_current_donation_form();

if ( ! $form ) {
	return;
}

$field 			= $form->get_current_field();
$is_required 	= isset( $field['required'] ) 	? $field['required']	: false;
?>
<div id="charitable_field_<?php echo $field['key'] ?>" class="charitable-form-field <?php if ( $is_required ) echo 'required-field' ?>">
	<?php if ( isset( $field['label'] ) ) : ?>
		<label for="charitable_field_<?php echo $field['key'] ?>">
			<?php echo $field['label'] ?>
			<?php if ( $is_required ) : ?>
				<abbr class="required" title="required">*</abbr>
			<?php endif ?>
		</label>
	<?php endif ?>
	<textarea name="<?php echo $field['key'] ?>"></textarea>
</div>