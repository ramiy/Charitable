<?php
/**
 * Charitable template
 *
 * @version		1.0.0
 * @package		Charitable/Classes/Charitable_Template
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Template' ) ) : 

/**
 * Charitable_Template
 *
 * @since 		1.0.0
 */
class Charitable_Template {	

	/**
	 * @var 	string Theme template path. 
	 */
	private $theme_template_path;

	/**
	 * @var 	array Template names to be loaded. 
	 */
	private $template_names;

	/**
	 * @var 	array Template name options. 
	 */
	private $theme_template_options;

	/**
	 * @var 	bool Whether to load template file if it is found. 
	 */
	private $load;

	/** 
	 * @var 	bool Whether to use require_once or require. 
	 */
	private $require_once;

	/**
	 * Class constructor. 
	 *
	 * @param 	string|array $template_name 	A single template name or an ordered array of template
	 * @param 	bool $load 						If true the template file will be loaded if it is found.
 	 * @param 	bool $require_once 				Whether to require_once or require. Default true. Has no effect if $load is false.
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function __construct($template_name, $load = false, $require_once = true) {
		$this->theme_template_path = trailingslashit( charitable()->get_public()->get_path( 'theme_templates' ) );
		$this->template_names = (array) $template_name;
		$this->load = $load;
		$this->require_once = $require_once;
		$this->theme_template_options = $this->get_theme_template_options();

		$this->render();
	}

	/**
	 * Return the theme template options. 
	 *
	 * @return 	array
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function get_theme_template_options() {
		$options = array();

		foreach ( $this->template_names as $template_name ) {
			$options[] = $this->theme_template_path . $template_name;
			$options[] = $template_name;
		} 

		return $options;
	}

	/**
	 * Renders the template. 
	 *
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function render() {

		/**
		 * Get the template and ensure it exists.
		 */
		$template = $this->locate_template();

		if ( ! file_exists( $template ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template ), '1.0.0' );
			return;
		}

		if ( $template )  {
			load_template( $template, $this->require_once );
		}

		return $template;
	}

	/**
	 * Locate the template file of the highest priority.
	 *
	 * @uses 	locate_template()
	 *
	 * @return 	string 
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function locate_template() {

		/**
		 * Template options are first checked in the theme/child theme using locate_template. 
		 */
		$template = locate_template( $this->theme_template_options );	

		/**
		 * No templates found in the theme/child theme, so use the plugin's default template.
		 */
		if ( ! $template ) {
			$template = charitable()->get_public()->get_path( 'base_templates' ) . $this->template_names[0];
		}

		return apply_filters( 'charitable_locate_template', $template, $this->template_names );
	}
}

endif; // End class_exists check