<?php
/**
 * The template used to display file form fields.
 *
 * @author  Studio 164a
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! isset( $view_args[ 'form' ] ) || ! isset( $view_args[ 'field' ] ) ) {
    return;
}

$form           = $view_args[ 'form' ];
$field          = $view_args[ 'field' ];
$classes        = $view_args[ 'classes' ];
$is_required    = isset( $field[ 'required' ] ) ? $field[ 'required' ] : false;
$placeholder    = isset( $field[ 'placeholder' ] ) ? esc_attr( $field[ 'placeholder' ] ) : '';

$size           = isset( $field[ 'size' ] ) ? $field[ 'size' ] : 'thumbnail';

// Load all the media scripts.
wp_enqueue_media();
wp_enqueue_script( 'charitable-media-fields' );

?>
<div id="charitable_field_<?php echo $field[ 'key' ] ?>" class="<?php echo $classes ?>">    
    <?php if ( isset( $field[ 'label' ] ) ) : ?>
        <label for="charitable_field_<?php echo $field[ 'key' ] ?>">
            <?php echo $field[ 'label' ] ?>         
            <?php if ( $is_required ) : ?>
                <abbr class="required" title="required">*</abbr>
            <?php endif ?>
        </label>
    <?php endif ?>
    <div class="charitable-media-upload" 
        data-size="<?php echo $media_size ?>"
        data-user="<?php echo get_current_user_id() ?>"
        data-key="<?php echo $field[ 'key' ] ?>"
        data-change-label="<?php _e( 'Choose a different photo', 'charitable' ) ?>"
        data-upload-label="<?php _e( 'Upload a photo', 'charitable' ) ?>" 
        data-upload-title="<?php _e( 'Insert photo', 'charitable' ) ?>" 
        >
        <?php 
        if ( strlen( $field[ 'value' ] ) ) : 
            echo $field[ 'value' ]; 
        endif; 
        ?>
        <input type="file" name="<?php echo $field[ 'key' ] ?>" /> 
    </div>
</div>