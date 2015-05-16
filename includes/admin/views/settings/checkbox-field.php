<?php
/**
 * Display checkbox field. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$value = charitable_get_option( $view_args[ 'key' ] );
if ( ! strlen( $value ) ) {
	$value = isset( $view_args['default'] ) ? $view_args['default'] : 0;
}
?>
<input type="checkbox" 
    id="<?php printf( 'charitable_settings_%s', implode( '_', $view_args[ 'key' ] ) ) ?>" 
    name="<?php printf( 'charitable_settings[%s]', $view_args[ 'name' ] ) ?>"
    <?php checked( $value ) ?> />
<?php if ( isset( $view_args['help'] ) ) : ?>
	<span class="charitable-help"><?php echo $view_args['help']  ?></span>
<?php endif;