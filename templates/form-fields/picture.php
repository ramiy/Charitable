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

$highlight_colour = charitable_get_option( 'highlight_colour', apply_filters( 'charitable_default_highlight_colour', '#f89d35' ) );

$form           = $view_args[ 'form' ];
$field          = $view_args[ 'field' ];
$classes        = $view_args[ 'classes' ];
$is_required    = isset( $field[ 'required' ] ) ? $field[ 'required' ] : false;
$placeholder    = isset( $field[ 'placeholder' ] ) ? esc_attr( $field[ 'placeholder' ] ) : '';
$size           = isset( $field[ 'size' ] ) ? $field[ 'size' ] : 'thumbnail';
$use_uploader   = isset( $field[ 'uploader' ] ) && $field[ 'uploader' ];
$max_uploads    = isset( $field[ 'max_uploads' ] ) ? $field[ 'max_uploads' ] : 1;
$max_file_size  = isset( $field[ 'max_file_size' ] ) ? $field[ 'max_file_size' ] : wp_max_upload_size() . 'b';
$value          = isset( $field[ 'value' ] ) ? $field[ 'value' ] : '';
$has_max_uploads = strlen( $value ) && ( $max_uploads == 1 || $value >= $max_uploads );

if ( $use_uploader ) {
    wp_enqueue_script( 'charitable-pupload-fields' );
}

$params = array(
    'runtimes'            => 'html5,silverlight,flash,html4',
    'file_data_name'      => 'async-upload',
    'container'           => $field[ 'key' ] . '-dragdrop',
    'browse_button'       => $field[ 'key' ] . '-browse-button',
    'drop_element'        => $field[ 'key' ] . '-dragdrop-dropzone',    
    'multiple_queues'     => true,
    'url'                 => admin_url( 'admin-ajax.php' ),
    'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
    'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
    'multipart'           => true,
    'urlstream_upload'    => true,
    'filters'             => array(
        array(
            'title'      => _x( 'Allowed Image Files', 'image upload', 'meta-box' ),
            'extensions' => 'jpg,jpeg,gif,png',
        ),
    ),
    'multipart_params' => array(
        'field_id' => $field[ 'key' ],
        'action' => 'charitable_plupload_image_upload',
        '_ajax_nonce' => wp_create_nonce( "charitable-upload-images-{$field[ 'key' ]}" ),
        'post_id' => isset( $field[ 'parent_id' ] ) && strlen( $field[ 'parent_id' ] ) ? $field[ 'parent_id' ] : '0',
        'size' => $size, 
        'max_uploads' => $max_uploads
    )
);

?>
<style>
.charitable-drag-drop-dropzone.supports-drag-drop, 
.charitable-drag-drop-image-loader, 
.charitable-drag-drop-images{ padding: 1em; width: 100%; }
.charitable-drag-drop-dropzone.supports-drag-drop{ border: 3px dashed #ebebeb; text-align: center; }
.charitable-drag-drop-dropzone.supports-drag-drop p, 
.charitable-drag-drop-image-loader .loader-title{ margin: 0 0 0.5em; padding: 0; }
.charitable-drag-drop-dropzone.supports-drag-drop .charitable-drag-drop-buttons{ margin-bottom: 0; }
.charitable-drag-drop-image-loader, 
.charitable-drag-drop-images { border: 1px solid #ebebeb; }
.charitable-drag-drop-image-loader .loader-title{ font-style: italic; }
.charitable-drag-drop-image-loader .images{ font-size: 0.85em; }
.charitable-drag-drop-dropzone.drag-over{ border-color: <?php echo $highlight_colour ?>; }
.charitable-drag-drop-images{ list-style: none; }
.charitable-drag-drop-images:empty{ border: none; padding: 0; }
.charitable-drag-drop-images li{ position: relative; padding: 0; margin: 0 4px 4px 0; display: inline-block; border: 1px solid #ebebeb; }
.charitable-drag-drop-images li a.remove-image{ position: absolute; top: 0; right: 0; padding: 0 8px; background-color: #f89d35; color: #fff; font-size: 0.8em; border: none; display: none; }
.charitable-drag-drop-images li:hover{ border: 1px dashed #f89d35; }
.charitable-drag-drop-images li:hover a.remove-image{ display: block; }
.charitable-drag-drop-images-1{ padding: 0; border: none; }
</style>
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
        data-max-size="<?php echo $max_uploads ?>"
        data-images="<?php echo $field[ 'key' ] ?>-dragdrop-images"
        data-params="<?php echo esc_attr( wp_json_encode( $params ) ) ?>">
        <div id="<?php echo $field[ 'key' ] ?>-dragdrop-dropzone" class="charitable-drag-drop-dropzone" <?php if ( $has_max_uploads ) : ?>style="display:none;"<?php endif ?>>
            <p class="charitable-drag-drop-info"><?php _ex( 'Drop images here', 'image upload', 'charitable' ) ?></p>
            <p><?php _ex( 'or', 'image upload', 'charitable' ) ?></p>
            <p class="charitable-drag-drop-buttons"><input id="<?php echo $field[ 'key' ] ?>-browse-button" type="button" value="<?php _ex( 'Select Files', 'image upload', 'charitable' ) ?>" class="button" /></p>
        </div>
        <div class="charitable-drag-drop-image-loader" style="display: none;">
            <p class="loader-title"><?php _e( 'Uploading...', 'charitable' ) ?></p>
            <ul class="images"></ul>
        </div>
        <ul id="<?php echo $field[ 'key' ] ?>-dragdrop-images" class="charitable-drag-drop-images charitable-drag-drop-images-<?php echo $max_uploads ?>">
            <?php 
            if ( ! empty( $value ) ) : 
                if ( ! is_array( $value ) ) :
                    $value = array( $value );
                endif;

                foreach ( $value as $image ) : 
                
                    charitable_template( 'form-fields/picture-preview.php', array( 'image' => $image, 'field' => $field ) );

                endforeach;
            endif;
            ?>
        </ul>
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