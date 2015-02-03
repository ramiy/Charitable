<?php 
/**
 * Renders the campaign goal block in the settings metabox for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

global $post;

$title 			= isset( $charitable_view_args['title'] ) 		? $charitable_view_args['title'] 	: '';
$tooltip 		= isset( $charitable_view_args['tooltip'] )		? '<span class="tooltip"> '. $charitable_view_args['tooltip'] . '</span>'	: '';
$description	= isset( $charitable_view_args['description'] )	? '<span class="charitable-helper">' . $charitable_view_args['description'] . '</span>' 	: '';
$goal 			= get_post_meta( $post->ID, '_campaign_goal', true );
$goal 			= ! $goal ? '&#8734;' : $goal;
?>
<div id="charitable-campaign-goal-metabox-wrap" class="charitable-metabox-wrap">
	<h4 class="charitable-metabox-title"><?php printf( '%s %s', $title, $tooltip ) ?></h4>
	<label class="screen-reader-text" for="campaign_goal"><?php echo $title ?></label>
	<input type="text" id="campaign_goal" name="_campaign_goal"  placeholder="&#8734;" tabindex="2" />
	<?php echo $description ?>
</div>