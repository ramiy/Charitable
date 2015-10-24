<?php 
/**
 * Class that sets up the WordPress Customizer integration.
 * 
 * @package     Charitable/Classes/Charitable_Customizer
 * @version     1.2.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Customizer' ) ) : 

/**
 * Sets up the Wordpress customizer
 *
 * @since 1.2.0
 */
class Charitable_Customizer {

    /** 
     * The single instance of this class.  
     *
     * @var     Charitable_Customizer|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * Create object instance. 
     *
     * @access  private
     * @since   1.2.0
     */
    private function __construct() {
        add_action('customize_save_after', array( $this, 'customize_save_after' ) );
        add_action('customize_register', array( $this, 'register_customizer_fields' ) );     
        // add_action('customize_preview_init', array( $this, 'load_theme_customizer_script' ) );
    }

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @global  WP_Customize_Manager $wp_customize
     * @return  Charitable_Customizer
     * @access  public
     * @since   1.2.0
     */
    public static function start() {
        global $wp_customize;

        if ( ! $wp_customize ) {
            return;
        }

        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Customizer();
        }

        return self::$instance;
    }

    /**
     * After the customizer has finished saving each of the fields, delete the transient.
     *
     * @see     customize_save_after hook
     * @return  void
     * @access  public
     * @since   1.2.0
     */
    public function customize_save_after() {
        delete_transient( 'charitable_custom_styles' );
    }

    /**
     * Theme customization. 
     *
     * @param   WP_Customize_Manager $wp_customize
     * @return  void
     */
    public function register_customizer_fields($wp_customize) {
        $highlight_colour = apply_filters( 'charitable_default_highlight_colour', '#f89d35' );

        $fields = apply_filters( 'charitable_customizer_fields', array(
            'title'     => __( 'Charitable', 'charitable' ), 
            'priority'  => 1000,
            'capability'=> 'edit_theme_options',
            'sections'  => array(
                'charitable_design' => array(
                    'title'     => __( 'Design', 'charitable' ), 
                    'priority'  => 1100,            
                    'settings'  => array(
                        'charitable_highlight_colour' => array(
                            'setting'   => array(
                                'transport'         => 'postMessage', 
                                'default'           => $highlight_colour,
                                'sanitize_callback' => 'sanitize_hex_color'
                            ),
                            'control'   => array(
                                'control_type'      => 'WP_Customize_Color_Control',
                                'priority'          => 1110,
                                'label'             => __( 'Highlight Colour', 'charitable' )
                            )
                        )
                    )
                )                
            )            
        ) );

        $this->add_panel( 'charitable', $fields );
    }

    /**
     * Adds a panel. 
     *
     * @param   string  $panel_id
     * @param   array   $panel
     * @return  void
     * @access  private
     * @since   1.2.0
     */
    private function add_panel( $panel_id, $panel ) {
        global $wp_customize;

        if ( empty( $panel ) ) {
            return;
        }
            
        $priority = $panel[ 'priority' ];

        $wp_customize->add_panel( $panel_id, array(
            'title' => $panel[ 'title' ],
            'priority' => $panel[ 'priority' ]  
        ) );

        $this->add_panel_sections( $panel_id, $panel[ 'sections' ] );
    }

    /**
     * Adds sections to a panel.
     *
     * @param   string  $panel_id
     * @param   array   $sections
     * @return  void
     * @access  private
     * @since   1.2.0
     */
    private function add_panel_sections( $panel_id = false, $sections ) {
        global $wp_customize;

        if ( empty( $sections ) ) {
            return;
        }

        foreach ( $sections as $section_id => $section ) {
            $this->add_section( $section_id, $section, $panel_id );            
        }
    }

    /**
     * Adds section & settings
     *
     * @param   string  $section_id
     * @param   array   $section
     * @param   string  $panel
     * @return  void
     * @access  private
     * @since   1.2.0
     */
    private function add_section( $section_id, $section, $panel = "" ) {
        global $wp_customize;

        if ( empty( $section ) ) {
            return;
        }

        $settings = $section[ 'settings' ];

        unset( $section[ 'settings' ] );

        if ( ! empty( $panel ) ) {
            $section[ 'panel' ] = $panel;
        } 

        $wp_customize->add_section( $section_id, $section );

        $this->add_section_settings( $section_id, $settings );
    }


    /**
     * Adds settings to a given section. 
     *
     * @param   string $section_id
     * @param   array $settings
     * @return  void
     * @access  private
     * @since   1.2.0
     */
    private function add_section_settings( $section_id, $settings ) {
        global $wp_customize;

        if ( empty( $settings ) ) {
            return;
        }

        foreach ( $settings as $setting_id => $setting ) {
            if ( ! isset( $setting[ 'setting' ][ 'type' ] ) ) {
                $setting[ 'setting' ][ 'type' ] = 'option';
            }            

            $wp_customize->add_setting( $setting_id, $setting[ 'setting' ] );        

            $setting_control = $setting[ 'control' ];
            $setting_control[ 'section' ] = $section_id;
            $setting_control[ 'type' ] = $setting[ 'type' ];

            if ( isset( $setting_control[ 'control_type' ] ) ) {

                $setting_control_type = $setting_control[ 'control_type' ];

                unset( $setting_control[ 'control_type'] );

                $wp_customize->add_control( new $setting_control_type( $wp_customize, $setting_id, $setting_control ) );

            }
            else {

                $wp_customize->add_control( $setting_id, $setting_control );

            }
        }
    }

    /**
     * Load the theme-customizer.js file. 
     *
     * @return  void
     * @access  public
     * @since   1.2.0
     */
    public function load_customizer_script() {
        wp_register_script( 'charitable-customizer', charitable()->get_path( 'assets', false ) . '/js/admin/charitable-customizer.js', array( 'jquery', 'customize-preview' ), '1.2.0-beta4', true );
        wp_enqueue_script( 'charitable-customizer' );
    }
}

endif; // End class_exists check