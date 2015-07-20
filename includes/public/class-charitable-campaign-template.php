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
        // add_action( 'charitable_campaign_content_before', array( $this, 'display_campaign_description' ), 4 );
        // add_action( 'charitable_campaign_content_before', array( $this, 'display_campaign_video' ), 6 );
        // add_action( 'charitable_campaign_content_before', array( $this, 'display_campaign_summary' ), 8 );
        
        // add_action( 'wp_footer', array( $this, 'add_modal_window' ) );        
        // add_filter( 'the_content', array( $this, 'campaign_content' ) );        
        
        /* If you want to unhook any of the callbacks attached above, use this hook. */
        do_action( 'charitable_campaign_template_start', $this );
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
        $classes[] = $campaign->has_goal() ? 'campaign-has-goal' : 'campaign-has-no-goal';
        $classes[] = $campaign->is_endless() ? 'campaign-is-endless' : 'campaign-has-end-date';
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
        if ( ! $this->is_campaign_post() ) {
            return $content;
        }

        /**
         * If you do not want to use the default campaign template, use this filter and return false. 
         *
         * @uses    charitable_use_campaign_template
         */
        if ( ! apply_filters( 'charitable_use_campaign_template', true ) ) {
            return $content;
        }

        ob_start();
        
        charitable_template_campaign_content( $content, charitable_get_current_campaign() );

        $content = ob_get_clean();

        return $content;
    }

    /**
     * Checks whether the current post object is a campaign. 
     *
     * @return  boolean
     * @access  private
     * @since   1.0.0
     */
    private function is_campaign_post() {
        return Charitable::CAMPAIGN_POST_TYPE == get_post_type();
    }
}

endif; // End class_exists check