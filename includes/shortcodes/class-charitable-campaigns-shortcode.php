<?php
/**
 * Campaigns shortcode class.
 * 
 * @version     1.0.0
 * @package     Charitable/Shortcodes/Campaigns
 * @category    Class
 * @author      Eric Daams
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Campaigns_Shortcode' ) ) : 

/**
 * Charitable_Campaigns_Shortcode class. 
 *
 * @since       1.0.0
 */
class Charitable_Campaigns_Shortcode {

    /**
     * The callback method for the campaigns shortcode.
     *
     * This receives the user-defined attributes and passes the logic off to the class. 
     *
     * @param   array       $atts       User-defined shortcode attributes.
     * @return  string
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function display( $atts ) {
        $default = array(
            'orderby'   => 'post_date',
            'number'    => get_option( 'posts_per_page' ), 
            'category'  => '',
            'creator'   => '', 
            'columns'   => 1
        );

        $args = shortcode_atts( $default, $atts, 'campaigns' );
        $args[ 'campaigns' ] = self::get_campaigns( $args );

        ob_start();        

        charitable_template( 'shortcodes/campaigns.php', $args );

        return apply_filters( 'charitable_campaigns_shortcode', ob_get_clean() );
    }

    /**
     * Return campaigns to display in the campaigns shortcode. 
     *
     * @param   array   $args
     * @return  WP_Query
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function get_campaigns( $args ) {
        $query_args = array(
            'posts_per_page' => $args[ 'number' ]            
        );

        /* Set category constraint */
        if ( ! empty( $args[ 'category' ] ) ) {
            $query_args[ 'tax_query' ] = array(
                array(
                    'taxonomy'  => 'campaign_category',
                    'field'     => 'slug',
                    'terms'     => $args[ 'category' ]
                )
            );
        }

        /* Set author constraint */
        if ( ! empty( $args[ 'creator' ] ) ) {
            $query_args[ 'author' ] = $args[ 'creator' ];
        }

        /* Return campaigns, ordered by date of creation. */
        if ( 'post_date' == $args[ 'orderby' ] ) {
            $query_args[ 'orderby' ] = 'date';
            $query_args[ 'order' ] = 'DESC';
            return Charitable_Campaigns::query( $query_args );
        }

        /* Return campaigns, ordered by how much money has been raised. */
        if ( 'popular' == $args[ 'orderby' ] ) {
            return Charitable_Campaigns::ordered_by_amount( $query_args );
        }

        /* Return campaigns, ordered by how soon they are ending. */
        return Charitable_Campaigns::ordered_by_ending_soon( $query_args );
    }

}

endif;