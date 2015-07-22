<?php
/**
 * Offline Payment Gateway 
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Gateway_Offline
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Gateway_Offline' ) ) : 

/**
 * Offline Payment Gateway 
 *
 * @since		1.0.0
 */
class Charitable_Gateway_Offline extends Charitable_Gateway {

    /**
     * @var     string
     */
    CONST ID = 'offline';

    /**
     * Instantiate the gateway class, defining its key values.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        $this->name = apply_filters( 'charitable_gateway_offline_name', __( 'Offline', 'charitable' ) );

        $this->defaults = array(
            'label' => __( 'Offline Donation', 'charitable' ),
            'instructions' => __( 'Thank you for your donation. We will contact you shortly for payment.', 'charitable' )
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
    public function gateway_settings( $settings ) {
        $settings[ 'instructions' ] = array(
            'type'      => 'textarea',
            'title'     => __( 'Instructions', 'charitable' ), 
            'help'      => __( 'These are the instructions you provide to donors after they make a donation.', 'charitable' ), 
            'priority'  => 6,
            'default'   => $this->defaults[ 'instructions' ]
        );

        return $settings;
    }

	/**
	 * Send the donation/donor off to the gateway.  
	 *	 
	 * @param 	int     $donation_id
     * @param   Charitable_Campaign $campaign
	 * @return 	void
	 * @access  public
     * @static
	 * @since 	1.0.0
	 */
	public static function process_donation( $donation_id, $campaign ) {
        wp_safe_redirect( charitable_get_permalink( 'donation_receipt_page', array( 'donation_id' => $donation_id ) ) );
        die();
	}
}

endif; // End class_exists check
