<?php

class Charitable_UnitTestCase extends WP_UnitTestCase {

	/**
	 * Set a Charitable setting.
	 *
	 * @param 	string $setting
	 * @param 	mixed  $value
	 * @since 	1.4.0
	 */
	public function set_charitable_option( $setting, $value ) {
		$settings = get_option( 'charitable_settings' );

		$settings[ $setting ] = $value;

		update_option( 'charitable_settings', $settings );
	}

	/**
	 * Utility method that resets permalinks and flushes rewrites.
	 *
	 * As of WordPress 4.4, this methods exists in WP_UnitTestCase, but without defining
	 * it ourselves, we end up with broken tests for previous versions.
	 *
	 * @global  WP_Rewrite $wp_rewrite
	 *
	 * @param   string $structure Optional. Permalink structure to set. Default empty.
	 * @since   1.4.0
	 */
	public function set_permalink_structure( $structure = '' ) {
		global $wp_rewrite;

		$wp_rewrite->init();
		$wp_rewrite->set_permalink_structure( $structure );
		$wp_rewrite->flush_rules();
	}

	function go_to( $url ) {
		// note: the WP and WP_Query classes like to silently fetch parameters
		// from all over the place (globals, GET, etc), which makes it tricky
		// to run them more than once without very carefully clearing everything
		$_GET = $_POST = array();
		foreach ( array( 'query_string', 'id', 'postdata', 'authordata', 'day', 'currentmonth', 'page', 'pages', 'multipage', 'more', 'numpages', 'pagenow' ) as $v ) {
			if ( isset( $GLOBALS[ $v ] ) ) { unset( $GLOBALS[ $v ] ); }
		}
		$parts = parse_url( $url );
		if ( isset( $parts['scheme'] ) ) {
			$req = isset( $parts['path'] ) ? $parts['path'] : '';
			if ( isset( $parts['query'] ) ) {
				$req .= '?' . $parts['query'];
				// parse the url query vars into $_GET
				parse_str( $parts['query'], $_GET );
			}
		} else {
			$req = $url;
		}
		if ( ! isset( $parts['query'] ) ) {
			$parts['query'] = '';
		}

		$_SERVER['REQUEST_URI'] = $req;
		unset( $_SERVER['PATH_INFO'] );

		$this->flush_cache();
		unset( $GLOBALS['wp_query'], $GLOBALS['wp_the_query'] );
		$GLOBALS['wp_the_query'] = new WP_Query();
		$GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];

		$public_query_vars  = $GLOBALS['wp']->public_query_vars;
		$private_query_vars = $GLOBALS['wp']->private_query_vars;

		$GLOBALS['wp'] = new WP();
		$GLOBALS['wp']->public_query_vars  = $public_query_vars;
		$GLOBALS['wp']->private_query_vars = $private_query_vars;

		_cleanup_query_vars();

		$GLOBALS['wp']->main( $parts['query'] );
	}
}
