<?php
/**
 * The template used to display textarea fields.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

if ( ! isset( $view_args[ 'form' ] ) || ! isset( $view_args[ 'field' ] ) ) {
	return;
}

$form 			= $view_args[ 'form' ];
$field 			= $view_args[ 'field' ];
$is_required 	= isset( $field[ 'required' ] ) 	? $field[ 'required' ] 		: false;
$value			= isset( $field[ 'value' ] ) 		? $field[ 'value' ] 		: '';
$placeholder 	= isset( $field[ 'placeholder' ] ) 	? $field[ 'placeholder' ] 	: '';

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
	<textarea name="<?php echo $field['key'] ?>" placeholder="<?php echo $placeholder ?>"><?php echo $value ?></textarea>
</div>