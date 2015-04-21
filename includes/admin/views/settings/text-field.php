<?php
/**
 * Display text field. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$value = charitable_get_option( $view_args[ 'key' ] );

if ( empty( $value ) ) {
	$value = isset( $view_args['default'] ) ? $view_args['default'] : '';
}
?>
<input type="text" name="charitable_settings[<?php echo $view_args[ 'section' ] ?>][<?php echo $view_args['key'] ?>]" value="<?php echo $value ?>">
<?php if ( isset( $view_args['help'] ) ) : ?>
	<span class="charitable-help"><?php echo $view_args['help']  ?></span>
<?php endif;