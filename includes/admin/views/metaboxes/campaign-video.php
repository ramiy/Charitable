<?php 
/**
 * Renders the video field for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */
global $post;

$video	= esc_textarea( get_post_meta( $post->ID, '_campaign_video', true ) );
?>
<textarea name="_campaign_video" id="campaign_video" tabindex="" rows="4"><?php echo esc_html( htmlspecialchars( $video ) ) ?></textarea>
