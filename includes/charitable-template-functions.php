<?php 
/**
 * Charitable Template Functions. 
 *
 * Functions used with template hooks.
 * 
 * @package     Charitable/Functions/Templates
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'charitable_template_campaign_description' ) ) { 
    /**
     * Display the campaign description before the summary and rest of content. 
     *
     * @return  void 
     * @since   1.0.0
     */
    function charitable_template_campaign_description( $campaign ) {
        charitable_template( 'campaign/description.php', array( 'campaign' => $campaign ) );
    }
}

if ( ! function_exists( 'charitable_template_campaign_video' ) ) { 
    /**
     * Display the campaign video before the summary and after the description. 
     *
     * @return  void 
     * @since   1.0.0
     */
    function charitable_template_campaign_video( $campaign ) {
        charitable_template( 'campaign/video.php', array( 'campaign' => $campaign ) );
    }
}

if ( ! function_exists( 'charitable_template_campaign_summary' ) ) { 
    /**
     * Display campaign summary before rest of campaign content. 
     *
     * @return  void 
     * @since   1.0.0
     */
    function charitable_template_campaign_summary( $campaign ) {
        charitable_template( 'campaign/summary.php' );
    }
}

if ( ! function_exists( 'charitable_template_campaign_progress_bar' ) ) { 
    /**
     * Output the campaign progress bar. 
     */
    function charitable_template_campaign_progress_bar( $campaign ) {
        charitable_template( 'campaign/progress-bar.php', array( 'campaign' => $campaign ) );
    }
}

if ( ! function_exists( 'charitable_template_campaign_donate_button' ) ) {
    /**
     * Output the campaign donate button. 
     */
    function charitable_template_campaign_donate_button( $campaign ) {
        charitable_template( 'campaign/donate-button.php', array( 'campaign' => $campaign ) );
    }
}

if ( ! function_exists( 'charitable_template_campaign_donate_link' ) ) {
    /**
     * Output the campaign donate link. 
     */
    function charitable_template_campaign_donate_link( $campaign ) {
        charitable_template( 'campaign/donate-link.php', array( 'campaign' => $campaign ) );
    }
}

if ( ! function_exists( 'charitable_template_campaign_status_tag' ) ) { 
    /**
     * Output the campaign status tag.
     */
    function charitable_template_campaign_status_tag( $campaign ) {
        charitable_template( 'campaign/status-tag.php', array( 'campaign' => $campaign ) );
    }
}

if ( ! function_exists( 'charitable_template_campaign_loop_thumbnail' ) ) { 
    /**
     * Output the campaign thumbnail on campaigns displayed within the loop.
     */
    function charitable_template_campaign_loop_thumbnail( $campaign ) {
        charitable_template( 'campaign-loop/thumbnail.php', array( 'campaign' => $campaign ) );
    }
}

if ( ! function_exists( 'charitable_template_campaign_loop_donation_stats' ) ) {
    /**
     * Output the campaign donation status on campaigns displayed within the loop.
     */
    function charitable_template_campaign_loop_donation_stats( $campaign ) {
        charitable_template( 'campaign-loop/donation-stats.php', array( 'campaign' => $campaign ) );
    }
}