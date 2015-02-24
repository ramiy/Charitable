<?php 
/**
 * Renders the campaign benefactors form.
 *
 * @since 		1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a 
 */

$benefactor 	= $view_args[ 'benefactor' ]; 
$action_args 	= charitable_get_action_args( array(
	'campaign_benefactor_id' => $benefactor->get_benefactor()->campaign_benefactor_id 
) );
?>
<div class="charitable-benefactor-summary">
	<span class="summary"><?php echo $benefactor ?></span>
	<a href="#" class="alignright" data-charitable-action="open-benefactor-form" <?php echo $action_args ?>><?php _e( 'Edit', 'charitable' ) ?></a>
</div>