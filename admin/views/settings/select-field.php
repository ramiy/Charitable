<?php
/**
 * Display select field. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$value = charitable_get_option( $view_args['key'] );

if ( empty( $value ) ) {
	$value = isset( $view_args['default'] ) ? $view_args['default'] : '';
}
?>
<select name="charitable_settings[<?php echo $view_args['key'] ?>]">
	<?php foreach( $view_args['options'] as $key => $option ) : ?>
		<option value="<?php echo $key ?>" <?php selected( $key, $value ) ?>><?php echo $option ?></option>
	<?php endforeach ?>
</select>
<?php if ( isset( $view_args['help'] ) ) : ?>
	<span class="charitable-help"><?php echo $view_args['help']  ?></span>
<?php endif;