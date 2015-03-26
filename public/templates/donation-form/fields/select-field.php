<?php
/**
 * The template used to display select form fields.
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

if ( isset( $field['options'] ) && count( $field['options'] ) ) : 
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
	<select name="<?php echo $field['key'] ?>">
		<?php 

		foreach ( $field['options'] as $value => $label ) :
			if ( is_array( $label ) ) : ?>
				
				<optgroup>
				
				<?php foreach( $label as $val => $label ) : ?>

					<option value="<?php echo $val ?>"><?php echo $label ?></option>

				<?php endforeach; ?>
				
				</optgroup>
			
			<?php else : ?>

				<option value="<?php echo $value ?>"><?php echo $label ?></option> 
				
			<?php 

			endif;
		endforeach;

		?>
	</select>
</div>
<?php endif;