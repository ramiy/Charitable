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
$use_uploader   = isset( $field[ 'uploader' ] ) && $field[ 'uploader' ];

if ( $use_uploader ) {
    wp_enqueue_script( 'charitable-pupload-fields' );
}

$js_arguments = array(
    'runtimes'            => 'html5,silverlight,flash,html4',
    'file_data_name'      => 'async-upload',
    'browse_button'       => $field[ 'key' ] . '-browse-button',
    'drop_element'        => $field[ 'key' ] . '-dragdrop',
    'multiple_queues'     => true,
    'max_file_size'       => wp_max_upload_size() . 'b',
    'url'                 => admin_url( 'admin-ajax.php' ),
    'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
    'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
    'multipart'           => true,
    'urlstream_upload'    => true,
    'filters'             => array(
        array(
            'title'      => _x( 'Allowed Image Files', 'image upload', 'charitable' ),
            'extensions' => 'jpg,jpeg,gif,png',
        ),
    ),
    'multipart_params'    => array(
        'field_id' => $field[ 'key' ],
        'action'   => 'charitable_plupload_image_upload',
    ),
);

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
    <div id="<?php echo $field[ 'key' ] ?>-dragdrop" 
        class="charitable-drag-drop hide-if-no-js" 
        data-upload-nonce="<?php echo wp_create_nonce( "charitable-upload-images-{$field[ 'key' ]}" ) ?>" 
        data-js-options="<?php echo esc_attr( wp_json_encode( $js_arguments ) ) ?>"
        data-post-id="<?php echo isset( $field[ 'parent_id' ] ) && strlen( $field[ 'parent_id' ] ) ? $field[ 'parent_id' ] : '0' ?>">
        <div class="charitable-drag-drop-inside">
            <p class="charitable-drag-drop-info"><?php _ex( 'Drop images here', 'image upload', 'charitable' ) ?></p>
            <p><?php _ex( 'or', 'image upload', 'charitable' ) ?></p>
            <p class="charitable-drag-drop-buttons"><input id="<?php echo $field[ 'key' ] ?>-browse-button" type="button" value="<?php _ex( 'Select Files', 'image upload', 'charitable' ) ?>" class="button" /></p>
        </div>
    </div>
    <!-- <div class="charitable-media-upload" 
        data-uploader="<?php echo intval( $use_uploader ) ?>"
        data-size="<?php echo $size ?>"
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
    </div> -->
</div>
<style>
.charitable-drag-drop{ padding: 1em; width: 100%; border: 3px dashed #ebebeb; text-align: center; line-height: 1em; }
.charitable-drag-drop{ font-size: 1.2em; }