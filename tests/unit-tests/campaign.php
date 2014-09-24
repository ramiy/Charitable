<?php

class Test_Charitable_Campaign extends Charitable_UnitTestCase {

	private $post;

	private $campaign;

	private $end_time;

	private $donations;

	function setUp() {
		parent::setUp();

		/**
		 * Create a campaign
		 */

		$campaign_id = $this->factory->campaign->create();

		$this->end_time = strtotime( '+300 days');

		$meta = array(
			'_campaign_goal_enabled' 				=> 1,
			'_campaign_goal' 						=> 40000.00,
			'_campaign_end_date_enabled' 			=> 1,
			'_campaign_end_date' 					=> date( 'Y-m-d H:i:s', $this->end_time ),
			'_campaign_custom_donations_enabled' 	=> 1,
			'_campaign_suggested_donations' 		=> array(
				5, 20, 50, 100, 250 
			)
		);

		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_id, $key, $value );
		}

		$this->post = get_post( $campaign_id );

		$this->campaign = new Charitable_Campaign( $this->post );

		/**
		 * Create a few donations
		 */
		$user_id_1 = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );
		$user_id_2 = $this->factory->user->create( array( 'display_name' => 'Mike Myers' ) );
		$user_id_3 = $this->factory->user->create( array( 'display_name' => 'Fritz Bolton' ) );

		$donations_data = array(
			array( 
				'user_id' => $user_id_1, 
				'amount' => 10, 
				'gateway' => 'paypal', 
				'is_preset_amount' => false, 
			),
			array( 
				'user_id' => $user_id_2, 
				'amount' => 20, 
				'gateway' => 'paypal', 
				'is_preset_amount' => true,
			),
			array( 
				'user_id' => $user_id_3, 
				'amount' => 30, 
				'gateway' => 'manual', 
				'is_preset_amount' => false
			)
		);

		foreach ( $donations_data as $donation ) {

			$donation_id = $this->factory->donation->create( array( 
				'campaign_id' 		=> $campaign_id, 
				'user_id'			=> $donation['user_id'], 
				'amount'			=> $donation['amount'], 
				'gateway'			=> $donation['gateway'], 
				'is_preset_amount' 	=> $donation['is_preset_amount']
			) );

			$this->donations[$donation_id] = new Charitable_Donation( $donation_id );
		}
	}

	function test_get_campaign_id() {
		$this->assertEquals( $this->post->ID, $this->campaign->get_campaign_id() );
	}	

	function test_get() {
		$this->assertEquals( 1, $this->campaign->get('campaign_goal_enabled') );
		$this->assertEquals( 40000.00, $this->campaign->get('campaign_goal') );
		$this->assertEquals( 1, $this->campaign->get('campaign_end_date_enabled') );
		$this->assertEquals( date( 'Y-m-d H:i:s', $this->end_time ), $this->campaign->get('campaign_end_date') );
		$this->assertEquals( 1, $this->campaign->get('campaign_custom_donations_enabled') );

		foreach ( array( 5, 20, 50, 100, 250 ) as $suggested_donation ) {
			$this->assertContains( $suggested_donation, $this->campaign->get('campaign_suggested_donations') );
		}
		
		foreach ( array( 'donor_first_name', 'donor_last_name', 'donor_email', 'donor_phone' ) as $form_field ) {
			$this->assertContains( $form_field, $this->campaign->get('campaign_donation_form_fields') );
		}
	}

	function test_get_end_time() {
		$this->assertEquals( $this->end_time, $this->campaign->get_end_time() );
	}

	function test_get_end_date() {
		$this->assertEquals( date('Y-m-d', $this->end_time), $this->campaign->get_end_date( 'Y-m-d' ) );
	}

	function test_get_seconds_left() {
		$seconds_left = $this->end_time - time();
		$this->assertEquals( $seconds_left , $this->campaign->get_seconds_left() );
	}

	function test_get_time_left() {	
		$this->assertEquals( '<span class="amount time-left days-left">300</span> Days Left', $this->campaign->get_time_left() );
	}

	function test_get_goal() {
		$this->assertEquals( 40000.00, $this->campaign->get_goal() );
	}

	function test_get_monetary_goal() {
		$this->assertEquals( '&#36;40,000.00', $this->campaign->get_monetary_goal(), 'Test monetary goal.' );
	}

	function test_get_donations() {
		$this->assertCount( 3, $this->campaign->get_donations() );
	}

	function test_get_donated_amount() {
		$this->assertEquals( 60, $this->campaign->get_donated_amount() );
	}

	function test_flush_donations_cache() {
		// Test count of donations pre-cache
		$this->assertCount( 3, $this->campaign->get_donations() );

		// Create a new donation
		$user_id_4 = $this->factory->user->create( array( 'display_name' => 'Abraham Lincoln' ) );
		$donation_id = $this->factory->donation->create( array( 
			'campaign_id' 		=> $this->campaign->get_campaign_id(), 
			'user_id'			=> $user_id_4, 
			'amount'			=> 100, 
			'gateway'			=> 'paypal', 
			'is_preset_amount' 	=> false
		) );

		// Test count of donations again, before flush caching
		$this->assertCount( 3, $this->campaign->get_donations() );

		// Flush cache
		$this->campaign->flush_donations_cache();	

		// Test count of donations again, should be +1
		$this->assertCount( 4, $this->campaign->get_donations() );
	}

	function test_get_donation_form() {
		$form = $this->campaign->get_donation_form();

		$this->assertEquals( 'Charitable_Donation_Form', get_class( $form ) );
	}	
}