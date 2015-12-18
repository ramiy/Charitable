<?php 
/**
 * Displays the campaign archive.
 *
 * The template is based 
 *
 * Override this template by copying it to yourtheme/campaign-archive.php
 *
 * @author  Studio 164a
 * @package Charitable/Templates/Campaign
 * @since   1.3.0
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); 
    
/**
 * @hook charitable_template_loop_before
 */
do_action( 'charitable_template_loop_before', 'archive-campaign' );

charitable_template_campaign_loop( false, 2 );

/**
 * @hook charitable_template_loop_before
 */
do_action( 'charitable_template_loop_after', 'archive-campaign' );
        
get_sidebar();

get_footer();