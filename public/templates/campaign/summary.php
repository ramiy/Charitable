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
	<div class="campaign-raised campaign-summary-item"><?php 
		printf(
			 _x( '%s raised', 'amount raised', 'charitable' ), 
			'<span class="amount">' . $campaign->get_percent_donated() . '</span>' 
		) 
	?></div>
	<div class="campaign-goal campaign-summary-item"><?php 
		printf(
			_x( '%s donated of %s goal', 'amount goal', 'charitable' ), 
			'<span class="amount">' . $currency_helper->get_monetary_amount( $campaign->get_donated_amount() ) . '</span>', 
			'<span class="goal-amount">' . $currency_helper->get_monetary_amount( $campaign->get_goal() ) . '</span>'
		)
	?></div>
	<div class="campaign-donors"><?php
		printf( 
			_x( '%s donors', 'number of donors', 'charitable' ), 
			'<span class="amount">' . $campaign->get_donor_count() . '</span>'
		)
	?></div>
	<div class="campaign-time-left campaign-summary-item"><?php 
		echo $campaign->get_time_left();
	?></div>
	<?php 
		new Charitable_Template_Part( 'campaign/donation-button' );
	?>
</div>
<?php

/**
 * @hook charitable_campaign_summary_after
 */
do_action('charitable_campaign_summary_after');