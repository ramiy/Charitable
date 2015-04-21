<?php
/**
 * The template used to display checkbox form fields.
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
$classes 		= $view_args[ 'classes' ];
$value			= isset( $field[ 'value' ] ) 		? $field[ 'value' ] 		: '1';
$placeholder 	= isset( $field[ 'placeholder' ] ) 	? $field[ 'placeholder' ] 	: '';
?>
<div id="charitable_field_<?php echo $field[ 'key' ] ?>" class="<?php echo $classes ?>">	
	<input type="checkbox" name="<?php echo $field[ 'key' ] ?>" value="<?php echo $value ?>" />
	<?php if ( isset( $field[ 'label' ] ) ) : ?>
		<label for="charitable_field_<?php echo $field[ 'key' ] ?>">
			<?php echo $field[ 'label' ] ?>			
		</label>
	<?php endif ?>
</div>