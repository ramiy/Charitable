<?php 
/**
 * Renders the campaign description field for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

global $post;

$title 					= isset( $charitable_view_args['title'] ) 	? $charitable_view_args['title'] 	: '';
$tooltip 				= isset( $charitable_view_args['tooltip'] )	? '<span class="tooltip"> '. $charitable_view_args['tooltip'] . '</span>'	: '';
$campaign_description	= get_post_meta( $post->ID, '_campaign_description', true );
?>
<div id="charitable-campaign-description-metabox-wrap" class="charitable-metabox-wrap">
	<h4 class="charitable-metabox-title"><?php printf( '%s %s', $title, $tooltip ) ?></h4>
	<label class="screen-reader-text" for="campaign_description"><?php echo $campaign_description ?></label>
	<textarea name="campaign_description" id="campaign_description" tabindex="3" rows="10" placeholder="<?php _e( 'Enter a short description of your campaign', 'charitable' ) ?>"><?php echo $campaign_description ?></textarea>
</div>