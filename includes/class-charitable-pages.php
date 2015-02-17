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
final class Charitable_Pages {

	/**
	 * @var 	Charitable $charitable
	 * @access 	private
	 */
	private $charitable;

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
	 * @param 	Charitable 	$charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct( Charitable $charitable ) {
		$this->charitable = $charitable;

		$this->charitable->register_object( $this );

		add_filter( 'query_vars', 		array( $this, 'register_query_vars' ) );
		add_action( 'parse_query', 		array( $this, 'parse_query') );
		add_action( 'template_include',	array( $this, 'maybe_load_ghost_page'), 11 );
		
		/**
		 * Allow plugins / themes to do something at this point. This
		 * hook can be used to unset any of the callbacks attached above.
		 *
		 * @hook 	charitable_pages_start
		 */
		do_action( 'charitable_pages_start', $this );
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 *
	 * @see 	charitable_start
	 * @param 	Charitable 	$charitable 
	 * @return 	void
	 * @static 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function charitable_start(Charitable $charitable) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Pages( $charitable );
	}

	/**
	 * Adds additional query vars. 
	 *
	 * @see 	query_vars
	 * 
	 * @param 	array $vars
	 * @return 	array
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function register_query_vars( $vars ) {
		$vars[] = 'donate';
		return $vars;
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
	 * @param 	string $page
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_page_url( $page ) {
		switch ( $page ) {
			case 'donation-form' : 
				$url = add_query_arg( array( 'donate' => 1 ), get_home_url() );
				break;
		}

		return $url;
	}	
}

endif; // End class_exists check.