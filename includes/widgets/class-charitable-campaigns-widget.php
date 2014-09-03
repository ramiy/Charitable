<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Charitable_Campaigns_Widget' ) ) : 

/**
 * Campaigns widget
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
		?>
		<aside class="widget widget-campaigns">
		<h1 class="widget-title"><?php echo $instance['title'];?></h1>
		<ul>

			<?php
				$query = Charitable_Campaign_Query::ordered_by_ending_soon( array('posts_per_page' => $instance['number'] ) );

				while($query->have_posts() ){
					$query->the_post();
					?><li class="cat-item"><a href="index.php?p=<?php echo get_the_id();?>"><?php the_title(); ?></a></li><?php
				}

			?>

		</aside>
		<?php
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
<!-- 				<option value="amount_raised"><?php _e( 'Amount raised', 'charitable' ) ?></option> -->
			</select>
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
		$instance = array();
		$instance['title']  = isset( $new_instance['title'] ) ? $new_instance['title'] : $old_instance['title'];
		$instance['number'] = isset( $new_instance['number'] ) ? $new_instance['number'] : $old_instance['number'];
		$instance['order']  = isset( $new_instance['order'] ) ? $new_instance['order'] : $old_instance['order'];
		return $instance;
	}	
}

endif; // End class_exists check