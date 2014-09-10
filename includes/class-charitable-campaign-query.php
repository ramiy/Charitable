<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Campaign_Query' ) ) :

/** 
 * Charitable Query. 
 *  
 * @class Charitable_Campaign_Query
 * @version		0.1
 * @package		Charitable/Classes/Charitable_Campaign_Query
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Campaign_Query extends WP_Query {

	/**
	 * Extend WP_Query with some predefined defaults to query only campaigns.	 	
	 *
	 * @param array $args
	 * @return void
	 * @since 0.1
	 */
	public function __construct( $args = array() ) {
		$defaults = array(
			'post_type'      => array( 'campaign' ),
			'posts_per_page' => get_option( 'posts_per_page' )
		);

		$args = wp_parse_args( $args, $defaults );

		parent::__construct( $args );
	}
	
	/**
	 * Returns a WP_Query that will return active campaigns, ordered by the date they're ending.
	 *
	 * @param array $args 		Additional arguments to pass to WP_Query 
	 * @return WP_Query
	 * @static
	 * @since 0.1
	 */
	public static function ordered_by_ending_soon( $args = array() ) {

		$defaults = array(
				'meta_query' => array(
					array(
						'key' => '_campaign_end_date',
						'value' => date( 'Y-m-d H:i:s' ),
						'compare' => '>=',
						'type' => 'datetime'
					)
				),
				'meta_key' => '_campaign_end_date',
				'orderby' => 'meta_value',
				'order' => 'ASC'
			);
		$args = wp_parse_args( $args, $defaults );


		
		return new Charitable_Campaign_Query( $args );	
	}

	/**
	 * Returns a WP_Query that will return campaigns, ordered by the amount they raised.
	 *
	 * @global $wpdb
	 * 
	 * @param array $args 		Additional arguments to pass to WP_Query 
	 * @return WP_Query
	 * @static
	 * @since 0.1
	 */
	public static function ordered_by_amount( $args = array() ) {
		global $wpdb;


		$ordered_campaign_ids = array();

		// todo -- set up the array of args
		
		return new Charitable_Campaign_Query( $args );
	}
}

endif; // End class_exists check 