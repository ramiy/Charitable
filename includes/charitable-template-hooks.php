<?php 
/**
 * Charitable Template Hooks. 
 *
 * Action/filter hooks used for Charitable functions/templates
 * 
 * @package     Charitable/Functions/Templates
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Single campaign, before content.
 *
 * @see charitable_template_campaign_description
 * @see charitable_template_campaign_video
 * @see charitable_template_campaign_summary
 */
add_action( 'charitable_campaign_content_before', 'charitable_template_campaign_description', 4 );
add_action( 'charitable_campaign_content_before', 'charitable_template_campaign_video', 6 );
add_action( 'charitable_campaign_content_before', 'charitable_template_campaign_summary', 8 );

/**
 * Single campaign, campaign summary. 
 *
 * @see charitable_template_campaign_percentage_raised
 * @see charitable_template_campaign_donation_summary
 * @see charitable_template_campaign_donor_count
 * @see charitable_template_campaign_time_left
 * @see charitable_template_donate_button
 */
add_action( 'charitable_campaign_summary', 'charitable_template_campaign_percentage_raised', 4 );
add_action( 'charitable_campaign_summary', 'charitable_template_campaign_donation_summary', 6 );
add_action( 'charitable_campaign_summary', 'charitable_template_campaign_donor_count', 8 );
add_action( 'charitable_campaign_summary', 'charitable_template_campaign_time_left', 10 );
add_action( 'charitable_campaign_summary', 'charitable_template_donate_button', 14 );

/** 
 * Single campaign, after content. 
 *
 * @see charitable_template_campaign_donation_form_in_page
 */
add_action( 'charitable_campaign_content_after', 'charitable_template_campaign_donation_form_in_page', 4 );

// add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_percentage_raised' ), 4 );
// add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_donation_summary' ), 6 );
// add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_donor_count' ), 8 );
// add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_time_left' ), 10 );
// add_action( 'charitable_campaign_summary', array( $this, 'display_donate_button' ), 14 );

/**
 * Campaigns loop, before title.
 * 
 * @see charitable_template_campaign_loop_thumbnail
 */
add_action( 'charitable_campaign_content_loop_before_title', 'charitable_template_campaign_loop_thumbnail', 10 );

/**
 * Campaigns loop, after the main title.
 *
 * @see charitable_template_campaign_description
 * @see charitable_template_campaign_progress_bar
 */
add_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_description', 5 );
add_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_progress_bar', 10 );
add_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_loop_donation_stats', 15 );
add_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_donate_link', 20 );