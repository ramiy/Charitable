<?php 
/**
 * Displays the campaign summary. 
 *
 * @author Studio 164a
 * @since 0.1
 */

$campaign = get_charitable()->get_request()->get_current_campaign();

/**
 * @hook charitable_campaign_summary_before
 */
do_action('charitable_campaign_summary_before');
?>
<div class="campaign-summary">
	<p class="campaign-raised campaign-summary-item">
		<?php printf(
			 _x( '%s Raised', 'amount raised', 'charitable' ), 
			'<span>' . $campaign->get_goal() . '</span>' 
		) ?>
	</p>
	<p class="campaign-goal campaign-summary-item">
		<?php printf(
			_x( '%s Goal', 'amount goal', 'charitable' ), 
			'<span>' . $campaign->get_goal() . '</span>'
		) ?>
	</p>
	<p class="campaign-time-left campaign-summary-item">
		<?php printf(
			_x( '%s Days Left', 'number of days left', 'charitable' ), 
			'<span>' . $campaign->get_time_left() . '</span>' 
		) ?>
	</p>
</div>
<?php
/**
 * @hook charitable_campaign_summary_after
 */
do_action('charitable_campaign_summary_after');