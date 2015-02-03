<?php 
/**
 * Renders the donation options metabox for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$title 					= isset( $charitable_view_args['title'] ) 		? $charitable_view_args['title'] 	: '';
$tooltip 				= isset( $charitable_view_args['tooltip'] )		? '<span class="tooltip"> '. $charitable_view_args['tooltip'] . '</span>'	: '';
$description			= isset( $charitable_view_args['description'] )	? '<span class="charitable-helper">' . $charitable_view_args['description'] . '</span>' 	: '';
?>
<div class="charitable-metabox">
	<h4 class="charitable-metabox-title"><?php printf( '%s %s', $title, $tooltip ) ?></h4>
	<?php 
	do_action( 'charitable_campaign_donation_options_metabox' );
	?>
</div>