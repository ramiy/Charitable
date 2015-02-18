<?php
/**
 * Charitable Uninstall class.
 * 
 * The responsibility of this class is to manage the events that need to happen 
 * when the plugin is deactivated.
 *
 * @package		Charitable/Charitable_Uninstall
 * @version		1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Uninstall' ) ) : 

/**
 * Charitable_Uninstall
 * 
 * @since 		1.0.0
 */
class Charitable_Uninstall {

	/**
	 * @var 	Charitable
	 * @access 	private 
	 */
	private $charitable;

	/**
	 * Uninstall the plugin.
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @static
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct( Charitable $charitable ){
		$this->charitable = $charitable;
		$this->remove_caps();
		$this->remove_post_data();
		$this->remove_tables();
	}

	/**
	 * Remove plugin-specific roles. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function remove_caps() {
		$roles = new Charitable_Roles();
		$roles->remove_caps();
	}

	/**
	 * Remove post objects created by Charitable. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function remove_post_data() {
		foreach ( array( 'campaign', 'donation' ) as $post_type ) {
			$posts = get_posts( array(
				'posts_per_page' 	=> -1, 
				'post_type'			=> $post_type
			) );

			foreach ( $posts as $post ) {
				wp_delete_post( $post->ID, true );
			}
		}
	}

	/**
	 * Remove the custom tables added by Charitable.  
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function remove_tables() {
		global $wpdb;

		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "charitable_campaign_donations" );

		delete_option( $wpdb->prefix . 'charitable_campaign_donations_db_version' );
	}
}

endif;