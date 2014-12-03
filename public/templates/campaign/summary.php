<?php 
/**
 * Displays the campaign summary. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$campaign 			= get_charitable()->get_request()->get_current_campaign();
$currency_helper 	= get_charitable()->get_currency_helper();
$has_goal 			= $campaign->has_goal();

/**
 * @hook charitable_campaign_summary_before
 */
do_action('charitable_campaign_summary_before');

?>
<div class="campaign-summary <?php echo $has_goal ? 'campaign-has-goal' : 'campaign-has-no-goal' ?>">
	<?php if ( $campaign->has_goal() ) : ?>
		<div class="campaign-raised campaign-summary-item"><?php 
			printf(
				 _x( '%s Raised', 'percentage raised', 'charitable' ), 
				'<span class="amount">' . $campaign->get_percent_donated() . '</span>' 
			) 
		?></div>
		<div class="campaign-figures campaign-summary-item"><?php 
			printf(
				_x( '%s donated of %s goal', 'amount donated of goal', 'charitable' ), 
				'<span class="amount">' . $currency_helper->get_monetary_amount( $campaign->get_donated_amount() ) . '</span>', 
				'<span class="goal-amount">' . $currency_helper->get_monetary_amount( $campaign->get_goal() ) . '</span>'
			)
		?></div>
	<?php else : ?>
		<div class="campaign-figures campaign-summary-item"><?php 
			printf(
				_x( '%s Donated', 'amount donated', 'charitable' ), 
				'<span class="amount">' . $currency_helper->get_monetary_amount( $campaign->get_donated_amount() ) . '</span>'
			)
		?></div>
	<?php endif ?>
	<div class="campaign-donors campaign-summary-item"><?php
		printf( 
			_x( '%s Donors', 'number of donors', 'charitable' ), 
			'<span class="donors-count">' . $campaign->get_donor_count() . '</span>'
		)
	?></div>
	<div class="campaign-time-left campaign-summary-item"><?php 
		echo $campaign->get_time_left();
	?></div>
	<?php 
		charitable_template_part( 'campaign/donation-button' );
	?>
</div>
<?php

/**
 * @hook charitable_campaign_summary_after
 */
do_action('charitable_campaign_summary_after');