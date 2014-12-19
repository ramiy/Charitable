<?php
/**
 * Display the main settings page wrapper. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$field 		= charitable_get_admin_settings()->get_current_field();
$settings 	= get_option( 'charitable_settings' );
?>
	<input type="text" name="charitable_settings[ <?php echo $field['key'] ?> ]" value="<?php echo $settings[ $field['key'] ]; ?>">
	<?php