<?php 
/**
 * Adds wrapper elements to generic templates like the campaign archive template.
 *
 * We provide base wrappers for all the default themes from Twenty Twelve onwards. The
 * default wrapper is used on Twenty Fifteen, Twenty Sixteen and is the base wrapper
 * for Underscores.
 *
 * If you notice problems with your theme, you can override this template by copying 
 * it to yourtheme/charitable/global/wrapper-end.php
 *
 * @author  Studio 164a
 * @package Charitable/Templates/Global
 * @since   1.3.0
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$template = get_option( 'template' );

switch( $template ) :     
    case 'twentytwelve' :
    case 'twentythirteen' :
    case 'twentyfourteen' :
        echo '</div><!-- #primary --></div><!-- #content -->';
        break;
    default :
        echo '</div><!-- #primary --></main><!-- #main -->'; // Twenty Fifteen, Twenty Sixteen and Underscores
        break;
    case '' : 
        break;
endswitch;