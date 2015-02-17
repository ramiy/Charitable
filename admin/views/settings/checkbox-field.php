<?php
/**
 * Display checkbox field. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$value = charitable_get_option( $view_args[ 'key' ] );

if ( empty( $value ) ) {
	$value = isset( $view_args['default'] ) ? $view_args['default'] : 0;
}
?>
<input type="checkbox" name="charitable_settings[<?php echo $view_args['key'] ?>]" <?php checked( $value ) ?> />
<?php if ( isset( $view_args['help'] ) ) : ?>
	<span class="charitable-help"><?php echo $view_args['help']  ?></span>
<?php endif;