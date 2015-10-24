<?php 
/**
 * Charitable Template Helpers. 
 *
 * Functions used to assist with rendering templates.
 * 
 * @package     Charitable/Functions/Templates
 * @version     1.2.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Simple CSS compression. 
 *
 * Removes all comments, removes spaces after colons and strips out all the whitespace. 
 *
 * Based on http://manas.tungare.name/software/css-compression-in-php/
 *
 * @param   string $css The block of CSS to be compressed. 
 * @return  string The compressed CSS
 * @since   1.2.0
 */ 
function charitable_compress_css( $css ) {
    /* 1. Remove comments */
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

    /* 2. Remove space after colons */
    $css = str_replace(': ', ':', $css);
     
    /* 3. Remove whitespace */
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

    return $css;
}