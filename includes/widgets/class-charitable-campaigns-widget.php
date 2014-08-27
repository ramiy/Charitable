<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Campaigns_Widget' ) ) : 

/**
 * Recent Campaigns widget
 *
 * @class 		Charitable_Campaigns_Widget
 * @version		0.1
 * @package		Charitable/Widgets/Campaigns Widget
 * @category	Class
 * @author 		Studio164a
 */
class Charitable_Campaigns_Widget extends WP_Widget {

	/**
	 * Instantiate the widget and set up basic configuration.
	 * 
	 * @access public
	 * @since 0.1
	 */
	public function __construct() {

		parent::__construct(
			'charitable_campaigns_widget', 
			__( 'Campaigns', 'charitable' ), 
			array( 'description' => __( 'Displays your Charitable campaigns.', 'charitable' ) )
		);
	}

	/**
	 * Display the widget contents on the front-end. 
	 *
	 * @param array $args
	 * @param array $instance
	 * @access public 
	 * @since 0.1
	 */
	public function widget( $args, $instance ) {

	}

	/**
	 * Display the widget form in the admin.
	 *
	 * @param array $instance The current settings for the widget options. 
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function form( $instance ) {		 
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$order = isset( $instance['order'] ) ? $instance['order'] : 'newest';
		?>
		<p>

		</p>		
		<p>

		</p>
		<p>

		</p>
		<?php
	}

	/**
	 * Update the widget settings in the admin. 
	 *
	 * @param array $new_instance The updated settings. 
	 * @param array $new_instance The old settings. 
	 * @return void
	 * @access public
	 * @since 0.1
	 */
	public function update( $new_instance, $old_instance ) {

	}	
}

endif; // End class_exists check