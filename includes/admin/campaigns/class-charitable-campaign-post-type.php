<?php
/**
 * The class that defines how campaigns are managed on the admin side.
 * 
 * @package     Charitable/Admin/Charitable_Campaign_Post_Type
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Campaign_Post_Type' ) ) : 

/**
 * Charitable_Campaign_Post_Type class.
 *
 * @final
 * @since       1.0.0
 */
final class Charitable_Campaign_Post_Type {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Campaign_Post_Type|null
     * @access  private
     * @static
     */
    private static $instance = null;

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
        $this->meta_box_helper = new Charitable_Meta_Box_Helper( 'charitable-campaign' );

        add_action( 'add_meta_boxes',                               array( $this, 'add_meta_boxes' ), 10);
        add_action( 'add_meta_boxes_campaign',                      array( $this, 'wrap_editor' ) );
        add_action( 'edit_form_after_title',                        array( $this, 'campaign_form_top' ) );
        add_action( 'save_post_' . Charitable::CAMPAIGN_POST_TYPE, array( $this, 'save_campaign' ), 10, 2);
        add_action( 'charitable_campaign_donation_options_metabox', array( $this, 'campaign_donation_options_metabox' ));
        add_filter( 'enter_title_here',                             array( $this, 'campaign_enter_title' ), 10, 2 );
        add_filter( 'get_user_option_meta-box-order_campaign',      '__return_false' );
    }

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Campaign_Post_Type
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Campaign_Post_Type();
        }

        return self::$instance;
    }    

    /**
     * Add meta boxes.
     * 
     * @see     add_meta_boxes hook
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function add_meta_boxes() {
        $meta_boxes = array(
            array( 
                'id'            => 'campaign-description', 
                'title'         => __( 'Campaign Description', 'charitable' ), 
                'context'       => 'campaign-top', 
                'priority'      => 'high', 
                'view'          => 'metaboxes/campaign-description'
            ),
            array( 
                'id'            => 'campaign-goal', 
                'title'         => __( 'Fundraising Goal ($)', 'charitable' ), 
                'context'       => 'campaign-top', 
                'priority'      => 'high', 
                'view'          => 'metaboxes/campaign-goal', 
                'description'   => __( 'Leave empty for campaigns without a fundraising goal.', 'charitable' )
            ),  
            array( 
                'id'            => 'campaign-end-date', 
                'title'         => __( 'End Date', 'charitable' ), 
                'context'       => 'campaign-top', 
                'priority'      => 'high', 
                'view'          => 'metaboxes/campaign-end-date', 
                'description'   => __( 'Leave empty for ongoing campaigns.', 'charitable' )
            ),
            array(
                'id'            => 'campaign-donation-options', 
                'title'         => __( 'Donation Options', 'charitable' ), 
                'context'       => 'campaign-advanced', 
                'priority'      => 'high', 
                'view'          => 'metaboxes/campaign-donation-options'
            ), 
            array(
                'id'            => 'campaign-extended-description', 
                'title'         => __( 'Extended Description', 'charitable' ), 
                'context'       => 'campaign-advanced', 
                'priority'      => 'high', 
                'view'          => 'metaboxes/campaign-extended-description'
            ), 
            array(
                'id'            => 'campaign-creator',
                'title'         => __( 'Campaign Creator', 'charitable' ), 
                'context'       => 'campaign-advanced',
                'priority'      => 'high',
                'view'          => 'metaboxes/campaign-creator'
            )
        );

        $meta_boxes = apply_filters( 'charitable_campaign_meta_boxes', $meta_boxes );

        foreach ( $meta_boxes as $meta_box ) {
            add_meta_box( 
                $meta_box['id'], 
                $meta_box['title'], 
                array( $this->meta_box_helper, 'metabox_display' ), 
                Charitable::CAMPAIGN_POST_TYPE, 
                $meta_box['context'], 
                $meta_box['priority'], 
                $meta_box
            );
        }
    }

    /**
     * Display fields at the very top of the page. 
     *
     * @param   WP_Post     $post
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function campaign_form_top( $post ) {
        if ( Charitable::CAMPAIGN_POST_TYPE == $post->post_type ) {
            do_meta_boxes( Charitable::CAMPAIGN_POST_TYPE, 'campaign-top', $post );
        }       
    }

    /**
     * Wrap elements around the main editor.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function wrap_editor() {
        add_filter( 'edit_form_after_title', array( $this, 'advanced_campaign_settings' ), 20 );
    }

    /**
     * Wrap editor (and other advanced settings). 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function editor_wrap_before() {
        charitable_admin_view( 'metaboxes/campaign-advanced-wrap-before', array( 'meta_boxes' => $this->get_advanced_meta_boxes() ) );
    }

    /**
     * End wrapper around editor and other advanced settings. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function editor_wrap_after() {
        charitable_admin_view( 'metaboxes/campaign-advanced-wrap-after' );
    }

    /**
     * Display advanced campaign fields. 
     *
     * @param   WP_Post         $post
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function advanced_campaign_settings( $post ) {
        charitable_admin_view( 'metaboxes/campaign-advanced-settings', array( 'meta_boxes' => $this->get_advanced_meta_boxes() ) );
    }

    /**
     * Return flat array of meta boxes, ordered by priority.  
     *
     * @global  array       $wp_meta_boxes
     * @return  array
     * @access  private
     * @since   1.0.0
     */
    private function get_advanced_meta_boxes() {
        global $wp_meta_boxes;

        $meta_boxes = array();

        if ( ! isset( $wp_meta_boxes['campaign']['campaign-advanced'] ) ) {
            return $meta_boxes;
        }

        foreach ( array( 'high', 'sorted', 'core', 'default', 'low' ) as $priority ) {
            if ( isset( $wp_meta_boxes['campaign']['campaign-advanced'][$priority] ) ) {
                foreach ( (array) $wp_meta_boxes['campaign']['campaign-advanced'][$priority] as $box ) {
                    $meta_boxes[] = $box;
                }
            }
        }



        return $meta_boxes;
    }

    /**
     * Adds fields to the campaign donation options metabox. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function campaign_donation_options_metabox() {
        /* Get the array of fields to be displayed within the campaign donations metabox. */
        $fields = array(
            'donations'     => array(
                'priority'  => 4, 
                'view'      => 'metaboxes/campaign-donation-options/suggested-amounts', 
                'label'     => __( 'Suggested Donation Amounts', 'charitable' ), 
                'fields'    => apply_filters( 'charitable_campaign_donation_suggested_amounts_fields', array(
                    'amount'    => array(
                        'column_header' => __( 'Amount', 'charitable' ), 
                        'placeholder'   => __( 'Amount', 'charitable' )
                    ), 
                    'description'   => array(
                        'column_header' => __( 'Description (optional)', 'charitable' ), 
                        'placeholder'   => __( 'Optional Description', 'charitable' )
                    )
                ) )
            ), 
            'permit_custom' => array(
                'priority'  => 6, 
                'view'      => 'metaboxes/campaign-donation-options/permit-custom', 
                'label'     => __( 'Allow Custom Donations', 'charitable' ) 
            )
        );

        $this->meta_box_helper->display_fields( apply_filters( 'charitable_campaign_donation_options_fields', $fields ) );
    }

    /**
     * Save meta for the campaign. 
     * 
     * @param   int $campaign_id
     * @param   WP_Post $post
     * @return  void
     * @access  public 
     * @since   1.0.0
     */
    public function save_campaign( $campaign_id, WP_Post $post ) {
        if ( ! $this->meta_box_helper->user_can_save( $campaign_id ) ) {
            return;
        }
            
        $meta_keys = apply_filters( 'charitable_campaign_meta_keys', array(
            '_campaign_end_date', 
            '_campaign_goal', 
            '_campaign_suggested_donations',
            '_campaign_allow_custom_donations',
            '_campaign_description'
        ) );            

        $submitted = $_POST;

        foreach ( $meta_keys as $key ) {

            $value = isset( $submitted[ $key ] ) ? $submitted[ $key ] : false;

            $value = apply_filters( 'charitable_sanitize_campaign_meta', $value, $key, $submitted );

            update_post_meta( $campaign_id, $key, $value );

        }

        /* Hook for plugins to do something else with the posted data */
        do_action( 'charitable_campaign_save', $post );
    }   

    /**
     * Sets the placeholder text of the campaign title field. 
     *
     * @param   string      $placeholder
     * @param   WP_Post     $post
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function campaign_enter_title( $placeholder, WP_Post $post ) {       
        if ( $post->post_type == 'campaign' ) {
            $placeholder = __( 'Enter campaign title', 'charitable' );
        }

        return $placeholder;
    }
}

endif; // End class_exists check