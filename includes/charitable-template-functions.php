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


/**********************************************/ 
/* SINGLE CAMPAIGN CONTENT
/**********************************************/

if ( ! function_exists( 'charitable_template_campaign_content' ) ) :
    /**
     * Display the campaign content.
     *
     * This is used instead of the_content filter. 
     *
     * @param   string $content
     * @param   Charitable_Campaign $campaign
     * @return  void
     * @since   1.0.0
     */
    function charitable_template_campaign_content( $content, $campaign ) {
        charitable_template( 'content-campaign.php', array( 'content' => $content, 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_description' ) ) :
    /**
     * Display the campaign description before the summary and rest of content. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  void 
     * @since   1.0.0
     */
    function charitable_template_campaign_description( $campaign ) {
        charitable_template( 'campaign/description.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_finished_notice' ) ) :
    /**
     * Display the campaign finished notice.
     *
     * @param   Charitable_Campaign $campaign
     * @return  void 
     * @since   1.0.0
     */
    function charitable_template_campaign_finished_notice( $campaign ) {
        if ( ! $campaign->has_ended() ) {
            return;
        }
        
        charitable_template( 'campaign/finished-notice.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_video' ) ) :
    /**
     * Display the campaign video before the summary and after the description. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  void 
     * @since   1.0.0
     */
    function charitable_template_campaign_video( $campaign ) {
        charitable_template( 'campaign/video.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_percentage_raised' ) ) :
    /**
     * Display the percentage that the campaign has raised in summary block. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  boolean     True if the template was displayed. False otherwise.
     * @since   1.0.0
     */
    function charitable_template_campaign_percentage_raised( $campaign ) {
        if ( ! $campaign->has_goal() ) {
            return false;
        }

        charitable_template( 'campaign/summary-percentage-raised.php', array( 'campaign' => $campaign ) );

        return true;
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_donation_summary' ) ) :
    /**
     * Display campaign goal in summary block. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  true
     * @since   1.0.0
     */
    function charitable_template_campaign_donation_summary( $campaign ) {
        charitable_template( 'campaign/summary-donations.php', array( 'campaign' => $campaign ) );
        return true;
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_donor_count' ) ) :
    /**
     * Display number of campaign donors in summary block.
     *
     * @param   Charitable_Campaign $campaign
     * @return  true
     * @since   1.0.0
     */
    function charitable_template_campaign_donor_count( $campaign ) {
        charitable_template( 'campaign/summary-donors.php', array( 'campaign' => $campaign ) );
        return true;
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_time_left' ) ) :
    /**
     * Display the amount of time left in the campaign in the summary block. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  boolean     True if the template was displayed. False otherwise.
     * @since   1.0.0
     */
    function charitable_template_campaign_time_left( $campaign ) {
        if ( $campaign->is_endless() ) {
            return false;
        }

        charitable_template( 'campaign/summary-time-left.php', array( 'campaign' => $campaign ) );
        return true;
    }
endif;

if ( ! function_exists( 'charitable_template_donate_button' ) ) :
    /**
     * Display donate button or link in the campaign summary.
     *
     * @param   Charitable_Campaign $campaign
     * @return  boolean     True if the template was displayed. False otherwise.
     * @since   1.0.0
     */
    function charitable_template_donate_button( $campaign ) {
        if ( $campaign->has_ended() ) {
            return false;
        }

        $campaign->donate_button_template();

        return true;
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_summary' ) ) :
    /**
     * Display campaign summary before rest of campaign content. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  void 
     * @since   1.0.0
     */
    function charitable_template_campaign_summary( $campaign ) {
        charitable_template( 'campaign/summary.php' );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_progress_bar' ) ) :
    /**
     * Output the campaign progress bar. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  void
     * @since   1.0.0
     */
    function charitable_template_campaign_progress_bar( $campaign ) {
        charitable_template( 'campaign/progress-bar.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_donate_button' ) ) :
    /**
     * Output the campaign donate button.
     *
     * @param   Charitable_Campaign $campaign 
     * @return  void
     * @since   1.0.0
     */
    function charitable_template_campaign_donate_button( $campaign ) {
        charitable_template( 'campaign/donate-button.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_donate_link' ) ) :
    /**
     * Output the campaign donate link. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  void
     * @since   1.0.0
     */
    function charitable_template_campaign_donate_link( $campaign ) {
        charitable_template( 'campaign/donate-link.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_status_tag' ) ) :
    /**
     * Output the campaign status tag.
     *
     * @param   Charitable_Campaign $campaign
     * @return  void
     * @since   1.0.0
     */
    function charitable_template_campaign_status_tag( $campaign ) {
        charitable_template( 'campaign/status-tag.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_donation_form_in_page' ) ) :
    /**
     * Add the donation form straight into the campaign page. 
     *
     * @param   Charitable_Campaign $campaign 
     * @return  void
     * @since   1.0.0
     */
    function charitable_template_campaign_donation_form_in_page( Charitable_Campaign $campaign ) {
        if ( $campaign->has_ended() ) {
            return;
        }
        
        if ( 'same_page' == charitable_get_option( 'donation_form_display', 'separate_page' ) ) {
            charitable_get_current_donation_form()->render();
        }
    }
endif;

/**********************************************/ 
/* CAMPAIGN LOOP
/**********************************************/

if ( ! function_exists( 'charitable_template_campaign_loop' ) ) :
    /**
     * Display the campaign loop.
     *
     * This is used instead of the_content filter. 
     *     
     * @param   WP_Query $campaigns
     * @param   int     $columns
     * @return  void
     * @since   1.0.0
     */
    function charitable_template_campaign_loop( $campaigns = false, $columns = 1 ) {
        if ( ! $campaigns ) {
            global $wp_query;
            $campaigns = $wp_query;
        }
        

        charitable_template( 'campaign-loop.php', array( 'campaigns' => $campaigns, 'columns' => $columns ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_loop_thumbnail' ) ) :
    /**
     * Output the campaign thumbnail on campaigns displayed within the loop.
     */
    function charitable_template_campaign_loop_thumbnail( $campaign ) {
        charitable_template( 'campaign-loop/thumbnail.php', array( 'campaign' => $campaign ) );
    }
endif;

if ( ! function_exists( 'charitable_template_campaign_loop_donation_stats' ) ) :
    /**
     * Output the campaign donation status on campaigns displayed within the loop.
     */
    function charitable_template_campaign_loop_donation_stats( $campaign ) {
        charitable_template( 'campaign-loop/donation-stats.php', array( 'campaign' => $campaign ) );
    }
endif;