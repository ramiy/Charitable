<?php
/**
 * Payment History Table Class
 *
 * @package     Charitable/Classes/Charitable_Donations_Table
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load WP_List_Table if not loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Charitable_Donations_Table Class
 *
 * Renders the Donations table on the Donations page
 *
 * @since 1.0.0
 */
class Charitable_Donations_Table extends WP_List_Table {

    /**
     * Number of results to show per page
     *
     * @var     string
     * @since   1.0.0
     * @access  protected
     */
    protected $per_page = 30;

    /**
     * URL of this page
     *
     * @var     string
     * @since   1.0.0
     * @access  protected
     */
    protected $base_url;

    /**
     * Total number of donations
     *
     * @var     int
     * @since   1.0.0
     * @access  protected
     */
    protected $total_count;

    /**
     * An array containing the counts per status.
     *
     * @var     int[]
     * @since   1.0.0
     * @access  protected
     */
    protected $status_counts;

    /**
     * An array containing all the valid donation statuses. 
     *
     * @var     string[]
     * @access  protected
     * @since   1.0.0
     */
    protected $donation_statuses;

    /**
     * Get things started
     *
     * @see     WP_List_Table::__construct()
     * @uses    Charitable_Donations_Table::prepare_donation_counts()
     *
     * @access  public
     * @since   1.0.0     
     */
    public function __construct() {
        global $status, $page;

        $donation_post_type = get_post_type_object( 'donation' );

        // Set parent defaults
        parent::__construct( array(
            'singular'  => $donation_post_type->labels->singular_name,
            'plural'    => $donation_post_type->labels->name,
            'ajax'      => false
        ) );

        $this->base_url = admin_url( 'edit.php?page=charitable-donations-table' );
        $this->donation_statuses = Charitable_Donation::get_valid_donation_statuses();

        $this->prepare_donation_counts();
        $this->process_bulk_action();    
    }

    /**
     * Retrieve the view types.
     *
     * @access  public
     * @since   1.0.0
     * @return  array $views All the views available
     */
    public function get_views() {
        $current = isset( $_GET[ 'post_status' ] ) ? $_GET[ 'post_status' ] : '';
        
        $views = array();
        $views[ 'all' ] = sprintf( '<a href="%s"%s>%s <span class="count">(%d)</span></a>', 
            remove_query_arg( array( 'post_status', 'paged' ) ), 
            $current === 'all' || $current == '' ? ' class="current"' : '', 
            __( 'All', 'charitable' ), 
            $this->total_count
        );

        foreach ( $this->donation_statuses as $status_key => $label ) {
            $views[ $status_key ] = sprintf( '<a href="%s"%s>%s <span class="count">(%d)</span></a>', 
                add_query_arg( array( 'post_status' => $status_key, 'paged' => FALSE ) ),
                $current === $status_key ? ' class="current"' : '', 
                $label,
                $this->status_counts[ $status_key ]
            );
        }

        return apply_filters( 'charitable_donations_table_views', $views );
    }

    /**
     * Retrieve the table columns.
     *
     * @return  string[]
     * @access  public
     * @since   1.0.0     
     */
    public function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'donation'  => __( 'Donation', 'charitable' ),
            'details'   => __( 'Details', 'charitable' ),
            'campaigns' => __( 'Campaigns', 'charitable' ),
            'amount'    => __( 'Amount', 'charitable' ),
            'date'      => __( 'Date', 'charitable' ),
            'status'    => __( 'Status', 'charitable' )
        );

        return apply_filters( 'charitable_donations_table_columns', $columns );
    }

    /**
     * Retrieve the table's sortable columns.
     *
     * @return  array[]
     * @access  public
     * @since   1.0.0     
     */
    public function get_sortable_columns() {
        $columns = array(
            'donation'  => array( 'ID', true ),
            'date'      => array( 'date', false )
        );
        return apply_filters( 'charitable_donations_table_sortable_columns', $columns );
    }    

    /**
     * Render the checkbox column
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0    
     */
    public function column_cb( $donation ) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            'donation',
            $donation->ID
        );
    }

    /**
     * Render the Donation column
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function column_donation( $donation ) {        
        $donation = charitable_get_donation( $donation->ID );
        $donor = $donation->get_donor();
        $admin_donation_url = esc_url( add_query_arg( array( 'post' => $donation->ID, 'action' => 'edit' ), admin_url( 'post.php' ) ) );        

        $value = sprintf( 
            // Translators: %1$s is the ID of the donation preceded by a # sign. %2$s is the donor's email address.
            _x( '%1$s by %2$s', 'donation by donor', 'charitable' ),
            "<a href=\"{$admin_donation_url}\">#{$donation->get_number()}</a>", 
            "<br />{$donor->get_email()}"
        );

        return apply_filters( 'charitable_donations_table_column', $value, $donation->ID, 'donation' );
    }

    /**
     * Render the ID column
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function column_ID( $donation ) {        
        $value = charitable_get_donation( $donation->ID )->get_number();
        return apply_filters( 'charitable_donations_table_column', $value, $donation->ID, 'ID' );
    }
    
    /**
     * Render the Details column. 
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function column_details( $donation ) {
        $value = sprintf( '<a href="%s">%s</a>', 
            esc_url( add_query_arg( array( 'post' => $donation->ID, 'action' => 'edit' ), admin_url( 'post.php' ) ) ), 
            __( 'View Donation Details', 'charitable' ) 
        );

        return apply_filters( 'charitable_donations_table_column', $value, $donation->ID, 'details' );
    }

    /**
     * Render the Campaigns column. 
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function column_campaigns( $donation ) {
        $value = implode( ', ', charitable_get_donation( $donation->ID )->get_campaigns() );
        return apply_filters( 'charitable_donations_table_column', $value, $donation->ID, 'details' );
    }

    /**
     * Render the Amount column. 
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function column_amount( $donation ) {
        $donation = charitable_get_donation( $donation->ID );

        $gateway = $donation->get_gateway_object() ? $donation->get_gateway_object()->get_name() : $donation->get_gateway();
        $method = sprintf( _x( 'via %s', 'via payment method', 'charitable' ), $gateway );

        $value = sprintf( '%s<span class="meta">%s</span>', 
            charitable_format_money( $donation->get_total_donation_amount() ),
            $method
        );
        
        return apply_filters( 'charitable_donations_table_column', $value, $donation->ID, 'amount' );
    }

    /**
     * Render the Date column. 
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function column_date( $donation ) {
        $value = charitable_get_donation( $donation->ID )->get_date();
        return apply_filters( 'charitable_donations_table_column', $value, $donation->ID, 'date' );
    }

    /**
     * Render the Status column. 
     *
     * @param   WP_Post $donation
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function column_status( $donation ) {
        $status = charitable_get_donation( $donation->ID )->get_status();
        $value = sprintf( '<span class="charitable-status-%s">%s</span>', 
            $status, 
            $this->donation_statuses[ $status ]
        );
        return apply_filters( 'charitable_donations_table_column', $value, $donation->ID, 'status' );
    }

    /**
     * Retrieve the bulk actions
     *
     * @access public
     * @since 1.0.0
     * @return array $actions Array of the bulk actions
     */
    public function get_bulk_actions() {
        $actions = array();
        
        foreach ( $this->donation_statuses as $status_key => $label ) {
            $actions[ 'set-' . $status_key ] = sprintf( _x( 'Set to %s', 'set donation status to x', 'charitable' ), $label );
        }

        $actions[ 'delete' ] = __( 'Delete', 'charitable' );

        return apply_filters( 'charitable_donations_table_bulk_actions', $actions );
    }

    /**
     * Process the bulk actions
     *
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function process_bulk_action() {
        $ids = isset( $_GET[ 'donation' ] ) ? $_GET[ 'donation' ] : array();
        $action = $this->current_action();

        if ( empty( $action ) || empty( $ids ) ) {
            return;
        }

        /* Bulk delete donations */
        if ( 'delete' == $action ) {

            foreach ( $ids as $id ) {
                wp_delete_post( $id );                
            }
        }

        /* Check for status change */
        foreach ( $this->donation_statuses as $status_key => $label ) {
            if ( 'set-' . $status_key != $action ) {
                continue;
            }

            foreach ( $ids as $id ) {
                charitable_get_donation( $id )->update_status( $status_key );

                do_action( 'charitable_donations_table_do_bulk_action', $id, $action );
            }
        }
    }

    /**
     * Setup the final data for the table
     *
     * @uses    Charitable_Donations_Table::get_columns()
     * @uses    Charitable_Donations_Table::get_sortable_columns()
     * @uses    Charitable_Donations_Table::get_donations()
     * @uses    WP_List_Table::get_pagenum()
     * @uses    WP_List_Table::set_pagination_args()
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function prepare_items() {
        wp_reset_vars( array( 'action', 'donation', 'orderby', 'order', 's' ) );

        $donations = $this->get_donations();

        $this->set_pagination_args( array(
            'total_items' => $donations->found_posts,
            'per_page'    => $this->per_page,
            'total_pages' => ceil( $donations->found_posts / $this->per_page )
        ) );

        $this->items = $donations->posts;
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
    }

    /**
     * Return the list of exportable report types. 
     *
     * @return  string[]
     * @access  public
     * @since   1.3.0
     */
    public function get_report_types() {
        return apply_filters( 'charitable_donation_export_report_types', array( 
            'donations' => __( 'Donations', 'charitable' ) 
        ) );

    }

    /**
     * Get the donation counts. 
     *
     * @return  void
     * @access  protected
     * @since   1.0.0     
     */
    protected function prepare_donation_counts() {
        $args = array();

        if ( ! empty( $_GET[ 'start_date' ] ) ) {
            $args[ 'start_date' ] = $this->get_parsed_date( $_GET[ 'start_date' ] );
        }

        if ( ! empty( $_GET[ 'end_date' ] ) ) {
            $args[ 'end_date' ] = $this->get_parsed_date( $_GET[ 'end_date' ] );
        }

        if ( isset( $_GET[ 's' ] ) ) {
            $args[ 's' ] = urldecode( $_GET[ 's' ] );
        }

        $counts = Charitable_Donations::count_by_status( $args );

        foreach ( $this->donation_statuses as $status_key => $label ) {            

            $this->status_counts[ $status_key ] = isset( $counts[ $status_key ] ) ? $counts[ $status_key ]->num_donations : 0;

        } 

        $this->total_count = array_sum( $this->status_counts );
    }    

    /**
     * Retrieve the donations to be displayed. 
     *
     * @return  WP_Query
     * @access  protected
     * @since   1.0.0
     */
    protected function get_donations() {
        $defaults = array(
            'page'              => null,
            'orderby'           => 'ID',
            'order'             => 'DESC',
            'author'            => null,
            'post_status'       => array_keys( $this->donation_statuses ),
            's'                 => null, 
            'posts_per_page'    => $this->per_page, 
            'post_type'         => Charitable::DONATION_POST_TYPE, 
            'date_query'        => array(
                'inclusive'     => true
            )
        );

        $args = wp_parse_args( $_GET, $defaults );

        /* Sanitize search */
        if ( ! is_null( $args[ 's' ] ) ) {
            $args[ 's' ] = sanitize_text_field( $args[ 's' ] );
        }

        /* Set up date query */
        if ( isset( $_GET[ 'start_date' ] ) && ! empty( $_GET[ 'start_date' ] ) ) {
            $start_date = $this->get_parsed_date( $_GET[ 'start_date' ] );            
            
            $args[ 'date_query' ][ 'after' ] = array(
                'year' => $start_date[ 'year' ],
                'month' => $start_date[ 'month' ],
                'day' => $start_date[ 'day' ]
            );
        }

        if ( isset( $_GET[ 'end_date' ] ) && ! empty( $_GET[ 'end_date' ] ) ) {
            $end_date = $this->get_parsed_date( $_GET[ 'end_date' ] );

            $args[ 'date_query' ][ 'before' ] = array(
                'year' => $end_date[ 'year' ],
                'month' => $end_date[ 'month' ],
                'day' => $end_date[ 'day' ]
            );
        }

        $args = array_filter( $args, array( $this, 'remove_null_query_args' ) );

        return new WP_Query( $args );
    }

    /**
     * Return the total number of items matching the current status. 
     *
     * @return  int
     * @access  protected
     * @since   1.0.0
     */
    protected function get_current_status_total_items() {
        if ( ! isset( $_GET[ 'post_status' ] ) ) {
            return $this->total_count;
        }

        if ( ! isset( $this->donation_statuses[ $_GET[ 'post_status' ] ] ) ) {
            return 0;
        }

        return intval( $this->donation_statuses[ $_GET[ 'post_status' ] ] );
    }

    /**
     * Remove query args that are equal to null. 
     *
     * @return  boolean
     * @access  protected
     * @since   1.0.0
     */
    protected function remove_null_query_args( $arg ) {
        return ! is_null( $arg );
    }

    /**
     * Returns whether the current query is for a single date. 
     *
     * @param   string $start_date
     * @return  boolean
     * @access  protected
     * @since   1.3.0
     */
    protected function is_single_date_query( $start_date = '' ) {
        if ( empty( $start_date ) ) {
            return false;
        }

        if ( ! isset( $_GET[ 'end_date' ] ) ) {
            return false;
        }

        return $start_date == $_GET[ 'end_date' ];
    }

    /**
     * Given a date, returns an array containing the date, month and year. 
     *
     * @return  string[]
     * @access  protected
     * @since   1.0.0
     */
    protected function get_parsed_date( $date ) {
        $time = strtotime( $date );

        return array(
            'year' => date( 'Y', $time ),
            'month' => date( 'm', $time ),
            'day' => date( 'd', $time )
        );
    }
}