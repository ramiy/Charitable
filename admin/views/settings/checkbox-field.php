<?php
/**
 * Display checkbox field. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$field 		= charitable_get_admin_settings()->get_current_field();
$value 		= charitable_get_option( $field[ 'key' ] );
?>
<input type="checkbox" name="charitable_settings[<?php echo $field['key'] ?>]" <?php checked( $value ) ?> />
<?php if ( isset( $field['help'] ) ) : ?>
	<span class="charitable-help"><?php echo $field['help']  ?></span>
<?php endif;