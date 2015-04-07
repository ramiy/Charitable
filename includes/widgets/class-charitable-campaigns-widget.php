<?php
/**
 * Campaigns widget class. 
 *
 * @class 		Charitable_Campaigns_Widget
 * @version		1.0.0
 * @package		Charitable/Widgets/Campaigns Widget
 * @category	Class
 * @author 		Eric Daams
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Campaigns_Widget' ) ) : 

/**
 * Charitable_Campaigns_Widget class. 
 *
 * @since		1.0.0
 */
class Charitable_Campaigns_Widget extends WP_Widget {

	/**
	 * Instantiate the widget and set up basic configuration.
	 * 
	 * @access 	public
	 * @since 	1.0.0
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
	 * @param 	array $args
	 * @param 	array $instance
	 * @access 	public 
	 * @since 	1.0.0
	 */
	public function widget( $args, $instance ) {
		
		$title = apply_filters( "campaigns-widget-title", $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<ul>

			<?php
				$query = Charitable_Campaigns::ordered_by_ending_soon( array( 'posts_per_page' => $instance['number'] ) );

				while($query->have_posts() ){
					$query->the_post();
					?><li><a href="<?php the_permalink() ?>" ><?php the_title(); ?></a></li><?php
				}

			?>

		</ul>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Display the widget form in the admin.
	 *
	 * @param 	array $instance 		The current settings for the widget options. 
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function form( $instance ) {		 
		$title 	= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$order 	= isset( $instance['order'] ) ? $instance['order'] : 'newest';
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>"><?php _e( 'Title', 'charitable' ) ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?>" value="<?php echo $instance['title']?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number') ?>"><?php _e( 'Number of campaigns to display', 'charitable' ) ?></label>
			<input type="text" name="<?php echo $this->get_field_name('number') ?>" id="<?php echo $this->get_field_id('number') ?>" value="<?php echo $instance['number']?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order') ?>"><?php _e( 'Order', 'charitable' ) ?></label>
			<select name="<?php echo $this->get_field_name('order') ?>" id="<?php echo $this->get_field_id('order') ?>">
				<option value="recent" <?php echo $order=="recent" ? "selected='selected'" : ""?>><?php _e( 'Date published', 'charitable' ) ?></option>
				<option value="ending" <?php echo $order=="ending" ? "selected='selected'" : ""?>><?php _e( 'Ending soonest', 'charitable' ) ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Update the widget settings in the admin. 
	 *
	 * @param 	array $new_instance 		The updated settings. 
	 * @param 	array $new_instance 		The old settings. 
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']  = isset( $new_instance['title'] ) ? $new_instance['title'] : $old_instance['title'];
		$instance['number'] = isset( $new_instance['number'] ) ? $new_instance['number'] : $old_instance['number'];
		$instance['order']  = isset( $new_instance['order'] ) ? $new_instance['order'] : $old_instance['order'];
		return $instance;
	}	
}

endif; // End class_exists check