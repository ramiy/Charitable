<?php

class Test_Charitable_Locations extends WP_UnitTestCase {

	/**
	 * For this collection of test, we set up our default country as Australia. 
	 */
	function setUp() {
		parent::setUp();		
	}

	function test_get_countries() {
		$countries = charitable_get_location_helper()->get_countries();
		$this->assertArrayHasKey( 'AF', $countries );
		$this->assertArrayHasKey( 'AX', $countries );
		$this->assertArrayHasKey( 'AL', $countries );
		$this->assertArrayHasKey( 'DZ', $countries );
		$this->assertArrayHasKey( 'AD', $countries );
		$this->assertArrayHasKey( 'AO', $countries );
		$this->assertArrayHasKey( 'AI', $countries );
		$this->assertArrayHasKey( 'AQ', $countries );
		$this->assertArrayHasKey( 'AG', $countries );
		$this->assertArrayHasKey( 'AR', $countries );
		$this->assertArrayHasKey( 'AM', $countries );
		$this->assertArrayHasKey( 'AW', $countries );
		$this->assertArrayHasKey( 'AU', $countries );
		$this->assertArrayHasKey( 'AT', $countries );
		$this->assertArrayHasKey( 'AZ', $countries );
		$this->assertArrayHasKey( 'BS', $countries );
		$this->assertArrayHasKey( 'BH', $countries );
		$this->assertArrayHasKey( 'BD', $countries );
		$this->assertArrayHasKey( 'BB', $countries );
		$this->assertArrayHasKey( 'BY', $countries );
		$this->assertArrayHasKey( 'BE', $countries );
		$this->assertArrayHasKey( 'PW', $countries );
		$this->assertArrayHasKey( 'BZ', $countries );
		$this->assertArrayHasKey( 'BJ', $countries );
		$this->assertArrayHasKey( 'BM', $countries );
		$this->assertArrayHasKey( 'BT', $countries );
		$this->assertArrayHasKey( 'BO', $countries );
		$this->assertArrayHasKey( 'BQ', $countries );
		$this->assertArrayHasKey( 'BA', $countries );
		$this->assertArrayHasKey( 'BW', $countries );
		$this->assertArrayHasKey( 'BV', $countries );
		$this->assertArrayHasKey( 'BR', $countries );
		$this->assertArrayHasKey( 'IO', $countries );
		$this->assertArrayHasKey( 'VG', $countries );
		$this->assertArrayHasKey( 'BN', $countries );
		$this->assertArrayHasKey( 'BG', $countries );
		$this->assertArrayHasKey( 'BF', $countries );
		$this->assertArrayHasKey( 'BI', $countries );
		$this->assertArrayHasKey( 'KH', $countries );
		$this->assertArrayHasKey( 'CM', $countries );
		$this->assertArrayHasKey( 'CA', $countries );
		$this->assertArrayHasKey( 'CV', $countries );
		$this->assertArrayHasKey( 'KY', $countries );
		$this->assertArrayHasKey( 'CF', $countries );
		$this->assertArrayHasKey( 'TD', $countries );
		$this->assertArrayHasKey( 'CL', $countries );
		$this->assertArrayHasKey( 'CN', $countries );
		$this->assertArrayHasKey( 'CX', $countries );
		$this->assertArrayHasKey( 'CC', $countries );
		$this->assertArrayHasKey( 'CO', $countries );
		$this->assertArrayHasKey( 'KM', $countries );
		$this->assertArrayHasKey( 'CG', $countries );
		$this->assertArrayHasKey( 'CD', $countries );
		$this->assertArrayHasKey( 'CK', $countries );
		$this->assertArrayHasKey( 'CR', $countries );
		$this->assertArrayHasKey( 'HR', $countries );
		$this->assertArrayHasKey( 'CU', $countries );
		$this->assertArrayHasKey( 'CW', $countries );
		$this->assertArrayHasKey( 'CY', $countries );
		$this->assertArrayHasKey( 'CZ', $countries );
		$this->assertArrayHasKey( 'DK', $countries );
		$this->assertArrayHasKey( 'DJ', $countries );
		$this->assertArrayHasKey( 'DM', $countries );
		$this->assertArrayHasKey( 'DO', $countries );
		$this->assertArrayHasKey( 'EC', $countries );
		$this->assertArrayHasKey( 'EG', $countries );
		$this->assertArrayHasKey( 'SV', $countries );
		$this->assertArrayHasKey( 'GQ', $countries );
		$this->assertArrayHasKey( 'ER', $countries );
		$this->assertArrayHasKey( 'EE', $countries );
		$this->assertArrayHasKey( 'ET', $countries );
		$this->assertArrayHasKey( 'FK', $countries );
		$this->assertArrayHasKey( 'FO', $countries );
		$this->assertArrayHasKey( 'FJ', $countries );
		$this->assertArrayHasKey( 'FI', $countries );
		$this->assertArrayHasKey( 'FR', $countries );
		$this->assertArrayHasKey( 'GF', $countries );
		$this->assertArrayHasKey( 'PF', $countries );
		$this->assertArrayHasKey( 'TF', $countries );
		$this->assertArrayHasKey( 'GA', $countries );
		$this->assertArrayHasKey( 'GM', $countries );
		$this->assertArrayHasKey( 'GE', $countries );
		$this->assertArrayHasKey( 'DE', $countries );
		$this->assertArrayHasKey( 'GH', $countries );
		$this->assertArrayHasKey( 'GI', $countries );
		$this->assertArrayHasKey( 'GR', $countries );
		$this->assertArrayHasKey( 'GL', $countries );
		$this->assertArrayHasKey( 'GD', $countries );
		$this->assertArrayHasKey( 'GP', $countries );
		$this->assertArrayHasKey( 'GT', $countries );
		$this->assertArrayHasKey( 'GG', $countries );
		$this->assertArrayHasKey( 'GN', $countries );
		$this->assertArrayHasKey( 'GW', $countries );
		$this->assertArrayHasKey( 'GY', $countries );
		$this->assertArrayHasKey( 'HT', $countries );
		$this->assertArrayHasKey( 'HM', $countries );
		$this->assertArrayHasKey( 'HN', $countries );
		$this->assertArrayHasKey( 'HK', $countries );
		$this->assertArrayHasKey( 'HU', $countries );
		$this->assertArrayHasKey( 'IS', $countries );
		$this->assertArrayHasKey( 'IN', $countries );
		$this->assertArrayHasKey( 'ID', $countries );
		$this->assertArrayHasKey( 'IR', $countries );
		$this->assertArrayHasKey( 'IQ', $countries );
		$this->assertArrayHasKey( 'IE', $countries );
		$this->assertArrayHasKey( 'IM', $countries );
		$this->assertArrayHasKey( 'IL', $countries );
		$this->assertArrayHasKey( 'IT', $countries );
		$this->assertArrayHasKey( 'CI', $countries );
		$this->assertArrayHasKey( 'JM', $countries );
		$this->assertArrayHasKey( 'JP', $countries );
		$this->assertArrayHasKey( 'JE', $countries );
		$this->assertArrayHasKey( 'JO', $countries );
		$this->assertArrayHasKey( 'KZ', $countries );
		$this->assertArrayHasKey( 'KE', $countries );
		$this->assertArrayHasKey( 'KI', $countries );
		$this->assertArrayHasKey( 'KW', $countries );
		$this->assertArrayHasKey( 'KG', $countries );
		$this->assertArrayHasKey( 'LA', $countries );
		$this->assertArrayHasKey( 'LV', $countries );
		$this->assertArrayHasKey( 'LB', $countries );
		$this->assertArrayHasKey( 'LS', $countries );
		$this->assertArrayHasKey( 'LR', $countries );
		$this->assertArrayHasKey( 'LY', $countries );
		$this->assertArrayHasKey( 'LI', $countries );
		$this->assertArrayHasKey( 'LT', $countries );
		$this->assertArrayHasKey( 'LU', $countries );
		$this->assertArrayHasKey( 'MO', $countries );
		$this->assertArrayHasKey( 'MK', $countries );
		$this->assertArrayHasKey( 'MG', $countries );
		$this->assertArrayHasKey( 'MW', $countries );
		$this->assertArrayHasKey( 'MY', $countries );
		$this->assertArrayHasKey( 'MV', $countries );
		$this->assertArrayHasKey( 'ML', $countries );
		$this->assertArrayHasKey( 'MT', $countries );
		$this->assertArrayHasKey( 'MH', $countries );
		$this->assertArrayHasKey( 'MQ', $countries );
		$this->assertArrayHasKey( 'MR', $countries );
		$this->assertArrayHasKey( 'MU', $countries );
		$this->assertArrayHasKey( 'YT', $countries );
		$this->assertArrayHasKey( 'MX', $countries );
		$this->assertArrayHasKey( 'FM', $countries );
		$this->assertArrayHasKey( 'MD', $countries );
		$this->assertArrayHasKey( 'MC', $countries );
		$this->assertArrayHasKey( 'MN', $countries );
		$this->assertArrayHasKey( 'ME', $countries );
		$this->assertArrayHasKey( 'MS', $countries );
		$this->assertArrayHasKey( 'MA', $countries );
		$this->assertArrayHasKey( 'MZ', $countries );
		$this->assertArrayHasKey( 'MM', $countries );
		$this->assertArrayHasKey( 'NA', $countries );
		$this->assertArrayHasKey( 'NR', $countries );
		$this->assertArrayHasKey( 'NP', $countries );
		$this->assertArrayHasKey( 'NL', $countries );
		$this->assertArrayHasKey( 'AN', $countries );
		$this->assertArrayHasKey( 'NC', $countries );
		$this->assertArrayHasKey( 'NZ', $countries );
		$this->assertArrayHasKey( 'NI', $countries );
		$this->assertArrayHasKey( 'NE', $countries );
		$this->assertArrayHasKey( 'NG', $countries );
		$this->assertArrayHasKey( 'NU', $countries );
		$this->assertArrayHasKey( 'NF', $countries );
		$this->assertArrayHasKey( 'KP', $countries );
		$this->assertArrayHasKey( 'NO', $countries );
		$this->assertArrayHasKey( 'OM', $countries );
		$this->assertArrayHasKey( 'PK', $countries );
		$this->assertArrayHasKey( 'PS', $countries );
		$this->assertArrayHasKey( 'PA', $countries );
		$this->assertArrayHasKey( 'PG', $countries );
		$this->assertArrayHasKey( 'PY', $countries );
		$this->assertArrayHasKey( 'PE', $countries );
		$this->assertArrayHasKey( 'PH', $countries );
		$this->assertArrayHasKey( 'PN', $countries );
		$this->assertArrayHasKey( 'PL', $countries );
		$this->assertArrayHasKey( 'PT', $countries );
		$this->assertArrayHasKey( 'QA', $countries );
		$this->assertArrayHasKey( 'RE', $countries );
		$this->assertArrayHasKey( 'RO', $countries );
		$this->assertArrayHasKey( 'RU', $countries );
		$this->assertArrayHasKey( 'RW', $countries );
		$this->assertArrayHasKey( 'BL', $countries );
		$this->assertArrayHasKey( 'SH', $countries );
		$this->assertArrayHasKey( 'KN', $countries );
		$this->assertArrayHasKey( 'LC', $countries );
		$this->assertArrayHasKey( 'MF', $countries );
		$this->assertArrayHasKey( 'SX', $countries );
		$this->assertArrayHasKey( 'PM', $countries );
		$this->assertArrayHasKey( 'VC', $countries );
		$this->assertArrayHasKey( 'SM', $countries );
		$this->assertArrayHasKey( 'ST', $countries );
		$this->assertArrayHasKey( 'SA', $countries );
		$this->assertArrayHasKey( 'SN', $countries );
		$this->assertArrayHasKey( 'RS', $countries );
		$this->assertArrayHasKey( 'SC', $countries );
		$this->assertArrayHasKey( 'SL', $countries );
		$this->assertArrayHasKey( 'SG', $countries );
		$this->assertArrayHasKey( 'SK', $countries );
		$this->assertArrayHasKey( 'SI', $countries );
		$this->assertArrayHasKey( 'SB', $countries );
		$this->assertArrayHasKey( 'SO', $countries );
		$this->assertArrayHasKey( 'ZA', $countries );
		$this->assertArrayHasKey( 'GS', $countries );
		$this->assertArrayHasKey( 'KR', $countries );
		$this->assertArrayHasKey( 'SS', $countries );
		$this->assertArrayHasKey( 'ES', $countries );
		$this->assertArrayHasKey( 'LK', $countries );
		$this->assertArrayHasKey( 'SD', $countries );
		$this->assertArrayHasKey( 'SR', $countries );
		$this->assertArrayHasKey( 'SJ', $countries );
		$this->assertArrayHasKey( 'SZ', $countries );
		$this->assertArrayHasKey( 'SE', $countries );
		$this->assertArrayHasKey( 'CH', $countries );
		$this->assertArrayHasKey( 'SY', $countries );
		$this->assertArrayHasKey( 'TW', $countries );
		$this->assertArrayHasKey( 'TJ', $countries );
		$this->assertArrayHasKey( 'TZ', $countries );
		$this->assertArrayHasKey( 'TH', $countries );
		$this->assertArrayHasKey( 'TL', $countries );
		$this->assertArrayHasKey( 'TG', $countries );
		$this->assertArrayHasKey( 'TK', $countries );
		$this->assertArrayHasKey( 'TO', $countries );
		$this->assertArrayHasKey( 'TT', $countries );
		$this->assertArrayHasKey( 'TN', $countries );
		$this->assertArrayHasKey( 'TR', $countries );
		$this->assertArrayHasKey( 'TM', $countries );
		$this->assertArrayHasKey( 'TC', $countries );
		$this->assertArrayHasKey( 'TV', $countries );
		$this->assertArrayHasKey( 'UG', $countries );
		$this->assertArrayHasKey( 'UA', $countries );
		$this->assertArrayHasKey( 'AE', $countries );
		$this->assertArrayHasKey( 'GB', $countries );
		$this->assertArrayHasKey( 'US', $countries );
		$this->assertArrayHasKey( 'UY', $countries );
		$this->assertArrayHasKey( 'UZ', $countries );
		$this->assertArrayHasKey( 'VU', $countries );
		$this->assertArrayHasKey( 'VA', $countries );
		$this->assertArrayHasKey( 'VE', $countries );
		$this->assertArrayHasKey( 'VN', $countries );
		$this->assertArrayHasKey( 'WF', $countries );
		$this->assertArrayHasKey( 'EH', $countries );
		$this->assertArrayHasKey( 'WS', $countries );
		$this->assertArrayHasKey( 'YE', $countries );
		$this->assertArrayHasKey( 'ZM', $countries );
		$this->assertArrayHasKey( 'ZW', $countries );
	}

	function test_get_base_country() {		
		add_option( 'charitable_default_country', 'AU' );
		$this->assertEquals( 'AU', charitable_get_location_helper()->get_base_country() );
	}

	function test_get_address_formats() {
		$formats = charitable_get_location_helper()->get_address_formats();
		$this->assertArrayHasKey( 'default', $formats );
		$this->assertArrayHasKey( 'AU', $formats );
		$this->assertArrayHasKey( 'AT', $formats );
		$this->assertArrayHasKey( 'BE', $formats );
		$this->assertArrayHasKey( 'CA', $formats );
		$this->assertArrayHasKey( 'CH', $formats );
		$this->assertArrayHasKey( 'CN', $formats );
		$this->assertArrayHasKey( 'CZ', $formats );
		$this->assertArrayHasKey( 'DE', $formats );
		$this->assertArrayHasKey( 'EE', $formats );
		$this->assertArrayHasKey( 'FI', $formats );
		$this->assertArrayHasKey( 'DK', $formats );
		$this->assertArrayHasKey( 'FR', $formats );
		$this->assertArrayHasKey( 'HK', $formats );
		$this->assertArrayHasKey( 'HU', $formats );
		$this->assertArrayHasKey( 'IS', $formats );
		$this->assertArrayHasKey( 'IT', $formats );
		$this->assertArrayHasKey( 'JP', $formats );
		$this->assertArrayHasKey( 'TW', $formats );
		$this->assertArrayHasKey( 'LI', $formats );
		$this->assertArrayHasKey( 'NL', $formats );
		$this->assertArrayHasKey( 'NZ', $formats );
		$this->assertArrayHasKey( 'NO', $formats );
		$this->assertArrayHasKey( 'PL', $formats );
		$this->assertArrayHasKey( 'SK', $formats );
		$this->assertArrayHasKey( 'SI', $formats );
		$this->assertArrayHasKey( 'ES', $formats );
		$this->assertArrayHasKey( 'SE', $formats );
		$this->assertArrayHasKey( 'TR', $formats );
		$this->assertArrayHasKey( 'US', $formats );
		$this->assertArrayHasKey( 'VN', $formats );
	}

	function test_get_formatted_address() {
		add_option( 'charitable_default_country', 'AU' );

		$address_fields = array(
			'first_name'	=> 'James',
			'last_name'		=> 'Gordon',
			'company'		=> 'Gotham City Police Department',
			'address'		=> 'Unit 3',
			'address_2'		=> '22 Batman Avenue',
			'city'			=> 'Gotham',
			'postcode'		=> '29292',
			'state'			=> 'Gotham State',
			'country'		=> 'US'
		);

		// "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state_code} {postcode}\n{country}"
		$expected = "James Gordon<br/>Gotham City Police Department<br/>Unit 3<br/>22 Batman Avenue<br/>Gotham, GOTHAM STATE 29292<br/>United States (US)";

		$this->assertEquals( $expected, charitable_get_location_helper()->get_formatted_address( $address_fields ) );

		// Second address. Within country, with state code. 
		$address_fields = array(
			'first_name'	=> 'Jack',
			'last_name'		=> 'Daniels',
			'company'		=> 'Jack Daniel\'s Tennessee Whiskey',
			'address'		=> '299 Smith Street',
			'address_2'		=> '',
			'city'			=> 'Darwin',
			'postcode'		=> '0800',
			'state'			=> 'NT',
			'country'		=> 'AU'
		);

		// "{name}\n{company}\n{address_1}\n{address_2}\n{city} {state} {postcode}",
		$expected = "Jack Daniels<br/>Jack Daniel&#039;s Tennessee Whiskey<br/>299 Smith Street<br/>Darwin Northern Territory 0800";

		$this->assertEquals( $expected, charitable_get_location_helper()->get_formatted_address( $address_fields ) );
	}
}