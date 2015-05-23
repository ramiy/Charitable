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
		add_filter( 'template_include', array( $this, 'widget_template' ) );
		add_filter( 'body_class', 		array( $this, 'add_donation_page_body_class' ) );
		add_filter( 'body_class', 		array( $this, 'add_widget_page_body_class' ) );
		add_filter( 'the_content', 		array( $this, 'campaign_content' ), 20 );		
		
		/* If you want to unhook any of the callbacks attached above, use this hook. */
		do_action( 'charitable_templates_start', $this );
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

	/**
	 * Load the donation template if we're looking at the donate page. 
	 *
	 * @global 	WP_Query 	$wp_query
	 * @param 	string 		$template
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function donate_template( $template ) {
		global $wp_query;

		if ( charitable_is_campaign_donation_page() ) {

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
		
		if ( charitable_is_campaign_widget_page() ) {

			do_action( 'charitable_is_widget' );
			
			$new_template 	= apply_filters( 'charitable_widget_page_template', 'campaign-widget.php' );
			$path 			= charitable_template( $new_template, false )->locate_template();

			if ( file_exists( $path ) ) {

				$template = $path;

			}
		}

		return $template;
	}	

	/**
	 * Adds custom body classes when viewing widget or donation form.
	 *
	 * @param 	array 		$classes
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_donation_page_body_class( $classes ) {
		
		if ( charitable_is_campaign_donation_page() ) {

			$classes[] = 'campaign-donation-page';

		}

		return $classes;
	}

	/**
	 * Adds custom body classes when viewing widget or donation form.
	 *
	 * @param 	array 		$classes
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_widget_page_body_class( $classes ) {

		if ( charitable_is_campaign_widget_page() ) {

			$classes[] = 'campaign-widget';
			
		}

		return $classes;
	}	
}

endif; // End class_exists check