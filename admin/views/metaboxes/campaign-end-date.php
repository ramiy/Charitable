<?php 
/**
 * Renders the end date field for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

global $post;

$title 				= isset( $charitable_view_args['title'] ) 		? $charitable_view_args['title'] 		: '';
$tooltip 			= isset( $charitable_view_args['tooltip'] )		? '<span class="tooltip">' . $charitable_view_args['tooltip'] . '</span>'					: '';
$description		= isset( $charitable_view_args['description'] )	? '<span class="charitable-helper">' . $charitable_view_args['description'] . '</span>' 	: '';
$end_date_enabled 	= get_post_meta( $post->ID, '_campaign_end_date_enabled', true );
$end_date 			= get_post_meta( $post->ID, '_campaign_end_date', true );
?>
<div id="charitable-campaign-end-date-metabox-wrap" class="charitable-metabox-wrap">
	<h4 class="charitable-metabox-title"><?php printf( '%s %s', $title, $tooltip ) ?></h4>
	<label class="screen-reader-text" for="campaign_end_date"><?php echo $title ?></label>
	<input type="text" id="campaign_end_date" name="_campaign_end_date"  placeholder="&#8734;" tabindex="3" class="datepicker" value="<?php echo $end_date ?>" />
	<?php echo $description ?>
</div>