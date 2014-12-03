<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Templates' ) ) : 

/**
 * Charitable templates
 *
 * @class 		Charitable_Templates
 * @version		1.0.0
 * @package		Charitable/Classes/Templates
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Templates {
	
	/**
	 * @var 	Charitable 	$charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * Set up the class. 
	 * 
	 * Note that the only way to instantiate an object is with the charitable_start method, 
	 * which can only be called during the start phase. In other words, don't try 
	 * to instantiate this object. 
	 *
	 * @param 	Charitable 	$charitable
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct(Charitable $charitable) {
		$this->charitable = $charitable;
	
		add_filter( 'the_content', array( $this, 'campaign_content' ) );
	
		// The main Charitable class will save the one instance of this object.
		$this->charitable->register_object( $this );
	}

	/**
	 * Instantiate the class, but only during the start phase.
	 *
	 * @uses 	charitable_start
	 * @param 	Charitable 	$charitable 
	 * @return 	void
	 * @static 
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function charitable_start(Charitable $charitable) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Templates( $charitable );
	}

	/** 
	 * Use our template for the campaign content.
	 * 
	 * @uses 	the_content
	 * @global 	WP_Post 	$post
	 * @param 	string 		$content
	 * @return 	string
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function campaign_content($content) {
		global $post;

		if ( $post->post_type == 'campaign' ) {

			/**
			 * If you do not want to use the default campaign template, use this filter and return false. 
			 *
			 * @uses 	charitable_use_campaign_template
			 */
			if ( apply_filters('charitable_use_campaign_template', true ) === false ) {
				return $content;
			}

			ob_start();

			charitable_template( 'content-campaign.php' );
			
			$content = ob_get_clean();
		}

		return $content;
	}
}

endif; // End class_exists check