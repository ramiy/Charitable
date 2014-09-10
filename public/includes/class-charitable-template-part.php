<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Template_Part' ) ) : 

/**
 * Charitable template part
 *
 * @class 		Charitable_Template_Part
 * @version		0.1
 * @package		Charitable/Classes/Template
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Template_Part {	

	/**
	 * @var string The template's slug.
	 */
	private $slug;

	/**
	 * @var string An optional name to be appended to the slug.
	 */
	private $name;

	/**
	 * Class constructor. 
	 *
	 * @param string $slug
	 * @param string $name Optional name.
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function __construct($slug, $name = "") {
		$this->slug = $slug;
		$this->name = $name;

		new Charitable_Template( $this->get_template_names() );
	}

	/**
	 * Returns the array of template names. 
	 * 
	 * @return array
	 * @access private
	 * @since 0.1
	 */
	private function get_template_names() {
		$names = array(
			$this->slug . '.php'
		);

		/**
		 * If a name is set, add the slug-name.php combination to the start of the $names array
		 */
		if ( strlen( $this->name ) ) {
			array_unshift( $names, $this->slug . '-' . $this->name . '.php' );
		}

		return $names;
	}
}

endif; // End class_exists check