<?php

class Test_Charitable_Campaign_Query extends Charitable_UnitTestCase {

	private $post;

	private $campaigns;
	private $campaigns_ordered_by_ending_soon;
	private $campaigns_ordered_by_amount;

	function setUp() {
		parent::setUp();

		/**
		 * User
		 */
		$user_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );

		/**
		 * Campaign 1: 
		 *
		 * End date: 			300 days from now
		 * Donations received: 	$1000
		 */
		$campaign_1_id = $this->factory->campaign->create();
		$meta = array(
			'_campaign_goal_enabled' 				=> 1,
			'_campaign_goal' 						=> 40000.00,
			'_campaign_end_date_enabled' 			=> 1,
			'_campaign_end_date' 					=> date( 'Y-m-d H:i:s', strtotime( '+300 days') ),
			'_campaign_custom_donations_enabled' 	=> 1,
			'_campaign_suggested_donations' 		=> array(
				5, 20, 50, 100, 250 
			),
			'_campaign_donation_form_fields' 		=> array(
				'donor_first_name', 
				'donor_last_name', 
				'donor_email', 
				'donor_phone'
			)
		);
		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_1_id, $key, $value );
		}

		$donation_1_id = $this->factory->donation->create( array( 
			'user_id'			=> $user_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_1_id,
					'campaign_name'	=> 'Campaign 1', 
					'amount'		=> 1000
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'paypal', 
		) );

		/**
		 * Campaign 2: 
		 *
		 * End date: 			100 days from now
		 * Donations received: 	$50
		 */
		$campaign_2_id = $this->factory->campaign->create();
		$meta = array(
			'_campaign_goal_enabled' 				=> 1,
			'_campaign_goal' 						=> 40000.00,
			'_campaign_end_date_enabled' 			=> 1,
			'_campaign_end_date' 					=> date( 'Y-m-d H:i:s', strtotime( '+100 days') ),
			'_campaign_custom_donations_enabled' 	=> 1,
			'_campaign_suggested_donations' 		=> array(
				5, 20, 50, 100, 250 
			),
			'_campaign_donation_form_fields' 		=> array(
				'donor_first_name', 
				'donor_last_name', 
				'donor_email', 
				'donor_phone'
			)
		);
		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_2_id, $key, $value );
		}

		$donation_2_id = $this->factory->donation->create( array( 
			'user_id'			=> $user_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_2_id,
					'campaign_name'	=> 'Campaign 2', 
					'amount'		=> 50
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'paypal', 
		) );

		/**
		 * Campaign 3: 
		 *
		 * End date: 			2 days from now
		 * Donations received: 	$200
		 */
		$campaign_3_id = $this->factory->campaign->create();
		$meta = array(
			'_campaign_goal_enabled' 				=> 1,
			'_campaign_goal' 						=> 40000.00,
			'_campaign_end_date_enabled' 			=> 1,
			'_campaign_end_date' 					=> date( 'Y-m-d H:i:s', strtotime( '+2 days') ),
			'_campaign_custom_donations_enabled' 	=> 1,
			'_campaign_suggested_donations' 		=> array(
				5, 20, 50, 100, 250 
			),
			'_campaign_donation_form_fields' 		=> array(
				'donor_first_name', 
				'donor_last_name', 
				'donor_email', 
				'donor_phone'
			)
		);
		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_3_id, $key, $value );
		}

		$donation_3_id = $this->factory->donation->create( array( 
			'user_id'			=> $user_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_3_id,
					'campaign_name'	=> 'Campaign 3', 
					'amount'		=> 200
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'paypal', 
		) );

		/**
		 * Campaign 4: 
		 *
		 * End date: 			2 days ago
		 * Donations received: 	$40
		 */
		$campaign_4_id = $this->factory->campaign->create();
		$meta = array(
			'_campaign_goal_enabled' 				=> 1,
			'_campaign_goal' 						=> 40000.00,
			'_campaign_end_date_enabled' 			=> 1,
			'_campaign_end_date' 					=> date( 'Y-m-d H:i:s', strtotime( '-2 days') ),
			'_campaign_custom_donations_enabled' 	=> 1,
			'_campaign_suggested_donations' 		=> array(
				5, 20, 50, 100, 250 
			),
			'_campaign_donation_form_fields' 		=> array(
				'donor_first_name', 
				'donor_last_name', 
				'donor_email', 
				'donor_phone'
			)
		);
		foreach( $meta as $key => $value ) {
			update_post_meta( $campaign_4_id, $key, $value );
		}

		$donation_4_id = $this->factory->donation->create( array( 
			'user_id'			=> $user_id, 
			'campaigns'			=> array(
				array( 
					'campaign_id' 	=> $campaign_4_id,
					'campaign_name'	=> 'Campaign 4', 
					'amount'		=> 40
				)
			), 
			'status'			=> 'charitable-completed', 
			'gateway'			=> 'paypal', 
		) );

		// The array of campaign IDs
		$this->campaigns = array( 
			$campaign_1_id, 
			$campaign_2_id, 
			$campaign_3_id, 
			$campaign_4_id
		);

		// The array of campaign IDs, ordered by ending soon
		$this->campaigns_ordered_by_ending_soon = array(
			$campaign_3_id, 
			$campaign_2_id,
			$campaign_1_id
		);

		// The array of campaign IDs, ordered by amount raised
		$this->campaigns_ordered_by_amount = array(
			$campaign_1_id, 
			$campaign_3_id,
			$campaign_2_id,
			$campaign_4_id
		);
	}

	function test_construct() {
		$query = new Charitable_Campaign_Query();
		$this->assertEquals( 4, $query->found_posts );
	}

	function test_ordered_by_ending_soon() {
		$query = Charitable_Campaign_Query::ordered_by_ending_soon();

		$this->assertEquals( 3, $query->found_posts );

		$i = 0;

		while( $query->have_posts() ) {
			$query->the_post();

			// $this->assertEquals( $this->campaigns_ordered_by_ending_soon[$i], get_the_ID(), 'Index '.$i.' for campaigns orderd by date ending' );

			$this->assertEquals( $this->campaigns_ordered_by_ending_soon[$i], get_the_ID(), sprintf( 'Index %d for campaigns orderd by date ending', $i ) );
			$i++;
		}

		$query_2 = Charitable_Campaign_Query::ordered_by_ending_soon( array('posts_per_page' => 1 ) );
		$this->assertEquals( 1, count( $query_2->posts ) );
	}

	function test_ordered_by_amount() {
		// $query = Charitable_Campaign_Query::ordered_by_amount();

		// $i = 0;

		// while( $query->have_posts() ) {
		// 	$query->the_post();
		// 	$this->assertEquals( $this->campaigns_ordered_by_amount[$i], get_the_ID(), sprintf( 'Index %d for campaigns orderd by amount raised', $i ) );
		// 	$i++;
		// }

		// $query_2 = Charitable_Campaign_Query::ordered_by_amount( array('posts_per_page' => 1 ) );
		// $this->assertEquals( 1, count( $query_2->posts ) );
	}
}