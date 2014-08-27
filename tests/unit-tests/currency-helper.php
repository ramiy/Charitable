<?php

class Test_Currency_Helper extends WP_UnitTestCase {
	
	private $charitable;
	private $currency_helper;

	function setUp() {
		parent::setUp();

		$this->charitable = get_charitable();
		$this->currency_helper = $this->charitable->get_currency_helper();

		update_option('charitable_currency_symbol_position', 'left');
	}

	function change_currency( $currency ) {
		update_option( 'charitable_currency', $currency ) ;
	}

	function test_get_monetary_amount() {
		$this->assertEquals( '$60.00', $this->currency_helper->get_monetary_amount('60') );
	}
	
	function test_get_decimals() {
		$this->assertEquals( '2', $this->currency_helper->get_decimals() );	
	}
	
	function test_get_currency_formats() {
		$this->assertEquals( '%1$s%2$s', $this->currency_helper->get_currency_format() );	

		update_option( 'charitable_currency_symbol_position', 'right-with-space' );  

		$this->assertEquals( '%2$s&nbsp;%1$s', $this->currency_helper->get_currency_format() );

		$this->assertEquals( '60.00 $', $this->currency_helper->get_monetary_amount('60') );

		update_option( 'charitable_currency_symbol_position', 'left' );
	}

	function test_currencies() {
		$this->change_currency( 'AED' );
		$this->assertEquals( 'د.إ60.00', $this->currency_helper->get_monetary_amount('60') );

		$this->change_currency( 'BDT' );
		$this->assertEquals( '&#2547;&nbsp;60.00', $this->currency_helper->get_monetary_amount('60') );
	}
}