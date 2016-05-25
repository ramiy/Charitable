<?php
/**
 * The class that defines how donations are managed on the admin side.
 * 
 * @package     Charitable/Classes/Charitable_Donation_Post_Type
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Donation_Post_Type' ) ) : 

/**
 * Charitable_Donation_Post_Type class.
 *
 * @final
 * @since       1.0.0
 */
final class Charitable_Donation_Post_Type {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Donation_Post_Type|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * @var     Charitable $charitable
     * @access  private
     */
    private $charitable;

    /**
     * @var     Charitable_Meta_Box_Helper $meta_box_helper
     * @access  private
     */
    private $meta_box_helper;

    /**
     * Create object instance. 
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {
        $this->meta_box_helper = new Charitable_Meta_Box_Helper( 'charitable-donation' );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 20 );
        add_action( 'transition_post_status', array( $this, 'handle_donation_status_change' ), 10, 3 );

        // Donations columns
        add_filter( 'manage_edit-donation_columns',         array( $this, 'dashboard_columns' ), 11, 1 );
        add_filter( 'manage_donation_posts_custom_column',  array( $this, 'dashboard_column_item' ), 11, 2 );
        add_filter( 'manage_edit-donation_sortable_columns', array( $this, 'sortable_columns' ) );
        add_filter( 'list_table_primary_column', array( $this, 'primary_column' ), 10, 2 );
        add_filter( 'post_row_actions', array( $this, 'row_actions' ), 2, 100 );

        // bulk edit
        add_filter( 'bulk_actions-edit-donation', array( $this, 'bulk_actions' ) );
        
        // customization filters
        add_filter( 'views_edit-donation',                  array( $this, 'view_options' ) );
        add_filter( 'disable_months_dropdown', array( $this, 'disable_months_dropdown' ), 10, 2 );
        add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );
        add_action( 'manage_posts_extra_tablenav', array( $this, 'extra_tablenav' ) );
        
        // export
        add_action( 'admin_footer', array( $this, 'export' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // sorting query
        add_filter( 'request', array( $this, 'request_query' ) );
        add_filter( 'posts_clauses', array( $this, 'posts_clauses' ) );

        do_action( 'charitable_admin_donation_post_type_start', $this );
    }

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Donation_Post_Type
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Donation_Post_Type();
        }

        return self::$instance;
    } 

    /**
     * Sets up the meta boxes to display on the donation admin page.     
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function add_meta_boxes() {    
        foreach ( $this->get_meta_boxes() as $meta_box_id => $meta_box ) {
            add_meta_box( 
                $meta_box_id, 
                $meta_box['title'], 
                array( $this->meta_box_helper, 'metabox_display' ), 
                Charitable::DONATION_POST_TYPE, 
                $meta_box['context'], 
                $meta_box['priority'], 
                $meta_box
            );
        }
    }

    /**
     * Remove default meta boxes.   
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function remove_meta_boxes() {
        global $wp_meta_boxes;

        $charitable_meta_boxes = $this->get_meta_boxes();
        
        foreach ( $wp_meta_boxes[ Charitable::DONATION_POST_TYPE ] as $context => $priorities ) {
            foreach ( $priorities as $priority => $meta_boxes ) {
                foreach ( $meta_boxes as $meta_box_id => $meta_box ) {
                    if ( ! isset( $charitable_meta_boxes[ $meta_box_id ] ) ) {                        
                        remove_meta_box( $meta_box_id, Charitable::DONATION_POST_TYPE, $context );
                    }
                }                
            }
        }
    }
    
    /**
     * Respond to changes in donation status. 
     *
     * @param   string $new_status
     * @param   string $old_status
     * @param   WP_Post $post
     * @return  void
     * @access  public
     * @since   1.2.0
     */
    public function handle_donation_status_change( $new_status, $old_status, $post ) {
        if ( Charitable::DONATION_POST_TYPE != $post->post_type ) {
            return;
        }

        $valid_statuses = charitable_get_valid_donation_statuses();

        if( $old_status == 'new' ){
            $message = sprintf( __( 'Donation status set to %s.', 'charitable' ), 
                $valid_statuses[$new_status] 
            );
        } else {
            $message = sprintf( __( 'Donation status updated from %s to %s.', 'charitable' ), 
                $valid_statuses[$old_status], 
                $valid_statuses[$new_status] 
            );
        }

        charitable_update_donation_log( $post->ID, $message );
    }

    /**
     * Returns an array of all meta boxes added to the donation post type screen. 
     *
     * @return  array
     * @access  private
     * @since   1.0.0
     */
    private function get_meta_boxes() {
        $meta_boxes = array(
            'donation-overview'  => array( 
                'title'         => __( 'Donation Overview', 'charitable' ), 
                'context'       => 'normal', 
                'priority'      => 'high', 
                'view'          => 'metaboxes/donation/donation-overview'
            ),             
            'donation-details'     => array(
                'title'         => __( 'Donation Details', 'charitable' ), 
                'context'       => 'side',
                'priority'      => 'high',
                'view'          => 'metaboxes/donation/donation-details'
            ), 
            'donation-log'      => array(
                'title'         => __( 'Donation Log', 'charitable' ), 
                'context'       => 'normal',
                'priority'      => 'low',
                'view'          => 'metaboxes/donation/donation-log'
            ), 
        );

        return apply_filters( 'charitable_donation_meta_boxes', $meta_boxes );  
    }

    /**
     * Customize donations columns.  
     *
     * @see     get_column_headers
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function dashboard_columns( $column_names ) {

        $column_names = apply_filters( 'charitable_donation_dashboard_column_names', array(
            'cb'                => '<input type="checkbox"/>',
            'id'                => __( 'Donation', 'charitable' ),          
            'amount'            => __( 'Amount Donated', 'charitable' ), 
            'campaigns'         => __( 'Campaign(s)', 'charitable' ),           
            'donation_date'     => __( 'Date', 'charitable' ),  
            'post_status'            => __( 'Status', 'charitable' ),
        ) );

        return $column_names;
    }

    /**
     * Add information to the dashboard donations table listing.
     *
     * @see     WP_Posts_List_Table::single_row()
     * 
     * @param   string  $column_name    The name of the column to display.
     * @param   int     $post_id        The current post ID.
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function dashboard_column_item( $column_name, $post_id ) {       

        $donation = $this->get_donation( $post_id );
        
        switch ( $column_name ) {
            case 'id' : 
               $display = sprintf( '<a href="%s" title="%s">%s</a>', 
                   esc_url( add_query_arg( array( 'post' => $donation->get_donation_id(), 'action' => 'edit' ), admin_url( 'post.php' ) ) ), 
                   __( 'View Donation Details', 'charitable' ),
                    sprintf( _x( '#%d', 'number symbol', 'charitable' ), $donation->get_donation_id() ) );

                if( $name = $donation->get_donor()->get_name() ) {
                    $display .= sprintf( _x( ' by %s', 'charitable', 'Donation by donor name' ), $name );
                }
                break;
            case 'post_status' : 
                $display = '<mark class="status '. $donation->get_status() .'">'. strtolower( $donation->get_status( true ) ) . '</mark>';
                break;

            case 'amount' : 
                $display = charitable()->get_currency_helper()->get_monetary_amount( $donation->get_total_donation_amount() );
                $display .= '<span class="meta">' . sprintf( _x( 'via %s', 'charitable-recurring' ), $donation->get_gateway_label() ). '</span>';
                break;          

            case 'campaigns' : 
                $donations = $donation->get_campaign_donations();
                $total = count( $donations );
                $display = '';
                $i = 1;
                foreach( $donations as $d ){
                    $display .= sprintf( '<a href="edit.php?post_type=%s&campaign_id=%s">%s</a>', 
                        Charitable::DONATION_POST_TYPE,
                        $d->campaign_id,
                        $d->campaign_name );
                    if( $i != $total ){
                        $display .= ', ';
                    }
                    $i++;
                }
                break;

            case 'donation_date' :              
                $display = $donation->get_date(); 
                break;

            default :
                $display = '';
                break;
        }

        echo apply_filters( 'charitable_donation_column_display', $display, $column_name, $post_id, $donation );
    }   

    /**
     * Returns the donation object. Caches the object to avoid re-creating this for each column.
     *
     * @return  Charitable_Donation
     * @access  private
     * @since   1.0.0
     */
    private function get_donation( $post_id ) {
        $donation = wp_cache_get( $post_id, 'charitable_donation' );

        if ( false === $donation ) {

            $donation = charitable_get_donation( $post_id );

            wp_cache_set( $post_id, $donation, 'charitable_donation' );

        }

        return $donation;
    }
  

    /**
     * Make columns sortable
     * 
     * @param   array  $columns  .
     * @return  array
     * @access  public
     * @since   1.4.0
     */
    public function sortable_columns( $columns ) {
        $sortable_columns = array(
            'id'       => 'ID',
            'amount'   => 'amount',
            'donation_date' => 'date',
            'post_status'   => 'post_status'
        );

        return wp_parse_args( $sortable_columns, $columns );          
    }

   /**
     * Set list table primary column for products and orders.
     * Support for WordPress 4.3.
     *
     * @param  string $default
     * @param  string $screen_id
     * @return string
     * @since  1.4.0
     */
    public function primary_column( $default, $screen_id ) {

        if ( 'edit-donation' === $screen_id ) {
            return 'id';
        }

        return $default;
    }


    /**
     * Set row actions for products and orders.
     *
     * @param  array $actions
     * @param  WP_Post $post
     * @return array
     * @since  1.4.0
     */
    public function row_actions( $actions, $post ) {

        if ( Charitable::DONATION_POST_TYPE === $post->post_type ) {
            if ( isset( $actions['inline hide-if-no-js'] ) ) {
                unset( $actions['inline hide-if-no-js'] );
            }
            if ( isset( $actions['edit'] ) ) { 
                $actions['edit'] = sprintf( '<a href="%s" title="%s">%s</a>', add_query_arg( array( 'post' => $post->ID, 'action' => 'edit' ), admin_url( 'post.php' ) ), __( 'View Details', 'charitable' ), __( 'View', 'charitable' ) );
            }
        }

        return $actions;
    }


    /**
     * Remove edit from the bulk actions.
     *
     * @param array $actions
     * @return array
     * @since  1.4.0
     */
    public function bulk_actions( $actions ) { return array();

        if ( isset( $actions['edit'] ) ) {
            unset( $actions['edit'] );
        }

        return $actions;
    }

    /**
     * Returns the array of view options for this campaign. 
     *
     * @param   array       $views
     * @return  array
     * @access  public
     * @since   1.4.0
     */
    public function view_options( $views ) {

        $current        = isset( $_GET['post-status'] ) ? $_GET['post-status'] : '';
        $statuses       = charitable_get_valid_donation_statuses();
        $donations      = new Charitable_Donations();
        $status_count   = $donations->count_by_status();

        $views          = array();
        $views['all']   = sprintf( '<a href="%s"%s>%s <span class="count">(%s)</span></a>', 
            esc_url( remove_query_arg( array( 'post_status', 'paged' ) ) ), 
            $current === 'all' || $current == '' ? ' class="current"' : '', 
            __('All', 'charitable'), 
            $donations->count_all()
        );

        foreach ( $statuses as $status => $label ) {
            $views[ $status ] = sprintf( '<a href="%s"%s>%s <span class="count">(%s)</span></a>', 
                esc_url( add_query_arg( array( 'post_status' => $status, 'paged' => false ) ) ), 
                $current === $status ? ' class="current"' : '', 
                $label, 
                isset( $status_count[ $status ] ) ? $status_count[ $status ]->num_donations : 0
            );
        } 

        return $views;
    } 

    /**
     * Disable the month's dropdown (will replace with custom range search)
     *
     * @param mixed $public_query_vars
     * @param  str $post_type
     * @return array
     * @since  1.4.0
     */
    public function disable_months_dropdown( $disable, $post_type ) {
        if( Charitable::DONATION_POST_TYPE == $post_type ){
            $disable = true;
        }

        return $disable;
    }


    /**
     * Filters for post types.
     *
     * @param str $which
     * @since  1.4.0
     */
    public function restrict_manage_posts( $which ) {
        global $typenow;

        // Show custom filters to filter orders by donor
        if ( in_array( $typenow, array( Charitable::DONATION_POST_TYPE ) ) ) { 
            $start_date = isset( $_GET['start_date'] )  ? sanitize_text_field( $_GET['start_date'] ) : '';
            $end_date   = isset( $_GET['end_date'] )    ? sanitize_text_field( $_GET['end_date'] )   : '';
            $campaign_id   = isset( $_GET['campaign_id'] )    ? intval( $_GET['campaign_id'] )   : '';
            ?>
            <label for="start_date" class="screen-reader-text"><?php _e( 'Start Date:', 'charitable' ) ?></label>
            <input type="text" id="start_date" placeholder="<?php _e( 'Start Date', 'charitable' );?>" name="start_date" class="charitable-datepicker" value="<?php echo $start_date; ?>" />
            <label for="end_date" class="screen-reader-text"><?php _e( 'End Date:', 'charitable' ) ?></label>     
            <input type="text" id="end_date" placeholder="<?php _e( 'End Date', 'charitable' );?>" name="end_date" class="charitable-datepicker" value="<?php echo $end_date; ?>" />

            <?php 
            $args = array(
                'post_type' => Charitable::CAMPAIGN_POST_TYPE,
                'nopaging' => true
            );

            $campaigns = get_posts( $args );

            ?>

            <select class="campaign_id" name="campaign_id">
            <option value="all"><?php _e( 'All Campaigns', 'charitable' ) ?></option>
            <?php foreach ( $campaigns as $campaign ) : ?>
                <option value="<?php echo $campaign->ID ?>" <?php selected( $campaign_id, $campaign->ID );?> ><?php echo get_the_title( $campaign->ID ) ?></option>
            <?php endforeach ?>
        </select>         
                 
            <?php if( ! empty( $start_date ) || ! empty( $end_date ) || ! empty( $campaign ) ) : ?>
                <a href="<?php echo admin_url( 'edit.php?post_type=' . Charitable::DONATION_POST_TYPE ); ?>" class="charitable-clear-filters button-secondary"><?php _e( 'Clear Filter', 'charitable' ); ?></a>
            <?php endif; ?>
          
    <?php    } 

    }



    /**
     * Add extra buttons after filters
     *
     * @param str $which
     * @since  1.4.0
     */
    public function extra_tablenav( $which ) {
        global $typenow;

        // Show custom filters to filter orders by donor
        if ( $which == 'top' && in_array( $typenow, array( Charitable::DONATION_POST_TYPE ) ) ) { ?>
            <div class="alignright export"><a href="#charitable-donations-export-modal" class="charitable-donations-export button-secondary trigger-modal" data-trigger-modal><?php _e( 'Export', 'charitable' ) ?></a></div>
    <?php  } 

    }


    /**
     * Load the export window
     *
     * @since  1.4.0
     *
     */
    public function export(){
        charitable_admin_view( 'donations-page/export' );
    }

    /**
     * Admin scripts and styles
     * Set up the scripts & styles used for the modal. 
     *
     * @since  1.4.0
     *
     */
    public function enqueue_scripts(){
        global $post_type;

        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
        wp_register_script( 'lean-modal', charitable()->get_path( 'assets', false ) . 'js/libraries/leanModal' . $suffix . '.js', array( 'jquery' ), charitable()->get_version() );
        wp_enqueue_style( 'lean-modal-css', charitable()->get_path( 'assets', false ) . 'css/modal' . $suffix . '.css', array(), charitable()->get_version() );
        wp_register_script( 'charitable-admin-donations', charitable()->get_path( 'assets', false ) . 'js/charitable-admin-donations' . $suffix . '.js', array( 'jquery', 'lean-modal' ), charitable()->get_version() );

        
        if( 'donation' == $post_type ){
            wp_enqueue_script( 'lean-modal' );
            wp_enqueue_script( 'charitable-admin-donations' );
            wp_enqueue_style( 'lean-modal-css' );
        }
    }

    /**
     * Custom filters
     *
     * @param  array $vars
     * @return array
     * @since  1.4.0
     */
    public function request_query( $vars ) {
        global $typenow;

        if ( Charitable::DONATION_POST_TYPE === $typenow ) {

            // No Status: fix WP's crappy handling of "all" post status
            if ( ! isset( $vars['post_status'] ) ) {
                $vars['post_status'] = array_keys( charitable_get_valid_donation_statuses() );
            }

            /* Set up date query */
            if ( isset( $_GET[ 'start_date' ] ) && ! empty( $_GET[ 'start_date' ] ) ) {
                $start_date = $this->get_parsed_date( $_GET[ 'start_date' ] );            
                
                $vars[ 'date_query' ][ 'after' ] = array(
                    'year' => $start_date[ 'year' ],
                    'month' => $start_date[ 'month' ],
                    'day' => $start_date[ 'day' ]
                );
            }

            if ( isset( $_GET[ 'end_date' ] ) && ! empty( $_GET[ 'end_date' ] ) ) {
                $end_date = $this->get_parsed_date( $_GET[ 'end_date' ] );

                $vars[ 'date_query' ][ 'before' ] = array(
                    'year' => $end_date[ 'year' ],
                    'month' => $end_date[ 'month' ],
                    'day' => $end_date[ 'day' ]
                );
            }


            // filter by campaign
            if ( isset( $_GET[ 'campaign_id' ] ) && ! empty( $_GET[ 'campaign_id' ] ) ) {
                $campaign_donations_db = new Charitable_Campaign_Donations_DB();

                $ids = $campaign_donations_db->get_donations_on_campaign( intval( $_GET[ 'campaign_id' ] ) );

                if( ! empty( $ids ) ){  
                    $ids = wp_list_pluck( $ids, 'donation_id' );     
                    $vars[ 'post__in' ] = (array) $ids; 
                }
                
            }

        }

        return $vars;
    }


    /**
     * column sorting handler
     *
     * @param  array $vars
     * @return array
     * @since  1.4.0
     */
    public function posts_clauses( $clauses ) {

        global $typenow, $wpdb;

        if ( Charitable::DONATION_POST_TYPE === $typenow ) {
        
            // Sorting
            if ( isset( $_GET['orderby'] ) ) {

                $order = isset( $_GET['order'] ) && strtoupper( $_GET['order'] ) == 'ASC' ? 'ASC' : 'DESC';

                switch ( $_GET['orderby'] ) {

                    case 'amount' :
                        $clauses['join'] = "JOIN {$wpdb->prefix}charitable_campaign_donations cd ON cd.donation_id = $wpdb->posts.ID ";
                        $clauses['orderby'] = "cd.amount " . $order;
                        break;
                    case 'status' :
                        $clauses['orderby'] = $wpdb->posts . ".post_status " . $order;
                        break;

                }
            }

        }

        return $clauses;
    }

    /**
     * Given a date, returns an array containing the date, month and year. 
     *
     * @return  string[]
     * @access  protected
     * @since   1.4.0
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

endif; // End class_exists check