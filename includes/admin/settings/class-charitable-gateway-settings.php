<?php
/**
 * Charitable Gateway Settings UI.
 * 
 * @package     Charitable/Classes/Charitable_Gateway_Settings
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Gateway_Settings' ) ) : 

/**
 * Charitable_Gateway_Settings
 *
 * @final
 * @since      1.0.0
 */
final class Charitable_Gateway_Settings {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Gateway_Settings|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * Create object instance. 
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {
    }

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Gateway_Settings
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Gateway_Settings();
        }

        return self::$instance;
    }

    /**
     * Returns all the payment gateway settings fields.  
     *
     * @return  array[]
     * @access  public
     * @since   1.0.0
     */
    public function add_gateway_fields() {
        if ( ! charitable_is_settings_view( 'gateways' ) ) {
            return array();
        }

        return array(
            'section' => array(
                'title'             => '',
                'type'              => 'hidden',
                'priority'          => 10000,
                'value'             => 'gateways', 
                'save'              => false
            ),
            'section_gateways' => array(
                'title'             => __( 'Available Payment Gateways', 'charitable' ),
                'type'              => 'heading',
                'priority'          => 5
            ), 
            'gateways' => array(
                'title'             => false,
                'callback'          => array( $this, 'render_gateways_table' ), 
                'priority'          => 10
            ),
            'test_mode' => array(
                'title'             => __( 'Turn on Test Mode', 'charitable' ),
                'type'              => 'checkbox',
                'priority'          => 15
            )
        );
    }

    /**
     * Add settings for each individual payment gateway. 
     *
     * @return  array[]
     * @access  public
     * @since   1.0.0
     */
    public function add_individual_gateway_fields( $fields ) {
        foreach ( charitable_get_helper( 'gateways' )->get_active_gateways() as $gateway ) {
            if ( ! class_exists( $gateway ) ) {
                continue;
            }

            $gateway = new $gateway;
            $fields[ 'gateways_' . $gateway->get_gateway_id() ] = apply_filters( 'charitable_settings_fields_gateways_gateway', array(), $gateway );
        }

        return $fields;
    }

    /**
     * Add gateway keys to the settings groups. 
     *
     * @param   string[] $groups
     * @return  string[]
     * @access  public
     * @since   1.0.0
     */
    public function add_gateway_settings_dynamic_groups( $groups ) {
        foreach ( charitable_get_helper( 'gateways' )->get_active_gateways() as $gateway_key => $gateway ) {
            if ( ! class_exists( $gateway ) ) {
                continue;
            }
                
            $groups[ 'gateways_' . $gateway_key ] = apply_filters( 'charitable_gateway_settings_fields_gateways_gateway', array(), new $gateway );
        }

        return $groups;
    }

    /**
     * Display table with available payment gateways.  
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function render_gateways_table( $args ) {
        charitable_admin_view( 'settings/gateways', $args );
    }
}

endif; // End class_exists check