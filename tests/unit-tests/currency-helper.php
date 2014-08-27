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
		$this->assertEquals( '&#36;60.00', $this->currency_helper->get_monetary_amount('60') );
	}
	
	function test_get_decimals() {
		$this->assertEquals( '2', $this->currency_helper->get_decimals() );	
	}
	
	function test_get_currency_formats() {
		$this->assertEquals( '%1$s%2$s', $this->currency_helper->get_currency_format() );	

		update_option( 'charitable_currency_symbol_position', 'right-with-space' );  

		$this->assertEquals( '%2$s&nbsp;%1$s', $this->currency_helper->get_currency_format() );

		$this->assertEquals( '60.00&nbsp;&#36;', $this->currency_helper->get_monetary_amount('60') );

		update_option( 'charitable_currency_symbol_position', 'left' );
	}

	function test_currencies() {

		$assertions = array(
				array('AED','د.إ'),
				array('BDT','৳'),
				array('BRL','R$'),
				array('BGN','лв.'),
				array('AUD','$'),
				array('USD','$'),
				array('EUR','€'),
				array('JPY','¥'),
				array('RUB','руб.'),
				array('KRW','₩'),
				array('TRY','₺'),
				array('NOK','kr'),
				array('ZAR','R'),
				array('CZK','Kč'),
				array('MYR','RM'),
				array('DKK','kr.'),
				array('HUF','Ft'),
				array('IDR','Rp'),
				array('INR','Rs.'),
				array('ISK','Kr.'),
				array('ILS','₪'),
				array('PHP','₱'),
				array('PLN','zł'),
				array('SEK','kr'),
				array('CHF','CHF'),
				array('TWD','NT$'),
				array('THB','฿'),
				array('GBP','£'),
				array('RON','lei'),
				array('VND','₫'),
				array('NGN','₦'),
				array('HRK','Kn'),
				array('',''),
				array('AN_UNLISTED_VALUE','')
			);

		foreach($assertions as $pair){
			$this->change_currency( $pair[0] );

			$amount = $this->currency_helper->get_monetary_amount(60);

			$this->assertEquals( $pair[1].'60.00', html_entity_decode($amount, ENT_QUOTES, 'UTF-8'), 'test_currencies_'.$pair[0]);
		}

	}
}