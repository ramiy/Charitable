<?php
/**
 * The class that defines how donations are managed on the admin side.
 *
 * @author 		Studio164a
 * @category 	Admin
 * @package 	Charitable/Admin/Donation Post Type
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Donation_Post_Type' ) ) : 

/**
 * Charitable_Donation_Post_Type class.
 *
 * @final
 * @since 	    1.0.0
 */
final class Charitable_Donation_Post_Type {

	/**
	 * @var 	Charitable 		$charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * @var 	Charitable_Meta_Box_Helper $meta_box_helper
	 * @access 	private
	 */
	private $meta_box_helper;

	/**
	 * Create an object instance. This will only work during the charitable_start event.
	 * 
	 * @see 	charitable_start hook
	 *
	 * @param 	Charitable $charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	public static function charitable_start( Charitable $charitable ) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Donation_Post_Type( $charitable );
	}

	/**
	 * Create object instance. 
	 *
	 * @param 	Charitable 		$charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct( Charitable $charitable ) {
		$this->charitable = $charitable;
		$this->charitable->register_object($this);

		// Add fields to the dashboard listing of donations.
		add_filter( 'manage_edit-donation_columns', 		array( $this, 'dashboard_columns' ), 11, 1 );
		add_filter( 'manage_donation_posts_custom_column', 	array( $this, 'dashboard_column_item' ), 11, 2 );

		do_action( 'charitable_admin_donation_post_type_start', $this );
	}

	/**
	 * Customize donations columns.  
	 *
	 * @see 	get_column_headers
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function dashboard_columns( $column_names ) {
		$column_names = apply_filters( 'charitable_donation_dashboard_column_names', array(
			'cb'                => '<input type="checkbox"/>',
			'donor'				=> __( 'Donor', 'charitable' ), 
			'amount'			=> __( 'Amount Donated', 'charitable' )
		) );

		return $column_names;
	}

	/**
	 * Add information to the dashboard donations table listing.
	 *
	 * @see 	WP_Posts_List_Table::single_row()
	 * 
	 * @param 	string 	$column_name 	The name of the column to display.
	 * @param 	int 	$post_id     	The current post ID.
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function dashboard_column_item( $column_name, $post_id ) {		

		switch ( $column_name ) {
			case 'donor' : 
				break;

			case 'amount' : 
				break;

			default :
				break;
		}
	}	
}

endif; // End class_exists check