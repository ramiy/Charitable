<?php

/**
 * Contains tests for functions added in `includes/donations/charitable-donation-functions.php`. 
 */

class Test_Charitable_Donation_Functions extends WP_UnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    /**
     * @covers charitable_create_donation
     */
    public function test_create_donation() {

        $donation_id = $this->create_donation();

        $this->assertGreaterThan( 0, $donation_id );

    }

    /**
     * @covers charitable_create_donation
     * @depends test_create_donation
     */
    public function test_create_donation_correct_gateway() {

        $donation_id = $this->create_donation();

        $this->assertEquals( 'manual', charitable_get_donation_gateway( $donation_id ) );

    }

    /**
     * @covers charitable_create_donation
     * @depends test_create_donation
     */
    public function test_create_donation_correct_amount() {

        $donation_id = $this->create_donation();

        $this->assertEquals( 50, charitable_get_donation( $donation_id )->get_total_donation_amount( false ) );

    }

    /**
     * @covers charitable_create_donation
     * @depends test_create_donation
     */
    public function test_create_donation_correct_user() {

        $donation_id = $this->create_donation();

        $this->assertEquals( 'Matthew Murdoch', charitable_get_donation( $donation_id )->get_donor() );

    }

    /**
     * Create a donation using `charitable_create_donation()`
     *
     * @return  int The donation ID
     */
    private function create_donation() {

        $campaign_id = Charitable_Campaign_Helper::create_campaign();

        $args = array(            
            'status' => 'charitable-completed', 
            'gateway' => 'manual', 
            'note' => '',
            'campaigns' => array(
                array( 
                    'campaign_id' => $campaign_id,
                    'amount' => 50,
                    'campaign_name' => 'Test Campaign'
                )
            ), 
            'user' => array(
                'first_name' => 'Matthew',
                'last_name' => 'Murdoch',
                'email' => 'matthew.murdoch@example.com'
            )
        );

        return charitable_create_donation( $args );

    }
}