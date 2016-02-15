<?php 
/**
 * Action/filter hooks used for the Charitable Welcome Page. 
 * 
 * @package     Charitable/Functions/Welcome Page
 * @version     1.3.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register the admin page.
 *
 * @see     Charitable_Welcome_Page::register_page()
 */
add_action( 'admin_menu', array( Charitable_Welcome_Page::get_instance(), 'register_page' ) );

/**
 * Hide the admin page from the menu.
 *
 * @see     Charitable_Welcome_Page::remove_page_from_menu()
 */
add_action( 'admin_head', array( Charitable_Welcome_Page::get_instance(), 'remove_page_from_menu' ) );

/**
 * Include custom styles for the welcome page.
 *
 * @see     Charitable_Welcome_Page::add_custom_styles()
 */
add_action( 'admin_enqueue_scripts', array( Charitable_Welcome_Page::get_instance(), 'add_custom_styles' ), 11 );