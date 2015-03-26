<?php
/**
 * Sets up Charitable templates for specific views. 
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Templates
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Templates' ) ) : 

/**
 * Charitable_Templates
 *
 * @since 		1.0.0
 */

class Charitable_Templates extends Charitable_Start_Object {

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @access 	protected
	 * @since 	1.0.0
	 */
	protected function __construct() {
		add_filter( 'template_include', array( $this, 'donate_template' ) );
		// add_filter( 'template_include', array( $this, 'widget_template' ) );
		add_filter( 'the_content', 		array( $this, 'campaign_content' ), 2 );
	}

	/**
	 * Load the donation template if we're looking at the donate page. 
	 *
	 * @param 	string 		$template
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function donate_template( $template ) {
		global $wp_query;

		if ( is_main_query() && isset ( $wp_query->query_vars[ 'donate' ] ) && is_singular( 'campaign' ) ) {

			do_action( 'charitable_is_donate_page' );
			
			$new_template 	= apply_filters( 'charitable_donate_page_template', 'campaign-donation-page.php' );
			$path 			= charitable_template( $new_template, false )->locate_template();

			if ( file_exists( $path ) ) {

				$template = $path;

			}
		}

		return $template;
	}

	/**
	 * Load the widget template if we're looking at the widget page. 
	 *
	 * @param 	string 		$template
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function widget_template( $template ) {
		global $wp_query;

		if ( is_main_query() && isset ( $wp_query->query_vars[ 'widget' ] ) && is_singular( 'campaign' ) ) {

			do_action( 'charitable_is_widget_page' );
			
			$new_template 	= apply_filters( 'charitable_widget_page_template', 'campaign-widget.php' );
			$path 			= charitable_template( $new_template, false )->locate_template();

			if ( file_exists( $path ) ) {

				$template = $path;

			}
		}

		return $template;
	}

	/** 
	 * Use our template for the campaign content.
	 * 
	 * @uses 	the_content
	 * @global 	WP_Post 	$post
	 * @param 	string 		$content
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function campaign_content($content) {
		global $post;

		if ( $post->post_type == 'campaign' ) {

			/**
			 * If you do not want to use the default campaign template, use this filter and return false. 
			 *
			 * @uses 	charitable_use_campaign_template
			 */
			if ( false === apply_filters( 'charitable_use_campaign_template', true ) ) {
				return $content;
			}

			ob_start();

			charitable_template( 'content-campaign.php' );
			
			$content = ob_get_clean();
		}

		return $content;
	}
}

endif; // End class_exists check