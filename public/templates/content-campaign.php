<?php 
/**
 * Displays the content of the campaign.
 *
 * @author Studio 164a
 * @since 0.1
 */

$campaign = get_charitable()->get_request()->get_current_campaign();

/**
 * @hook charitable_campaign_content_before
 */
do_action( 'charitable_campaign_content_before', $campaign ); 

	/**
	 * Display the summary of the campaign. 
	 */
	new Charitable_Template_Part( 'campaign/summary' );

	the_content();

/**
 * @hook charitable_campaign_content_after
 */
do_action( 'charitable_campaign_content_after', $campaign );