<?php 
/**
 * Renders the suggested donation amounts field inside the donation options metabox for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

global $post;

$title 					= isset( $charitable_view_args['label'] ) 		? $charitable_view_args['label'] 	: '';
$tooltip 				= isset( $charitable_view_args['tooltip'] )		? '<span class="tooltip"> '. $charitable_view_args['tooltip'] . '</span>'	: '';
$description			= isset( $charitable_view_args['description'] )	? '<span class="charitable-helper">' . $charitable_view_args['description'] . '</span>' 	: '';
$suggested_donations 	= get_post_meta( $post->ID, '_campaign_suggested_donations', true );
?>
<div id="charitable-campaign-goal-metabox-wrap" class="charitable-metabox-wrap">
	<label for="campaign_suggested_donations"><?php echo $title ?></label>
	<input type="text" id="campaign_suggested_donations" name="_campaign_suggested_donations" value="<?php echo $suggested_donations ?>" />		
</div>