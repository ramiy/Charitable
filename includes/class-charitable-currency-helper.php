<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Currency_Helper' ) ) : 

/**
 * Charitable Currency helper.
 *
 * @class 		Charitable_Currency_Helper
 * @version		0.1
 * @package		Charitable/Classes/Charitable_Currency_Helper
 * @category	Class
 * @author 		Studio164a
 */
final class Charitable_Currency_Helper {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * @var string $currency_format The format that the current currency will take.
	 */
	private $currency_format = array();

	/**
	 * @var string $currency The currency in use on the site.
	 */
	private $currency;

	/**
	 * Return an amount as a monetary string.
	 *
	 * 50.00 -> $50.00 
	 *
	 * @param float $amount
 	 * @return string
 	 * @access public
 	 * @since 0.1
 	 */
	public function get_monetary_amount( $amount ) {
		/**
		 * Cast amount to a float.
		 */
		$amount = floatval( $amount );
		$amount = number_format( $amount, $this->get_decimals( $amount ) );
		return sprintf( $this->get_currency_format(), $this->get_currency_symbol(), $amount );
	}

	/**
	 * Return the number of decimals to use. 
	 *
	 * @uses charitable_currency_decimal_count
	 * 
	 * @param float $amount 
	 * @return int
	 * @access public
	 * @since 0.1
	 */
	public function get_decimals( $amount = "" ) {
		return apply_filters( 'charitable_currency_decimal_count', 2, $amount );
	}

	/**
	 * Return the currency format based on the position of the currency symbol. 
	 *
	 * @uses charitable_currency_format
	 *
	 * @return string
	 * @access public
	 * @since 0.1
	 */
	public function get_currency_format() {
		if ( empty( $this->currency_format ) ) {

			$symbol_position = get_option( 'charitable_currency_symbol_position', 'left' );

			switch ( $symbol_position ) {
				case 'left': 
					$format = '%1$s%2$s';
					break;
				case 'right':
					$format = '%2$s%1$s';
					break;
				case 'left-with-space':
					$format = '%1$s&nbsp;%2$s';
					break;
				case 'right-with-space':
					$format = '%2$s&nbsp;%1$s';
					break; 
			}

			$this->currency_format = apply_filters( 'charitable_currency_format', $format, $symbol_position );
		}

		return $this->currency_format;
	}

	/**
	 * Return the currency symbol for a given currency. 
	 *
	 * Credit: This is based on the WooCommerce implemenation.
	 *
	 * @uses charitable_currency_symbol
	 * 
	 * @param string $currency 			Optional. If not set, currency is based on 
	 *	 								currently selected currency.
	 * @return string
	 * @access private
	 * @since 0.1
	 */
	private function get_currency_symbol( $currency = "" ) {

		if ( strlen( $currency ) ) {
			$currency = get_option( 'charitable_currency', 'AUD' );
		}		

		switch ( $currency ) {
			case 'AED' :
				$currency_symbol = 'د.إ';
				break;
			case 'BDT':
				$currency_symbol = '&#2547;&nbsp;';
				break;
			case 'BRL' :
				$currency_symbol = '&#82;&#36;';
				break;
			case 'BGN' :
				$currency_symbol = '&#1083;&#1074;.';
				break;
			case 'AUD' :
			case 'CAD' :
			case 'CLP' :
			case 'MXN' :
			case 'NZD' :
			case 'HKD' :
			case 'SGD' :
			case 'USD' :
				$currency_symbol = '&#36;';
				break;
			case 'EUR' :
				$currency_symbol = '&euro;';
				break;
			case 'CNY' :
			case 'RMB' :
			case 'JPY' :
				$currency_symbol = '&yen;';
				break;
			case 'RUB' :
				$currency_symbol = '&#1088;&#1091;&#1073;.';
				break;
			case 'KRW' : $currency_symbol = '&#8361;'; break;
			case 'TRY' : $currency_symbol = '&#84;&#76;'; break;
			case 'NOK' : $currency_symbol = '&#107;&#114;'; break;
			case 'ZAR' : $currency_symbol = '&#82;'; break;
			case 'CZK' : $currency_symbol = '&#75;&#269;'; break;
			case 'MYR' : $currency_symbol = '&#82;&#77;'; break;
			case 'DKK' : $currency_symbol = 'kr.'; break;
			case 'HUF' : $currency_symbol = '&#70;&#116;'; break;
			case 'IDR' : $currency_symbol = 'Rp'; break;
			case 'INR' : $currency_symbol = 'Rs.'; break;
			case 'ISK' : $currency_symbol = 'Kr.'; break;
			case 'ILS' : $currency_symbol = '&#8362;'; break;
			case 'PHP' : $currency_symbol = '&#8369;'; break;
			case 'PLN' : $currency_symbol = '&#122;&#322;'; break;
			case 'SEK' : $currency_symbol = '&#107;&#114;'; break;
			case 'CHF' : $currency_symbol = '&#67;&#72;&#70;'; break;
			case 'TWD' : $currency_symbol = '&#78;&#84;&#36;'; break;
			case 'THB' : $currency_symbol = '&#3647;'; break;
			case 'GBP' : $currency_symbol = '&pound;'; break;
			case 'RON' : $currency_symbol = 'lei'; break;
			case 'VND' : $currency_symbol = '&#8363;'; break;
			case 'NGN' : $currency_symbol = '&#8358;'; break;
			case 'HRK' : $currency_symbol = 'Kn'; break;
			default    : $currency_symbol = ''; break;
		}

		return apply_filters( 'charitable_currency_symbol', $currency_symbol, $currency );
	}
}

endif; // End class_exists check