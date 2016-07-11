<?php

class Charitable_UnitTestCase extends WP_UnitTestCase {

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

}
