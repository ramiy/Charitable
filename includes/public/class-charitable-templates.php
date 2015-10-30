<?php
/**
 * Sets up Charitable templates for specific views. 
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Templates
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Templates' ) ) : 

/**
 * Charitable_Templates
 *
 * @since 		1.0.0
 */

class Charitable_Templates {

	/**
     * The single instance of this class.  
     *
     * @var     Charitable_Templates|null
     * @access  private
     * @static
     */
    private static $instance = null;    

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Templates
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Templates();
        }

        return self::$instance;
    }

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct() {		
		add_filter( 'template_include', array( $this, 'donation_receipt_template' ) );
		add_filter( 'template_include', array( $this, 'donation_processing_template' ) );
		add_filter( 'template_include', array( $this, 'donate_template' ) );
		add_filter( 'template_include', array( $this, 'widget_template' ) );
		add_filter( 'template_include', array( $this, 'email_template' ) );
		add_filter( 'body_class', 		array( $this, 'add_donation_page_body_class' ) );
		add_filter( 'body_class', 		array( $this, 'add_widget_page_body_class' ) );
		
		/* If you want to unhook any of the callbacks attached above, use this hook. */
		do_action( 'charitable_templates_start', $this );
	}	

	/**
	 * Load the donation receipt template if we're looking at a donation receipt. 
	 *
	 * @param 	string 		$template
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function donation_receipt_template( $template ) {		
		if ( charitable_is_page( 'donation_receipt_page' ) ) {

			if ( 'auto' != charitable_get_option( 'donation_receipt_page', 'auto' ) ) {
				return $template;
			}

			new Charitable_Ghost_Page( 'donation-receipt-page', array(
				'title' 	=> __( 'Your Receipt', 'charitable' ),
				'content' 	=> sprintf( '<p>%s</p>', __( 'Thank you for your donation!', 'charitable' ) )
			) );

			$new_template = apply_filters( 'charitable_donation_receipt_page_template', array( 'donation-receipt-page.php', 'page.php', 'index.php' ) );

			$template = charitable_get_template_path( $new_template, $template );
		}

		return $template;
	}

	/**
	 * Load the donation processing template if we're looking at the donation processing page. 
	 *
	 * @param 	string $template
	 * @return 	string
	 * @access  public
	 * @since 	1.2.0
	 */
	public function donation_processing_template( $template ) {		
		if ( charitable_is_page( 'donation_processing_page' ) ) {

			new Charitable_Ghost_Page( 'donation-processing-page', array(
				'title' 	=> __( 'Thank you for your donation', 'charitable' ),
				'content' 	=> sprintf( '<p>%s</p>', __( 'You will shortly be redirected to the payment gateway to complete your donation.', 'charitable' ) )
			) );

			$new_template = apply_filters( 'charitable_donation_processing_page_template', array( 'donation-processing-page.php', 'page.php', 'index.php' ) );

			$template = charitable_get_template_path( $new_template, $template );
		}

		return $template;
	}

	/**
	 * Load the donation template if we're looking at the donate page. 
	 *
	 * @param 	string 		$template
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function donate_template( $template ) {		
		if ( charitable_is_page( 'campaign_donation_page' ) ) {

			do_action( 'charitable_is_donate_page' );

			$new_template = apply_filters( 'charitable_donate_page_template', array( 'campaign-donation-page.php', 'page.php', 'index.php' ) );

			$template = charitable_get_template_path( $new_template, $template );
		}

		return $template;
	}

	/**
	 * Load the widget template if we're looking at the widget page. 
	 *
	 * @param 	string 		$template
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function widget_template( $template ) {	
		if ( charitable_is_page( 'campaign_widget_page' ) ) {

			do_action( 'charitable_is_widget' );			
			
			add_filter( 'show_admin_bar', '__return_false' );
			add_action( 'wp_head', array( $this, 'remove_admin_bar_from_widget_template' ) );

			$new_template = apply_filters( 'charitable_widget_page_template', 'campaign-widget.php' );
			$template = charitable_get_template_path( $new_template, $template );
		}

		return $template;
	}

	/**
	 * Load the email template if we're looking at the email page. 
	 *
	 * @param 	string 		$template
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function email_template( $template ) {	
		if ( charitable_is_page( 'email_preview' ) ) {

			do_action( 'charitable_email_preview' );
			
			$template = charitable_get_template_path( 'emails/preview.php' );
		}

		return $template;
	}

	/**
	 * Adds custom body classes when viewing widget or donation form.
	 *
	 * @param 	array 		$classes
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_donation_page_body_class( $classes ) {
		if ( charitable_is_campaign_donation_page() ) {

			$classes[] = 'campaign-donation-page';

		}

		return $classes;
	}

	/**
	 * Adds custom body classes when viewing widget or donation form.
	 *
	 * @param 	array 		$classes
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function add_widget_page_body_class( $classes ) {
		if ( charitable_is_campaign_widget_page() ) {

			$classes[] = 'campaign-widget';
			
		}

		return $classes;
	}	

	/**
	 * Removes the admin bar from the widget template.	
	 *
	 * @return  void
	 * @access  public
	 * @since   1.0.0
	 */
	public function remove_admin_bar_from_widget_template() {
		?>
<style type="text/css" media="screen">
html { margin-top: 0 !important; }
* html body { margin-top: 0 !important; }
</style>
		<?php 
	}
}

endif; // End class_exists check