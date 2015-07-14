<?php
/** 
 * The class responsible for querying data about campaigns.
 *  
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Campaigns
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Campaigns' ) ) :

/** 
 * Charitable_Campaigns. 
 *  
 * @since		1.0.0
 */
class Charitable_Campaigns {

	/**
	 * Return WP_Query object with predefined defaults to query only campaigns. 
	 *
	 * @param 	array 		$args
	 * @return 	WP_Query
	 * @static
	 * @access  public
	 * @since 	1.0.0
	 */
	public static function query( $args = array() ) {
		$defaults = array(
			'post_type'      => array( 'campaign' ),
			'posts_per_page' => get_option( 'posts_per_page' )
		);

		$args = wp_parse_args( $args, $defaults );

		return new WP_Query( $args );
	}
	
	/**
	 * Returns a WP_Query that will return active campaigns, ordered by the date they're ending.
	 *
	 * @param 	array $args 	Additional arguments to pass to WP_Query 
	 * @return	WP_Query
	 * @static
	 * @since 	1.0.0
	 */
	public static function ordered_by_ending_soon( $args = array() ) {
		$defaults = array(
			'meta_query' 	=> array(
				array(
					'key' 		=> '_campaign_end_date',
					'value' 	=> date( 'Y-m-d H:i:s' ),
					'compare' 	=> '>=',
					'type' 		=> 'datetime'
				)
			),
			'meta_key' 		=> '_campaign_end_date',
			'orderby' 		=> 'meta_value',
			'order' 		=> 'ASC'
		);

		$args = wp_parse_args( $args, $defaults );
		
		return Charitable_Campaigns::query( $args );	
	}

	/**
	 * Returns a WP_Query that will return campaigns, ordered by the amount they raised.
	 *
	 * @global 	$wpdb
	 * @param 	array $args 	Additional arguments to pass to WP_Query 
	 * @return 	WP_Query
	 * @static
	 * @since 	1.0.0
	 * @todo
	 */
	public static function ordered_by_amount( $args = array() ) {
		global $wpdb;

		$defaults = array(
		);

		$args = wp_parse_args( $args, $defaults );

		return Charitable_Campaigns::query( $args );
	}
}

endif; // End class_exists check 