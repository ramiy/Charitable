<?php 

/**
 * Charitable User Dashboard Template Functions. 
 * 
 * @package     Charitable/Functions/User Dashboard
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the URL for the user login page. 
 *
 * This is used when you call charitable_get_permalink( 'login_page' ). In
 * general, you should use charitable_get_permalink() instead since it will
 * take into account permalinks that have been filtered by plugins/themes.
 *
 * @see     charitable_get_permalink
 * @global  WP_Rewrite  $wp_rewrite
 * @param   string      $url
 * @param   array       $args
 * @return  string
 * @since   1.0.0
 */
function charitable_get_login_page_permalink( $url, $args = array() ) {     
    $page   = charitable_get_option( 'login_page', 'wp' );
    $url    = 'wp' == $page ? wp_login_url() : get_permalink( $page );
    return $url;
}   

add_filter( 'charitable_permalink_login_page', 'charitable_get_login_page_permalink', 2, 2 );      

/**
 * Returns the URL for the user registration page. 
 *
 * This is used when you call charitable_get_permalink( 'registration_page' ).In
 * general, you should use charitable_get_permalink() instead since it will
 * take into account permalinks that have been filtered by plugins/themes.
 * 
 * @see     charitable_get_permalink
 * @global  WP_Rewrite  $wp_rewrite
 * @param   string      $url
 * @param   array       $args
 * @return  string
 * @since   1.0.0
 */
function charitable_get_registration_page_permalink( $url, $args = array() ) {      
    $page   = charitable_get_option( 'registration_page', 'wp' );
    $url    = 'wp' == $page ? wp_registration_url() : get_permalink( $page );
    return $url;
}   

add_filter( 'charitable_permalink_registration_page', 'charitable_get_registration_page_permalink', 2, 2 );    

/**
 * Returns the URL for the user profile page. 
 *
 * This is used when you call charitable_get_permalink( 'profile_page' ).In
 * general, you should use charitable_get_permalink() instead since it will
 * take into account permalinks that have been filtered by plugins/themes.
 * 
 * @see     charitable_get_permalink
 * @global  WP_Rewrite  $wp_rewrite
 * @param   string      $url
 * @param   array       $args
 * @return  string
 * @since   1.0.0
 */
function charitable_get_profile_page_permalink( $url, $args = array() ) {       
    $page   = charitable_get_option( 'profile_page', false );

    if ( $page ) {
        $url = get_permalink( $page );        
    }

    return $url;
}   

add_filter( 'charitable_permalink_profile_page', 'charitable_get_profile_page_permalink', 2, 2 );  