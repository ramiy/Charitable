<?php 
/**
 * Charitable AJAX Functions. 
 *
 * Functions used with ajax hooks.
 * 
 * @package     Charitable/Functions/AJAX
 * @version     1.2.3
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'charitable_ajax_get_donation_form' ) ) : 
    /**
     * Returns the donation form content for a particular campaign, through AJAX.
     *
     * @return  void
     * @since   1.2.3
     */
    function charitable_ajax_get_donation_form() {        
        if ( ! isset( $_POST[ 'campaign_id' ] ) ) {
            wp_send_json_error();
        }

        $campaign = new Charitable_Campaign( $_POST[ 'campaign_id' ] );

        ob_start();

        $campaign->get_donation_form()->render();

        $output = ob_get_clean();

        wp_send_json_success( $output );

        die();
    }
endif;