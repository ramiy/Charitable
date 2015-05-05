<?php
/**
 * The class responsible for querying donors.
 *
 * @package     Charitable/Classes/Charitable_Donors_Query
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Donors_Query' ) ) : 

/**
 * Charitable_Donors_Query
 *
 * @since       1.0.0
 */
class Charitable_Donors_Query implements Iterator {

    /**
     * User-defined arguments. 
     *
     * @var     array
     * @access  protected
     */
    protected $args;

    /**
     * The parameters that will be passed as the second argument of WPDB's prepare() method. 
     *
     * @var     array
     * @access  protected
     */
    protected $parameters;

    /**
     * Internal iterator position. 
     *
     * @var     int
     * @access  protected
     */
    protected $position = 0;

    /**
     * Result set. 
     *
     * @var     object[]
     * @access  protected
     */
    protected $results;

    /**
     * The parsed query. 
     *
     * @var     string
     * @access  protected
     */
    protected $query;

    /**
     * Create new query object. 
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct( $args = array() ) {
        add_filter( 'charitable_donor_query_sanitize_argument', array( $this, 'sanitize_argument' ), 5, 2 );

        $this->position = 0;
        $this->parse_args( $args );
        $this->init_query_parameters();
        $this->results = $this->query();        
    }

    /**
     * Sets query arguments by parsing passed arguments with defaults.
     *
     * @param   array   $args
     * @return  array
     * @access  protected
     * @since   1.0.0
     */
    protected function parse_args( $args = array() ) {
        $defaults = apply_filters( 'charitable_donor_query_default_args', array(            
            'status'    => array( 'charitable-completed', 'charitable-preapproved' ), 
            'orderby'   => 'date',
            'order'     => 'DESC',
            'number'    => 20,
            'paged'     => 1, 
            'fields'    => 'all', 
            'campaign'  => 0
        ) );

        $this->args = wp_parse_args( $args, $defaults );

        foreach ( $this->args as $key => $value ) {
            $this->args[ $key ] = apply_filters( 'charitable_donor_query_sanitize_argument', $value, $key );
        }
    }

    /**
     * Return the query argument value for the given key. 
     *
     * @param   string  $key
     * @return  mixed|null  Returns null if the argument is not found.  
     * @access  public
     * @since   1.0.0
     */
    public function get( $key ) {
        return isset( $this->args[ $key ] ) ? $this->args[ $key ] : null;
    }

    /**
     * Set the query argument for the given key. 
     * 
     * @param   string  $key
     * @param   mixed   $value
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function set( $key, $value ) {
        $this->args[ $key ] = apply_filters( 'charitable_donor_query_sanitize_argument', $value, $key );
    }

    /**
     * Sanitize argument value when setting.  
     *
     * @param   mixed   $value
     * @param   string  $key     
     * @return  mixed
     * @access  public
     * @since   1.0.0
     */
    public function sanitize_argument( $value, $key ) {
        switch ( $key ) {
            case 'status' :
                if ( ! is_array( $value ) ) {
                    $value = array( $value );
                }
                $value = esc_sql( array_filter( $value, 'is_string' ) );
                break;

            case 'orderby' : 
                $value = strval( $value );
                break;

            case 'order' : 
                if ( ! in_array( $value, array( 'DESC', 'ASC' ) ) ) {
                    $value = 'DESC';
                }
                break;           

            case 'fields' :
                if ( is_array( $value ) ) {
                    $value = array_filter( $value, 'is_string' );
                }
                else  {
                    $value = strval( $value );
                }
                break;

            case 'campaign' :
            case 'number' : 
            case 'paged' : 
                $value = intval( $value );
                break;
        }

        return $value;
    }

    /**
     * Set up query parameters. 
     *
     * @return  void
     * @access  protected
     * @since   1.0.0
     */
    protected function init_query_parameters() {
        $this->parameters = $this->get( 'status' );
    }

    /**
     * Return list of donor IDs together with the number of donations they have made.
     *
     * @global  WPDB    $wpdb
     * @param   array   $args
     * @return  object[]        
     * @access  public
     * @since   1.0.0
     */
    public function query( $args = array() ) {
        global $wpdb;                        
        
        $sql = "SELECT {$this->get_fields_clause()}
                FROM $wpdb->posts p
                {$this->get_joins()}
                WHERE p.post_type = 'donation'
                AND p.post_status IN ( {$this->get_status_placeholders()} )
                {$this->get_where_clause()}                
                GROUP BY p.post_author
                {$this->get_order_clause()}
                {$this->get_limit_and_offset_clause()}";

        $sql = apply_filters( 'charitable_donor_query', $sql, $args );

        $this->query = $wpdb->prepare( $sql, $this->parameters );

        $results = $wpdb->get_results( $this->query );

        // if ( 'all' == $this->get( 'fields' ) ) {
        //     $results = array_map( array( $this, 'get_user_object' ), $results );
        // }

        return $results;
    }

    /**
     * Given a result object, returns a Charitable_User object. 
     *
     * @return  Charitable_User
     * @access  public
     * @since   1.0.0
     */
    public function get_user_object( $object ) {        
        if ( ! isset( $object->ID ) ) {
            return;
        }

        return new Charitable_User( $object->ID );
    }

    /**
     * Return the where clause. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_where_clause() {
        $where_clauses = array();

        if ( $this->get( 'campaign' ) ) {
            $where_clauses[] = "cd.campaign_id = %d";    
            $this->parameters[] = $this->get( 'campaign' );
        }

        if ( empty( $where_clauses ) ) {
            $sql = '';
        }
        else {
            $sql = 'AND ' . implode( ' AND ', $where_clauses );
        }

        return apply_filters( 'charitable_donor_query_where_sql', $sql, $this );
    }

    /**
     * Return order clause. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_order_clause() {    
        $order = in_array( $this->get( 'order' ), array( 'DESC', 'ASC' ) ) ? $this->get( 'order' ) : 'DESC';

        switch ( $this->get( 'orderby' ) ) {

            case 'date' : 
                $sql = "ORDER BY p.post_date $order";
                break;

            case 'donations' : 
                $sql = "ORDER BY donations $order";
                break;

            case 'amount' : 
                $sql = "ORDER BY SUM(cd.amount) $order";
                break;

            default : 
                $sql = "";
        }

        return apply_filters( 'charitable_donor_query_order_sql', $sql, $this );
    }

    /**
     * Return limit and offset clause. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_limit_and_offset_clause() {
        /* If a negative number has been passed, we will return all results. */
        if ( $this->get( 'number' ) < 0 ) {
            return;
        }

        $sql = sprintf( "LIMIT %d ", $this->get( 'number' ) );

        if ( $this->get( 'paged' ) > 1 ) {
            $sql = sprintf( "OFFSET %d", ( $this->get( 'paged' ) - 1 ) * $this->get( 'number' ) );
        }

        return $sql;
    }

    /**
     * Return field limiting clause. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_fields_clause() {
        $select_fields = array( "p.post_author AS ID" );
        
        if ( is_array( $this->get( 'fields' ) ) ) {
            if ( in_array( 'donations', $this->get( 'fields' ) ) ) {
                $select_fields[] = "COUNT(*) AS donations";
            } 

            if ( in_array( 'amount', $this->get( 'fields' ) ) ) {
                $select_fields[] = "SUM(cd.amount) AS amount";
            }

            if ( in_array( 'display_name', $this->get( 'fields' ) ) ) {
                $select_fields[] = "u.display_name";
            }            
        }  
        elseif ( 'all' == $this->get( 'fields' ) ) {
            $select_fields[] = "COUNT(*) AS donations";
            $select_fields[] = "SUM(cd.amount) AS amount";
            $select_fields[] = "u.display_name";
        }  

        $sql = implode( ', ', $select_fields );    
        
        return apply_filters( 'charitable_donor_query_fields_sql', $sql, $select_fields, $this );
    }

    /**
     * Return joins sql.  
     *
     * @global  WPDB    $wpdb  
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_joins() {    
        global $wpdb;

        $sql = "";

        if ( $this->join_campaign_donations_table() ) {
            $sql .= "INNER JOIN {$wpdb->prefix}charitable_campaign_donations cd ON cd.donation_id = p.ID ";
        }

        if ( $this->join_users_table() ) {
            $sql .= "INNER JOIN $wpdb->users u ON u.ID = p.post_author ";
        }

        return apply_filters( 'charitable_donor_query_joins_sql', $sql, $this );
    }

    /**
     * Returns whether we will join the charitable_campaign_donations table. 
     *
     * @return  boolean
     * @access  protected
     * @since   1.0.0
     */
    protected function join_campaign_donations_table() {
        $ret = 'all' == $this->get( 'fields' ) 
            || $this->get( 'campaign' ) // Limiting by campaign ID
            || 'amount' == $this->get( 'orderby' ) // Ordering by amount 
            || ( is_array( $this->get( 'fields' ) ) && in_array( 'amount', $this->get( 'fields' ) ) ); // Return amount field

        return apply_filters( 'charitable_donor_query_join_campaign_donations_table', $ret, $this );
    }

    /**
     * Returns whether we will join the users table. 
     *
     * @return  boolean
     * @access  protected
     * @since   1.0.0
     */
    protected function join_users_table() {
        $ret = 'all' == $this->get( 'fields' ) 
            || 'name' == $this->get( 'orderby' ) // Ordering by display name 
            || ( is_array( $this->get( 'fields' ) ) && in_array( 'display_name', $this->get( 'fields' ) ) ); // Return display name field

        return apply_filters( 'charitable_donor_query_join_users_table', $ret, $this );
    }

    /**
     * Return string of %s repeated for as many statuses as we're accepting. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_status_placeholders() {
        $status_placeholders = array_fill( 0, count( $this->get( 'status' ) ), '%s' );
        return implode( ', ', $status_placeholders );
    }

    /**
     * Return number of results. 
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function count() {
        return count( $this->results );
    }

    /**
     * Rewind to first result.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * Return current element.
     *
     * @return  object
     * @access  public
     * @since   1.0.0
     */
    public function current() {
        return $this->results[ $this->position ];
    }

    /**
     * Return current key.
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function key() {
        return $this->position;
    }

    /**
     * Advance to next item.
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function next() {
        ++$this->position;
    }

    /**
     * Ensure that current position is valid.
     *
     * @return  boolean
     * @access  public
     * @since   1.0.0
     */
    public function valid() {
        return isset( $this->results[ $this->position ] );
    }
}

endif; // End class_exists check