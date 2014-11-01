<?php 
/**
 * Displays the campaign summary. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$campaign = get_charitable()->get_request()->get_current_campaign();
$currency_helper = get_charitable()->get_currency_helper();

/**
 * @hook charitable_campaign_summary_before
 */
do_action('charitable_campaign_summary_before');

?>
<div class="campaign-summary">
	<p class="campaign-raised campaign-summary-item"><?php 
		printf(
			 _x( '%s Raised', 'amount raised', 'charitable' ), 
			'<span class="amount">' . $currency_helper->get_monetary_amount( $campaign->get_donated_amount() ) . '</span>' 
		) 
	?></p>
	<p class="campaign-goal campaign-summary-item"><?php 
		printf(
			_x( '%s Goal', 'amount goal', 'charitable' ), 
			'<span class="amount">' . $currency_helper->get_monetary_amount( $campaign->get_goal() ) . '</span>'
		)
	?></p>
	<p class="campaign-time-left campaign-summary-item"><?php 
		echo $campaign->get_time_left();
	?></p>
	<?php 
		new Charitable_Template_Part( 'campaign/donation-button' );
	?>
</div>
<?php

/**
 * @hook charitable_campaign_summary_after
 */
do_action('charitable_campaign_summary_after');