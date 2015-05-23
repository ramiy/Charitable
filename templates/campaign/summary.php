<?php 
/**
 * Displays the campaign summary. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$campaign 			= charitable_get_current_campaign();
$currency_helper 	= charitable()->get_currency_helper();
$classes 			= array( 'campaign-summary' ); 
$classes[]			= $campaign->has_goal() 	? 'campaign-has-goal' 	: 'campaign-has-no-goal';
$classes[] 			= $campaign->is_endless() 	? 'campaign-is-endless' : 'campaign-has-end-date';

/**
 * @hook charitable_campaign_summary_class
 */
apply_filters( 'charitable_campaign_summary_class', $classes, $campaign );

/**
 * @hook charitable_campaign_summary_before
 */
do_action('charitable_campaign_summary_before');

?>
<div class="<?php echo implode( ' ', $classes ) ?>">
	<?php if ( $campaign->has_goal() ) : ?>
        <div class="campaign-raised campaign-summary-item">
            <?php printf( 
                _x( '%s Raised', 'percentage raised', 'charitable' ), 
                '<span class="amount">' . $campaign->get_percent_donated() . '</span>' 
            ) ?>
        </div>
    <?php endif ?>
    <div class="campaign-figures campaign-summary-item"><?php echo $campaign->get_donation_summary() ?></div>        
    <div class="campaign-donors campaign-summary-item">
        <?php printf( 
            _x( '%s Donors', 'number of donors', 'charitable' ), 
            '<span class="donors-count">' . $campaign->get_donor_count() . '</span>'
        ) ?>
    </div>
    <?php if ( ! $campaign->is_endless() ) : ?>
        <div class="campaign-time-left campaign-summary-item">
            <?php echo $campaign->get_time_left() ?>
        </div>
    <?php 
    endif;
	
	charitable_template_part( 'campaign/donation-button' );
	?>
</div>
<?php

/**
 * @hook charitable_campaign_summary_after
 */
do_action('charitable_campaign_summary_after');