<?php
/**
 * Manage Charitable's pages. 
 *
 * The responsibility of this is to manage the pages on the frontend of the Charitable experience. 
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Pages
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Pages' ) ) : 

/**
 * Charitable_Pages
 *
 * @final
 * @since 		1.0.0
 */
final class Charitable_Pages extends Charitable_Start_Object {

	/**
	 * @var 	string|false $current_view
	 * @access 	private
	 */
	private $current_view = false;

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
		
		// add_filter( 'query_vars', 		array( $this, 'register_query_vars' ) );
		// add_action( 'parse_query', 		array( $this, 'parse_query') );
		// add_action( 'template_include',	array( $this, 'maybe_load_ghost_page'), 11 );		
		add_filter( 'template_include', array( $this, 'donate_template' ) );		
		add_filter( 'template_include', array( $this, 'widget_template' ) );
		add_filter( 'body_class', 		array( $this, 'add_custom_body_classes' ) );
		
		/**
		 * Allow plugins / themes to do something at this point. This
		 * hook can be used to unset any of the callbacks attached above.
		 *
		 * @hook 	charitable_pages_start
		 */
		do_action( 'charitable_pages_start', $this );
	}

	/**
	 * Parses the current query. 
	 *
	 * @see 	parse_query
	 * 
	 * @param 	WP_Query $wp_query
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function parse_query( WP_Query $wp_query ) {
		if ( get_query_var( 'donate', false ) ) {
			$this->current_view = 'donation-form';
		}
	}	

	/**
	 * Before displaying the page, this checks if we're currently 
	 * viewing one of the frontend Charitable pages (other than a
	 * campaign). If we are, it loads the Charitable_Ghost_Page 
	 * class, which modifies the WP_Query object. 
	 *
	 * @param 	string $template
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0 
	 */
	public function maybe_load_ghost_page( $template ) {

		if ( $this->current_view ) {
			if ( $this->current_view == 'campaign' ) {
				return;
			}

			require_once( 'class-charitable-ghost-page.php' );
			
			new Charitable_Ghost_Page( $this->current_view );
		}

		return $template;
	}

	/**
	 * Returns the URL for the given page. 
	 *
	 * @global 	WP_Rewrite 	$wp_rewrite
	 * @param 	string 		$page
	 * @param 	array 		$args
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_page_url( $page, $args = array() ) {
		global $wp_rewrite;

		switch ( $page ) {
			case 'campaign-donation-page' : 

				$campaign_id = isset( $args[ 'campaign_id' ] ) ? $args[ 'campaign_id' ] : get_the_ID();

				if ( $wp_rewrite->using_permalinks() ) {
					$url = get_permalink( $campaign_id ) . '/donate/';
				}
				else {
					$url = add_query_arg( array( 'donate' => 1 ), get_permalink( $campaign_id ) );	
				}
				
				break;
		}

		return $url;
	}	

	/**
	 * Checks whether the current request is for the given page. 
	 *
	 * @global 	WP_Query 	$wp_query
	 * @param 	string 		$page
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function is_page( $page ) {		
		global $wp_query;

		$ret = false;

		switch ( $page ) {

			case 'campaign-donation-page' : 

				$ret = is_main_query() && isset ( $wp_query->query_vars[ 'donate' ] ) && is_singular( 'campaign' );

				break;

			case 'campaign-widget' : 

				$ret = is_main_query() && isset ( $wp_query->query_vars[ 'widget' ] ) && is_singular( 'campaign' );

				break;
		}

		return $ret;
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

		if ( $this->is_page( 'campaign-donation-page' ) ) {

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
		
		if ( $this->is_page( 'campaign-widget' ) ) {

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
	public function add_custom_body_classes( $classes ) {
		if ( $this->is_page( 'campaign-donation-page' ) ) {
			$classes[] = 'campaign-donation-page';
		}

		if ( $this->is_page( 'campaign-widget' ) ) {
			$classes[] = 'campaign-widget';
		}

		return $classes;
	}
}

endif; // End class_exists check.