<?php
/**
 * Class Charitable_Donation_Helper
 *
 * Helper class to create and delete a donation easily.
 */
class Charitable_Donation_Helper extends WP_UnitTestCase {

	/**
	 * Delete a donation 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function delete_donation( $donation_id ) {
		wp_delete_post( $donation_id, true );
	}

	/**
	 * Create a donation. 
	 *
	 * @param 	array 		$args 				Optional arguments.
	 * @return 	int 		$donation_id
	 * @access  public
	 * @static
	 * @since 	1.0.0	 
	 */
	public static function create_donation( $args = array() ) {
		$defaults = array(
			'user_id'		=> 1, 
			'campaigns'		=> array(), 
			'status'		=> 'charitable-completed', 
			'gateway'		=> 'manual', 
			'note'			=> ''
		);

		$args = array_merge( $defaults, $args );

		if ( empty( $args['campaigns'] ) || ! is_array( $args['campaigns'] ) ) {
			wp_die( 'You must pass an array of campaigns to create a donation.' );
		}

		return Charitable_Donation_Processor::get_instance()->save_donation( $args );
	}

	/**
	 * Create a donation for a user. 
	 *
	 * @param 	int 		$user_id
	 * @param 	int 		$campaign_id
	 * @param 	float 		$amount
	 * @return 	int 		$donation_id
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function create_campaign_donation_for_user( $user_id, $campaign_id, $amount ) {
	 	$args = array(
	 		'donor_id'			=> $user_id, 
	 		'user_id'			=> $user_id, 
	 		'campaigns'			=> array(
	 			array(	 		
		 			'campaign_id' 	=> $campaign_id, 
		 			'amount'		=> $amount
		 		)
	 		)
	 	);

	 	return self::create_donation( $args );
	}
}