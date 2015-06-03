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
                'priority'  => 4
            )
        );
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