<?php
/**
 * Sets up Charitable single campaign page template. 
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Campaign_Template
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Campaign_Template' ) ) : 

/**
 * Charitable_Campaign_Template
 *
 * @since       1.0.0
 */
class Charitable_Campaign_Template {

    /**
     * Static method to load template object. 
     *
     * @return  boolean     True if the template object was loaded. False otherwise.
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function load() {
        if ( Charitable::CAMPAIGN_POST_TYPE != get_post_type() ) {
            return false;
        }

        new Charitable_Campaign_Template();

        return true;
    }

    /**
     * Private constructor. Instantiate object with Charitable_Campaign_Template::load()
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {            
        // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_lean_modal' ) );   
        add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_percentage_raised' ), 4 );
        add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_donation_summary' ), 6 );
        add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_donor_count' ), 8 );
        add_action( 'charitable_campaign_summary', array( $this, 'display_campaign_time_left' ), 10 );
        add_action( 'charitable_campaign_summary', array( $this, 'display_donate_button' ), 14 );
        add_action( 'wp_footer', array( $this, 'add_modal_window' ) );

        add_filter( 'post_class', array( $this, 'campaign_post_class' ) );
        add_filter( 'the_content', array( $this, 'campaign_content' ), 10 );
        add_filter( 'the_content', array( $this, 'campaign_donation_form' ), 20 );
        
        /* If you want to unhook any of the callbacks attached above, use this hook. */
        do_action( 'charitable_campaign_template_start', $this );
    }    

    /**
     * Enqueue lean modal script if the donation form is set to display in a modal. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    // public function enqueue_lean_modal() {
    //     if ( 'modal' == charitable_get_option( 'donation_form_display', 'separate_page' ) ) {
    //         wp_enqueue_script( 'lean-modal' );
    //         wp_enqueue_style( 'lean-modal-css' );
    //     }
    // }

    /**
     * Display the percentage that the campaign has raised in summary block. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  boolean     True if the template was displayed. False otherwise.
     * @access  public
     * @since   1.0.0
     */
    public function display_campaign_percentage_raised( $campaign ) {
        if ( ! $campaign->has_goal() ) {
            return false;
        }

        $template = charitable_template( 'campaign/summary-percentage-raised.php', false );
        $template->set_view_args( array( 'campaign' => $campaign ) );
        $template->render();

        return true;
    }

    /**
     * Display campaign goal in summary block. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  true
     * @access  public
     * @since   1.0.0
     */
    public function display_campaign_donation_summary( $campaign ) {
        $template = charitable_template( 'campaign/summary-donations.php', false );
        $template->set_view_args( array( 'campaign' => $campaign ) );
        $template->render();

        return true;
    }

    /**
     * Display number of campaign donors in summary block.
     *
     * @param   Charitable_Campaign $campaign
     * @return  true
     * @access  public
     * @since   1.0.0
     */
    public function display_campaign_donor_count( $campaign ) {
        $template = charitable_template( 'campaign/summary-donors.php', false );
        $template->set_view_args( array( 'campaign' => $campaign ) );
        $template->render();

        return true;
    }

    /**
     * Display the amount of time left in the campaign in the summary block. 
     *
     * @param   Charitable_Campaign $campaign
     * @return  boolean     True if the template was displayed. False otherwise.
     * @access  public
     * @since   1.0.0
     */
    public function display_campaign_time_left( $campaign ) {
        if ( $campaign->is_endless() ) {
            return false;
        }

        $template = charitable_template( 'campaign/summary-time-left.php', false );
        $template->set_view_args( array( 'campaign' => $campaign ) );
        $template->render();

        return true;
    }

    /**
     * Display donate button or link in the campaign summary.
     *
     * @param   Charitable_Campaign $campaign
     * @return  boolean     True if the template was displayed. False otherwise.
     * @access  public
     * @since   1.0.0
     */
    public function display_donate_button( $campaign ) {
        if ( $campaign->has_ended() ) {
            return false;
        }

        $display_option = charitable_get_option( 'donation_form_display', 'separate_page' );

        switch ( $display_option ) {
            case 'separate_page' : 
                $template_name = 'campaign/donate-button.php';
                break;

            case 'same_page' : 
                $template_name = 'campaign/donate-link.php';
                break;

            case 'modal' : 
                $template_name = 'campaign/donate-modal.php';
                break;
        }

        $template = charitable_template( $template_name, false );
        $template->set_view_args( array( 'campaign' => $campaign ) );
        $template->render();

        return true;
    }

    /**
     * Add modal window if we are using the modal display method. 
     *
     * @return  boolean     True if template is added. False otherwise.
     * @access  public
     * @since   1.0.0
     */
    public function add_modal_window() {
        if ( 'modal' == charitable_get_option( 'donation_form_display', 'separate_page' ) ) {
            charitable_template( 'campaign/donate-modal-window.php' );            
            return true;
        }

        return false;
    }

    /**
     * Adds custom post classes when viewing campaign. 
     *
     * @return  string[] 
     * @access  public
     * @since   1.0.0
     */
    public function campaign_post_class( $classes ) {
        $campaign = charitable_get_current_campaign();
        $classes[] = $campaign->has_goal()      ? 'campaign-has-goal'   : 'campaign-has-no-goal';
        $classes[] = $campaign->is_endless()    ? 'campaign-is-endless' : 'campaign-has-end-date';

        return $classes;
    }

    /** 
     * Use our template for the campaign content.
     * 
     * @uses    the_content
     * @param   string      $content
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function campaign_content( $content ) {
        /**
         * If you do not want to use the default campaign template, use this filter and return false. 
         *
         * @uses    charitable_use_campaign_template
         */
        if ( false === apply_filters( 'charitable_use_campaign_template', true ) ) {
            return $content;
        }

        ob_start();

        charitable_template( 'content-campaign.php' );
        
        $content = ob_get_clean();
        
        return $content;
    }   

    /**
     * Optionally add the donation form straight into the campaign page. 
     *
     * @uses    the_content
     * @param   string      $content
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function campaign_donation_form( $content ) {        
        if ( $this->show_donation_form_on_campaign_page() ) {

            ob_start();

            charitable_get_current_donation_form()->render();

            $donation_form = ob_get_clean();

            $content = $content . $donation_form;
        }   

        return $content;
    }

    /**
     * Returns whether to show the donation form on campaign pages. 
     *
     * @return  boolean
     * @access  private
     * @since   1.0.0
     */
    private function show_donation_form_on_campaign_page() {
        return 'same_page' == charitable_get_option( 'donation_form_display', 'separate_page' );
    }
}

endif; // End class_exists check