<?php

class Test_Donation_Processor extends WP_UnitTestCase {

    private $donation_1;
    private $donation_2;

    private $campaign_1;
    private $campaign_2;

    private $donor_id;

    public function setUp() {
        parent::setUp();

        /* Campaign 1: $40,000 goal, 300 days till end */
        $campaign_1_id  = Charitable_Campaign_Helper::create_campaign( array( 
            'post_title'                    => 'Test Campaign 1',
            '_campaign_goal'                => 40000.00,
            '_campaign_end_date'            => date( 'Y-m-d H:i:s', strtotime( '+300 days' ) )
        ) );

        $this->campaign_1 = new Charitable_Campaign( get_post( $campaign_1_id ) );

        /* Campaign 2: $40,000 goal, 300 days till end */
        $campaign_2_id  = Charitable_Campaign_Helper::create_campaign( array( 
            'post_title'                    => 'Test Campaign 2',
            '_campaign_goal'                => 10000.00
        ) );

        $this->campaign_2 = new Charitable_Campaign( get_post( $campaign_2_id ) );

        /* Create a couple donations */
        $this->user_id = $this->factory->user->create( array( 'display_name' => 'John Henry' ) );
        $user = new Charitable_User( $this->user_id );
        $this->donor_id = $user->add_donor();
    }

    /** 
     * Test insert method first. If this fails, we can skip most of the other tests.
     */
    public function test_add_donation() {
        $donation_id = Charitable_Donation_Helper::create_donation( array(
            'campaigns' => array(
                array( 
                    'campaign_id'   => $this->campaign_1->ID,
                    'campaign_name' => 'Test Campaign', 
                    'amount'        => 10
                )
            )
        ) );
        
        $this->assertGreaterThan( 0, $donation_id );
    }

    /**
     * Test making a donation with multiple campaigns at once.
     */
    public function test_add_multi_donation() {
        $processor = Charitable_Donation_Processor::get_instance();

        $donation_id = $processor->save_donation( array(
            'user_id'       => 1, 
            'campaigns'     => array(
                array(
                    'campaign_id'   => $this->campaign_1->ID, 
                    'campaign_name' => get_the_title( $this->campaign_1 ), 
                    'amount'        => 30
                ), 
                array(
                    'campaign_id'   => $this->campaign_2->ID, 
                    'campaign_name' => get_the_title( $this->campaign_2 ), 
                    'amount'        => 40
                )
            ),
            'status'        => 'charitable-completed', 
            'gateway'       => 'manual', 
            'note'          => ''
        ) );

        $this->assertEquals( 2, $processor->save_campaign_donations( $donation_id ) );

        Charitable_Donation_Processor::destroy();
    }

    /** 
     * Test retrieving the campaign donation data.
     */
     public function test_get_campaign_donation_data() {
        $processor = Charitable_Donation_Processor::get_instance();

        $donation_id = $processor->save_donation( array(
            'user_id'       => 1, 
            'campaigns'     => array(
                array(
                    'campaign_id'   => $this->campaign_1->ID, 
                    'campaign_name' => get_the_title( $this->campaign_1 ), 
                    'amount'        => 30
                ), 
                array(
                    'campaign_id'   => $this->campaign_2->ID, 
                    'campaign_name' => get_the_title( $this->campaign_2 ), 
                    'amount'        => 40
                )
            ),
            'status'        => 'charitable-completed', 
            'gateway'       => 'manual', 
            'note'          => ''
        ) );

        $this->assertCount( 2, $processor->get_campaign_donations_data() );

        Charitable_Donation_Processor::destroy();
     }
}