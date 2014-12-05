<?php
/**
 * Contains the class that provides a utility functions relating to locales.
 *
 * @class 		Charitable_Locations
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Locations
 * @copyright 	Copyright (c) 2014, Eric Daams	
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @category	Class
 * @author 		Studio164a
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly. 

if ( ! class_exists( 'Charitable_Locations' ) ) : 

/**
 * Charitable_Locations
 *
 * @since 	1.0.0
 */
class Charitable_Locations {

	public function __construct() {	
	}

	/** 
	 * Return an array with all the countries supported by Charitable. 
	 *
	 * @return 	array
	 * @access 	public 
	 * @since 	1.0.0
	 */
	public function get_countries() {
		if ( ! isset( $this->countries ) ) {
			$this->countries = apply_filters( 'charitable_countries', array(
				'AF' => __( 'Afghanistan', 'charitable' ),
				'AX' => __( '&#197;land Islands', 'charitable' ),
				'AL' => __( 'Albania', 'charitable' ),
				'DZ' => __( 'Algeria', 'charitable' ),
				'AD' => __( 'Andorra', 'charitable' ),
				'AO' => __( 'Angola', 'charitable' ),
				'AI' => __( 'Anguilla', 'charitable' ),
				'AQ' => __( 'Antarctica', 'charitable' ),
				'AG' => __( 'Antigua and Barbuda', 'charitable' ),
				'AR' => __( 'Argentina', 'charitable' ),
				'AM' => __( 'Armenia', 'charitable' ),
				'AW' => __( 'Aruba', 'charitable' ),
				'AU' => __( 'Australia', 'charitable' ),
				'AT' => __( 'Austria', 'charitable' ),
				'AZ' => __( 'Azerbaijan', 'charitable' ),
				'BS' => __( 'Bahamas', 'charitable' ),
				'BH' => __( 'Bahrain', 'charitable' ),
				'BD' => __( 'Bangladesh', 'charitable' ),
				'BB' => __( 'Barbados', 'charitable' ),
				'BY' => __( 'Belarus', 'charitable' ),
				'BE' => __( 'Belgium', 'charitable' ),
				'PW' => __( 'Belau', 'charitable' ),
				'BZ' => __( 'Belize', 'charitable' ),
				'BJ' => __( 'Benin', 'charitable' ),
				'BM' => __( 'Bermuda', 'charitable' ),
				'BT' => __( 'Bhutan', 'charitable' ),
				'BO' => __( 'Bolivia', 'charitable' ),
				'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'charitable' ),
				'BA' => __( 'Bosnia and Herzegovina', 'charitable' ),
				'BW' => __( 'Botswana', 'charitable' ),
				'BV' => __( 'Bouvet Island', 'charitable' ),
				'BR' => __( 'Brazil', 'charitable' ),
				'IO' => __( 'British Indian Ocean Territory', 'charitable' ),
				'VG' => __( 'British Virgin Islands', 'charitable' ),
				'BN' => __( 'Brunei', 'charitable' ),
				'BG' => __( 'Bulgaria', 'charitable' ),
				'BF' => __( 'Burkina Faso', 'charitable' ),
				'BI' => __( 'Burundi', 'charitable' ),
				'KH' => __( 'Cambodia', 'charitable' ),
				'CM' => __( 'Cameroon', 'charitable' ),
				'CA' => __( 'Canada', 'charitable' ),
				'CV' => __( 'Cape Verde', 'charitable' ),
				'KY' => __( 'Cayman Islands', 'charitable' ),
				'CF' => __( 'Central African Republic', 'charitable' ),
				'TD' => __( 'Chad', 'charitable' ),
				'CL' => __( 'Chile', 'charitable' ),
				'CN' => __( 'China', 'charitable' ),
				'CX' => __( 'Christmas Island', 'charitable' ),
				'CC' => __( 'Cocos (Keeling) Islands', 'charitable' ),
				'CO' => __( 'Colombia', 'charitable' ),
				'KM' => __( 'Comoros', 'charitable' ),
				'CG' => __( 'Congo (Brazzaville)', 'charitable' ),
				'CD' => __( 'Congo (Kinshasa)', 'charitable' ),
				'CK' => __( 'Cook Islands', 'charitable' ),
				'CR' => __( 'Costa Rica', 'charitable' ),
				'HR' => __( 'Croatia', 'charitable' ),
				'CU' => __( 'Cuba', 'charitable' ),
				'CW' => __( 'Cura&Ccedil;ao', 'charitable' ),
				'CY' => __( 'Cyprus', 'charitable' ),
				'CZ' => __( 'Czech Republic', 'charitable' ),
				'DK' => __( 'Denmark', 'charitable' ),
				'DJ' => __( 'Djibouti', 'charitable' ),
				'DM' => __( 'Dominica', 'charitable' ),
				'DO' => __( 'Dominican Republic', 'charitable' ),
				'EC' => __( 'Ecuador', 'charitable' ),
				'EG' => __( 'Egypt', 'charitable' ),
				'SV' => __( 'El Salvador', 'charitable' ),
				'GQ' => __( 'Equatorial Guinea', 'charitable' ),
				'ER' => __( 'Eritrea', 'charitable' ),
				'EE' => __( 'Estonia', 'charitable' ),
				'ET' => __( 'Ethiopia', 'charitable' ),
				'FK' => __( 'Falkland Islands', 'charitable' ),
				'FO' => __( 'Faroe Islands', 'charitable' ),
				'FJ' => __( 'Fiji', 'charitable' ),
				'FI' => __( 'Finland', 'charitable' ),
				'FR' => __( 'France', 'charitable' ),
				'GF' => __( 'French Guiana', 'charitable' ),
				'PF' => __( 'French Polynesia', 'charitable' ),
				'TF' => __( 'French Southern Territories', 'charitable' ),
				'GA' => __( 'Gabon', 'charitable' ),
				'GM' => __( 'Gambia', 'charitable' ),
				'GE' => __( 'Georgia', 'charitable' ),
				'DE' => __( 'Germany', 'charitable' ),
				'GH' => __( 'Ghana', 'charitable' ),
				'GI' => __( 'Gibraltar', 'charitable' ),
				'GR' => __( 'Greece', 'charitable' ),
				'GL' => __( 'Greenland', 'charitable' ),
				'GD' => __( 'Grenada', 'charitable' ),
				'GP' => __( 'Guadeloupe', 'charitable' ),
				'GT' => __( 'Guatemala', 'charitable' ),
				'GG' => __( 'Guernsey', 'charitable' ),
				'GN' => __( 'Guinea', 'charitable' ),
				'GW' => __( 'Guinea-Bissau', 'charitable' ),
				'GY' => __( 'Guyana', 'charitable' ),
				'HT' => __( 'Haiti', 'charitable' ),
				'HM' => __( 'Heard Island and McDonald Islands', 'charitable' ),
				'HN' => __( 'Honduras', 'charitable' ),
				'HK' => __( 'Hong Kong', 'charitable' ),
				'HU' => __( 'Hungary', 'charitable' ),
				'IS' => __( 'Iceland', 'charitable' ),
				'IN' => __( 'India', 'charitable' ),
				'ID' => __( 'Indonesia', 'charitable' ),
				'IR' => __( 'Iran', 'charitable' ),
				'IQ' => __( 'Iraq', 'charitable' ),
				'IE' => __( 'Republic of Ireland', 'charitable' ),
				'IM' => __( 'Isle of Man', 'charitable' ),
				'IL' => __( 'Israel', 'charitable' ),
				'IT' => __( 'Italy', 'charitable' ),
				'CI' => __( 'Ivory Coast', 'charitable' ),
				'JM' => __( 'Jamaica', 'charitable' ),
				'JP' => __( 'Japan', 'charitable' ),
				'JE' => __( 'Jersey', 'charitable' ),
				'JO' => __( 'Jordan', 'charitable' ),
				'KZ' => __( 'Kazakhstan', 'charitable' ),
				'KE' => __( 'Kenya', 'charitable' ),
				'KI' => __( 'Kiribati', 'charitable' ),
				'KW' => __( 'Kuwait', 'charitable' ),
				'KG' => __( 'Kyrgyzstan', 'charitable' ),
				'LA' => __( 'Laos', 'charitable' ),
				'LV' => __( 'Latvia', 'charitable' ),
				'LB' => __( 'Lebanon', 'charitable' ),
				'LS' => __( 'Lesotho', 'charitable' ),
				'LR' => __( 'Liberia', 'charitable' ),
				'LY' => __( 'Libya', 'charitable' ),
				'LI' => __( 'Liechtenstein', 'charitable' ),
				'LT' => __( 'Lithuania', 'charitable' ),
				'LU' => __( 'Luxembourg', 'charitable' ),
				'MO' => __( 'Macao S.A.R., China', 'charitable' ),
				'MK' => __( 'Macedonia', 'charitable' ),
				'MG' => __( 'Madagascar', 'charitable' ),
				'MW' => __( 'Malawi', 'charitable' ),
				'MY' => __( 'Malaysia', 'charitable' ),
				'MV' => __( 'Maldives', 'charitable' ),
				'ML' => __( 'Mali', 'charitable' ),
				'MT' => __( 'Malta', 'charitable' ),
				'MH' => __( 'Marshall Islands', 'charitable' ),
				'MQ' => __( 'Martinique', 'charitable' ),
				'MR' => __( 'Mauritania', 'charitable' ),
				'MU' => __( 'Mauritius', 'charitable' ),
				'YT' => __( 'Mayotte', 'charitable' ),
				'MX' => __( 'Mexico', 'charitable' ),
				'FM' => __( 'Micronesia', 'charitable' ),
				'MD' => __( 'Moldova', 'charitable' ),
				'MC' => __( 'Monaco', 'charitable' ),
				'MN' => __( 'Mongolia', 'charitable' ),
				'ME' => __( 'Montenegro', 'charitable' ),
				'MS' => __( 'Montserrat', 'charitable' ),
				'MA' => __( 'Morocco', 'charitable' ),
				'MZ' => __( 'Mozambique', 'charitable' ),
				'MM' => __( 'Myanmar', 'charitable' ),
				'NA' => __( 'Namibia', 'charitable' ),
				'NR' => __( 'Nauru', 'charitable' ),
				'NP' => __( 'Nepal', 'charitable' ),
				'NL' => __( 'Netherlands', 'charitable' ),
				'AN' => __( 'Netherlands Antilles', 'charitable' ),
				'NC' => __( 'New Caledonia', 'charitable' ),
				'NZ' => __( 'New Zealand', 'charitable' ),
				'NI' => __( 'Nicaragua', 'charitable' ),
				'NE' => __( 'Niger', 'charitable' ),
				'NG' => __( 'Nigeria', 'charitable' ),
				'NU' => __( 'Niue', 'charitable' ),
				'NF' => __( 'Norfolk Island', 'charitable' ),
				'KP' => __( 'North Korea', 'charitable' ),
				'NO' => __( 'Norway', 'charitable' ),
				'OM' => __( 'Oman', 'charitable' ),
				'PK' => __( 'Pakistan', 'charitable' ),
				'PS' => __( 'Palestinian Territory', 'charitable' ),
				'PA' => __( 'Panama', 'charitable' ),
				'PG' => __( 'Papua New Guinea', 'charitable' ),
				'PY' => __( 'Paraguay', 'charitable' ),
				'PE' => __( 'Peru', 'charitable' ),
				'PH' => __( 'Philippines', 'charitable' ),
				'PN' => __( 'Pitcairn', 'charitable' ),
				'PL' => __( 'Poland', 'charitable' ),
				'PT' => __( 'Portugal', 'charitable' ),
				'QA' => __( 'Qatar', 'charitable' ),
				'RE' => __( 'Reunion', 'charitable' ),
				'RO' => __( 'Romania', 'charitable' ),
				'RU' => __( 'Russia', 'charitable' ),
				'RW' => __( 'Rwanda', 'charitable' ),
				'BL' => __( 'Saint Barth&eacute;lemy', 'charitable' ),
				'SH' => __( 'Saint Helena', 'charitable' ),
				'KN' => __( 'Saint Kitts and Nevis', 'charitable' ),
				'LC' => __( 'Saint Lucia', 'charitable' ),
				'MF' => __( 'Saint Martin (French part)', 'charitable' ),
				'SX' => __( 'Saint Martin (Dutch part)', 'charitable' ),
				'PM' => __( 'Saint Pierre and Miquelon', 'charitable' ),
				'VC' => __( 'Saint Vincent and the Grenadines', 'charitable' ),
				'SM' => __( 'San Marino', 'charitable' ),
				'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'charitable' ),
				'SA' => __( 'Saudi Arabia', 'charitable' ),
				'SN' => __( 'Senegal', 'charitable' ),
				'RS' => __( 'Serbia', 'charitable' ),
				'SC' => __( 'Seychelles', 'charitable' ),
				'SL' => __( 'Sierra Leone', 'charitable' ),
				'SG' => __( 'Singapore', 'charitable' ),
				'SK' => __( 'Slovakia', 'charitable' ),
				'SI' => __( 'Slovenia', 'charitable' ),
				'SB' => __( 'Solomon Islands', 'charitable' ),
				'SO' => __( 'Somalia', 'charitable' ),
				'ZA' => __( 'South Africa', 'charitable' ),
				'GS' => __( 'South Georgia/Sandwich Islands', 'charitable' ),
				'KR' => __( 'South Korea', 'charitable' ),
				'SS' => __( 'South Sudan', 'charitable' ),
				'ES' => __( 'Spain', 'charitable' ),
				'LK' => __( 'Sri Lanka', 'charitable' ),
				'SD' => __( 'Sudan', 'charitable' ),
				'SR' => __( 'Suriname', 'charitable' ),
				'SJ' => __( 'Svalbard and Jan Mayen', 'charitable' ),
				'SZ' => __( 'Swaziland', 'charitable' ),
				'SE' => __( 'Sweden', 'charitable' ),
				'CH' => __( 'Switzerland', 'charitable' ),
				'SY' => __( 'Syria', 'charitable' ),
				'TW' => __( 'Taiwan', 'charitable' ),
				'TJ' => __( 'Tajikistan', 'charitable' ),
				'TZ' => __( 'Tanzania', 'charitable' ),
				'TH' => __( 'Thailand', 'charitable' ),
				'TL' => __( 'Timor-Leste', 'charitable' ),
				'TG' => __( 'Togo', 'charitable' ),
				'TK' => __( 'Tokelau', 'charitable' ),
				'TO' => __( 'Tonga', 'charitable' ),
				'TT' => __( 'Trinidad and Tobago', 'charitable' ),
				'TN' => __( 'Tunisia', 'charitable' ),
				'TR' => __( 'Turkey', 'charitable' ),
				'TM' => __( 'Turkmenistan', 'charitable' ),
				'TC' => __( 'Turks and Caicos Islands', 'charitable' ),
				'TV' => __( 'Tuvalu', 'charitable' ),
				'UG' => __( 'Uganda', 'charitable' ),
				'UA' => __( 'Ukraine', 'charitable' ),
				'AE' => __( 'United Arab Emirates', 'charitable' ),
				'GB' => __( 'United Kingdom (UK)', 'charitable' ),
				'US' => __( 'United States (US)', 'charitable' ),
				'UY' => __( 'Uruguay', 'charitable' ),
				'UZ' => __( 'Uzbekistan', 'charitable' ),
				'VU' => __( 'Vanuatu', 'charitable' ),
				'VA' => __( 'Vatican', 'charitable' ),
				'VE' => __( 'Venezuela', 'charitable' ),
				'VN' => __( 'Vietnam', 'charitable' ),
				'WF' => __( 'Wallis and Futuna', 'charitable' ),
				'EH' => __( 'Western Sahara', 'charitable' ),
				'WS' => __( 'Western Samoa', 'charitable' ),
				'YE' => __( 'Yemen', 'charitable' ),
				'ZM' => __( 'Zambia', 'charitable' ),
				'ZW' => __( 'Zimbabwe', 'charitable' )
			) );	
		}	

		return $this->countries;
	}

	/**
	 * Get the base country for the website.
	 *	 
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function get_base_country() {
		$default = esc_attr( get_option( 'charitable_default_country' ) );
		$country = ( ( $pos = strrpos( $default, ':' ) ) === false ) ? $default : substr( $default, 0, $pos );

		return apply_filters( 'charitable_countries_base_country', $country );
	}

	/**
	 * Get country address formats.
	 *
	 * @return 	array
 	 * @access 	public
 	 * @since 	1.0.0
	 */
	public function get_address_formats() {
		if ( ! isset( $this->address_formats ) ) {

			// Common formats
			$postcode_before_city = "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}";

			// Define address formats
			$this->address_formats = apply_filters('charitable_localisation_address_formats', array(
				'default' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}",
				'AU' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'AT' => $postcode_before_city,
				'BE' => $postcode_before_city,
				'CA' => "{company}\n{name}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'CH' => $postcode_before_city,
				'CN' => "{country} {postcode}\n{state}, {city}, {address_2}, {address_1}\n{company}\n{name}",
				'CZ' => $postcode_before_city,
				'DE' => $postcode_before_city,
				'EE' => $postcode_before_city,
				'FI' => $postcode_before_city,
				'DK' => $postcode_before_city,
				'FR' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city_upper}\n{country}",
				'HK' => "{company}\n{first_name} {last_name_upper}\n{address_1}\n{address_2}\n{city_upper}\n{state_upper}\n{country}",
				'HU' => "{name}\n{company}\n{city}\n{address_1}\n{address_2}\n{postcode}\n{country}",
				'IS' => $postcode_before_city,
				'IT' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode}\n{city}\n{state_upper}\n{country}",
				'JP' => "{postcode}\n{state}{city}{address_1}\n{address_2}\n{company}\n{last_name} {first_name}\n {country}",
				'TW' => "{postcode}\n{city}{address_2}\n{address_1}\n{company}\n{last_name} {first_name}\n {country}",
				'LI' => $postcode_before_city,
				'NL' => $postcode_before_city,
				'NZ' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {postcode}\n{country}",
				'NO' => $postcode_before_city,
				'PL' => $postcode_before_city,
				'SK' => $postcode_before_city,
				'SI' => $postcode_before_city,
				'ES' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}",
				'SE' => $postcode_before_city,
				'TR' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city} {state}\n{country}",
				'US' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state_code} {postcode}\n{country}",
				'VN' => "{name}\n{company}\n{address_1}\n{city}\n{country}",
			));
		
		}

		return $this->address_formats;
	}

	/**
	* Get country address format.
	*
	* @param 	array 		$address_fields
	* @return 	string
	* @access 	public
	* @since 	1.0.0	
	*/
	public function get_formatted_address( $address_fields = array() ) {

		$address_fields = array_map( 'trim', $address_fields );

		// echo '<pre>'; print_r( $address_fields );

		// die;

		// Get all formats
		$formats 		= $this->get_address_formats();

		// Get format for the address' country
		$format			= ( $address_fields['country'] && isset( $formats[ $address_fields['country'] ] ) ) ? $formats[ $address_fields['country'] ] : $formats['default'];

		// Handle full country name
		$full_country 	= ( isset( $this->countries[ $address_fields['country'] ] ) ) ? $this->countries[ $address_fields['country'] ] : $address_fields['country'];

		// Country is not needed if the same as base
		if ( $address_fields['country'] == $this->get_base_country() && ! apply_filters( 'charitable_formatted_address_force_country_display', false ) ) {
			$format = str_replace( '{country}', '', $format );
		}

		// Handle full state name
		$full_state		= ( $address_fields['country'] && $address_fields['state'] && isset( $this->states[ $address_fields['country'] ][ $address_fields['state'] ] ) ) ? $this->states[ $address_fields['country'] ][ $address_fields['state'] ] : $address_fields['state'];

		// Substitute address parts into the string
		$replace = array_map( 'esc_html', apply_filters( 'charitable_formatted_address_replacements', array(
			'{first_name}'       => $address_fields['first_name'],
			'{last_name}'        => $address_fields['last_name'],
			'{name}'             => $address_fields['first_name'] . ' ' . $address_fields['last_name'],
			'{company}'          => $address_fields['company'],
			'{address_1}'        => $address_fields['address'],
			'{address_2}'        => $address_fields['address_2'],
			'{city}'             => $address_fields['city'],
			'{state}'            => $full_state,
			'{postcode}'         => $address_fields['postcode'],
			'{country}'          => $full_country,
			'{first_name_upper}' => strtoupper( $address_fields['first_name'] ),
			'{last_name_upper}'  => strtoupper( $address_fields['last_name'] ),
			'{name_upper}'       => strtoupper( $address_fields['first_name'] . ' ' . $address_fields['last_name'] ),
			'{company_upper}'    => strtoupper( $address_fields['company'] ),
			'{address_1_upper}'  => strtoupper( $address_fields['address'] ),
			'{address_2_upper}'  => strtoupper( $address_fields['address_2'] ),
			'{city_upper}'       => strtoupper( $address_fields['city'] ),
			'{state_upper}'      => strtoupper( $full_state ),
			'{state_code}'       => strtoupper( $address_fields['state'] ),
			'{postcode_upper}'   => strtoupper( $address_fields['postcode'] ),
			'{country_upper}'    => strtoupper( $full_country ),
		), $address_fields ) );

		// echo '<pre>'; 
		// print_r( $replace );

		// echo $format;

		$formatted_address = str_replace( array_keys( $replace ), $replace, $format );

		// echo '1: ' . $formatted_address . PHP_EOL;

		// Clean up white space
		$formatted_address = preg_replace( '/  +/', ' ', trim( $formatted_address ) );
		// echo '2: ' . $formatted_address . PHP_EOL;
		$formatted_address = preg_replace( '/\n\n+/', "\n", $formatted_address );
		// echo '3: ' . $formatted_address . PHP_EOL;

		// Break newlines apart and remove empty lines/trim commas and white space
		$formatted_address = array_filter( array_map( array( $this, 'trim_formatted_address_line' ), explode( "\n", $formatted_address ) ) );

		// print_r( $formatted_address );

		// Add html breaks
		$formatted_address = implode( '<br/>', $formatted_address );

		// echo $formatted_address . PHP_EOL;

		// die;

		// We're done!
		return $formatted_address;
	}

	/**
	 * trim white space and commas off a line. 
	 *
	 * @param  	string 		$line
	 * @return 	string
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function trim_formatted_address_line( $line ) {
		return trim( $line, ", " );
	}
}



endif; // End class_exists check