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
	<p class="campaign-raised campaign-summary-item"><?php 
		printf(
			 _x( '%s Raised', 'amount raised', 'charitable' ), 
			'<span class="amount">' . $campaign->get_goal() . '</span>' 
		) 
	?></p>
	<p class="campaign-goal campaign-summary-item"><?php 
		printf(
			_x( '%s Goal', 'amount goal', 'charitable' ), 
			'<span class="amount">' . $campaign->get_goal() . '</span>'
		)
	?></p>
	<p class="campaign-time-left campaign-summary-item"><?php 
		echo $campaign->get_time_left();
	?></p>
	<a class="campaign-donate-link" href="<?php
		echo "link goes here";
	?>"><div class="campaign-donate-button"><?php
		echo __( "Donate", "charitable" );
	?></div></a>
</div>
<?php
/**
 * @hook charitable_campaign_summary_after
 */
do_action('charitable_campaign_summary_after');