<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Public' ) ) : 

/**
 * Charitable Public class. 
 *
 * @class 		Charitable_Public 
 * @author 		Studio164a
 * @category 	Public
 * @package 	Charitable/Public
 * @version     0.1
 */
final class Charitable_Public {

	/**
	 * @var Charitable $charitable
	 * @access private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param Charitable $charitable
	 * @return void
	 * @access private
	 * @since 0.1
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;

		$this->charitable->register_object($this);

		$this->load_dependencies();

		$this->attach_hooks_and_filters();
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 * 
	 * @param Charitable $charitable 
	 * @return void
	 * @static 
	 * @access public
	 * @since 0.1
	 */
	public static function start(Charitable $charitable) {
		if ( $charitable->started() ) {
			return;
		}

		new Charitable_Public( $charitable );
	}

	/**
	 * Load dependencies used for the public facing site. 
	 *
	 * @return void
	 * @access private
	 * @since 0.1
	 */
	private function load_dependencies() {
		require_once( $this->get_path( 'includes' ) . 'class-charitable-actions.php' );
		require_once( $this->get_path( 'includes' ) . 'class-charitable-pages.php' );
		require_once( $this->get_path( 'includes' ) . 'class-charitable-session.php' );
		require_once( $this->get_path( 'includes' ) . 'class-charitable-template.php' );		
		require_once( $this->get_path( 'includes' ) . 'class-charitable-template-part.php' );
		require_once( $this->get_path( 'includes' ) . 'class-charitable-templates.php' );
	}

	/**
	 * Sets up hook and filter callback functions for public facing functionality.
	 * 
	 * @return void
	 * @access private
	 * @since 0.1
	 */
	private function attach_hooks_and_filters() {
		add_action('charitable_start', array( 'Charitable_Session', 'charitable_start' ), 1 );
		add_action('charitable_start', array( 'Charitable_Actions', 'charitable_start' ), 2 );		
		add_action('charitable_start', array( 'Charitable_Templates', 'charitable_start' ), 2 );
		add_action('charitable_start', array( 'Charitable_Pages', 'charitable_start' ), 2 );		
		add_action('wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts') );
	}

	/**
	 * Returns the path to one of the directories related to the public facing functionality.
	 *
	 * @param string $type
	 * @return string
	 * @access public
	 * @since 0.1
	 */
	public function get_path( $type = '', $absolute_path = true ) {
		$base = $this->charitable->get_path( 'public', $absolute_path );

		switch ( $type ) {
			case 'assets' : 
				$path = $base . 'assets/';
				break;

			case 'includes' : 
				$path = $base . 'includes/';
				break;

			case 'base_templates' : 
				$path = $base . 'templates/';
				break;

			case 'theme_templates' :
				$path = apply_filters( 'charitable_theme_template_path', 'charitable' );
				break;

			default:
				$path = $base;
		}

		return $path;
	}

	/**
	 * Loads public facing scripts and stylesheets. 
	 *
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function wp_enqueue_scripts() {		
		wp_register_style( 'charitable-styles', $this->get_path( 'assets', false ) . 'css/charitable.css', array(), $this->charitable->get_version() );
		wp_enqueue_style( 'charitable-styles' );
	}
}

endif;