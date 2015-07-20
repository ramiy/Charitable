<?php
/**
 * Gateway abstract model 
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Gateway
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Gateway' ) ) : 

/**
 * Charitable_Gateway
 *
 * @abstract
 * @since		1.0.0
 */
abstract class Charitable_Gateway {	
	
    /**
     * @var     string  The gateway's unique identifier.
     */
    const ID = '';

    /**
     * @var     string  Name of the payment gateway.
     * @access  protected
     * @since   1.0.0
     */
    protected $name;

    /**
     * @var     array   The default values for all settings added by the gateway.
     * @access  protected
     * @since   1.0.0
     */
    protected $defaults;

    /**
     * Return the gateway name.
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_name() {
        return $this->name;
    }
    
    /**
     * Returns the default gateway label to be displayed to donors. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_default_label() {
        return isset( $this->defaults[ 'label' ] ) ? $this->defaults[ 'label' ] : $this->get_name();
    }

    /**
     * Provide default gateway settings fields.
     *
     * @param   array   $settings
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function default_gateway_settings( $settings ) {
        return array(
            'section_gateway' => array(
                'type'      => 'heading',
                'title'     => $this->get_name(),
                'priority'  => 2
            ),
            'label' => array(
                'type'      => 'text',
                'title'     => __( 'Gateway Label', 'charitable' ), 
                'help'      => __( 'The label that will be shown to donors on the donation form.', 'charitable' ), 
                'priority'  => 4,
                'default'   => $this->get_default_label()
            )
        );
    }

    /**
     * Return the settings for this gateway. 
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function get_settings() {
        return charitable_get_option( 'gateways_' . self::ID, array() );
    }

    /**
     * Retrieve the gateway label. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_label() {
        return charitable_get_option( 'label', $this->get_default_label(), $this->get_settings() );
    }

    /**
     * Return the value for a particular gateway setting. 
     *
     * @param   string  $setting
     * @return  mixed
     * @access  public
     * @since   1.0.0
     */
    public function get_value( $setting ) {
        $default = isset( $this->defaults[ $setting ] ) ? $this->defaults[ $setting ] : '';
        return charitable_get_option( $setting, $default, $this->get_settings() );
    }

    /**
     * Register gateway settings. 
     *
     * @param   array   $settings
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    abstract public function gateway_settings( $settings );

	/**
	 * Send a donation to the gateway. 
	 *
	 * @param 	Charitable_Campaign 	$campaign
	 * @param 	int 					$donation_id
	 */
	abstract public function send_donation_to_gateway( $campaign, $donation_id );

}

endif; // End class_exists check