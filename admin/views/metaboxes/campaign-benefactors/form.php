<?php 
/**
 * Renders the campaign benefactors form.
 *
 * @since 		1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a 
 */

$campaign_benefactor_id = isset( $view_args[ 'campaign_benefactor_id' ] ) ? $view_args[ 'campaign_benefactor_id' ] : 0;


?>
<div class="charitable-metabox-wrap">
	<label for="campaign_benefactor_<?php echo $campaign_benefactor_id ?>_contribution_amount"><?php _e( 'Contribution Amount', 'charitable' ) ?></label>
	<input type="text" id="campaign_benefactor_<?php echo $campaign_benefactor_id ?>_contribution_amount" name="_campaign_benefactor[<?php echo $campaign_benefactor_id ?>][contribution_amount]" />
</div>