<?php

class Test_Charitable_Campaign_Query extends WP_UnitTestCase {

	private $post;

	private $campaigns;
	private $campaigns_ordered_by_ending_soon;
	private $campaigns_ordered_by_amount;

	function setUp() {
		parent::setUp();

		/* User */
		$user_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );

		/**
		 * Campaign 1: 
		 *
		 * End date: 			300 days from now
		 * Donations received: 	$1000
		 */
		$campaign_1_id = Charitable_Campaign_Helper::create_campaign( array( 
			'_campaign_end_date' 	=> date( 'Y-m-d H:i:s', strtotime( '+300 days') )
		) );

		Charitable_Donation_Helper::create_campaign_donation_for_user( $user_id, $campaign_1_id, 1000 );

		/**
		 * Campaign 2: 
		 *
		 * End date: 			100 days from now
		 * Donations received: 	$50
		 */
		$campaign_2_id = Charitable_Campaign_Helper::create_campaign( array( 
			'_campaign_end_date' 	=> date( 'Y-m-d H:i:s', strtotime( '+100 days') )
		) );

		Charitable_Donation_Helper::create_campaign_donation_for_user( $user_id, $campaign_2_id, 50 );

		/**
		 * Campaign 3: 
		 *
		 * End date: 			2 days from now
		 * Donations received: 	$200
		 */
		$campaign_3_id = Charitable_Campaign_Helper::create_campaign( array( 
			'_campaign_end_date' 	=> date( 'Y-m-d H:i:s', strtotime( '+2 days') )
		) );

		Charitable_Donation_Helper::create_campaign_donation_for_user( $user_id, $campaign_3_id, 200 );

		/**
		 * Campaign 4: 
		 *
		 * End date: 			2 days ago
		 * Donations received: 	$40
		 */
		$campaign_4_id = Charitable_Campaign_Helper::create_campaign( array( 
			'_campaign_end_date' 	=> date( 'Y-m-d H:i:s', strtotime( '-2 days') )
		) );

		Charitable_Donation_Helper::create_campaign_donation_for_user( $user_id, $campaign_4_id, 40 );

		/* The array of campaign IDs */
		$this->campaigns = array( 
			$campaign_1_id, 
			$campaign_2_id, 
			$campaign_3_id, 
			$campaign_4_id
		);

		/* The array of campaign IDs, ordered by ending soon */
		$this->campaigns_ordered_by_ending_soon = array(
			$campaign_3_id, 
			$campaign_2_id,
			$campaign_1_id
		);

		/* The array of campaign IDs, ordered by amount raised */
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