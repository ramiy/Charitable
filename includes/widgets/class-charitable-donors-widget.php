<?php
/**
 * Campaign donors widget class. 
 *
 * @version     1.0.0
 * @package     Charitable/Widgets/Donors Widget
 * @category    Class
 * @author      Eric Daams
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Donors_Widget' ) ) : 

/**
 * Charitable_Donors_Widget class. 
 *
 * @since       1.0.0
 */
class Charitable_Donors_Widget extends WP_Widget {

    /**
     * Instantiate the widget and set up basic configuration.
     * 
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        parent::__construct(
            'charitable_donors_widget', 
            __( 'Donors', 'charitable' ), 
            array( 'description' => __( 'Display a list of donors.', 'charitable' ) )
        );
    }

    /**
     * Display the widget contents on the front-end. 
     *
     * @param   array $args
     * @param   array $instance
     * @access  public 
     * @since   1.0.0
     */
    public function widget( $args, $instance ) {    
        $view_args = array_merge( $args, $instance );
        $view_args[ 'donors' ] = $this->get_widget_donors( $instance );

        $template = charitable_template( 'widgets/donors.php', false );
        $template->set_view_args( $view_args );
        $template->render();        
    }    

    /**
     * Display the widget form in the admin.
     *
     * @param   array $instance         The current settings for the widget options. 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function form( $instance ) {
        $defaults = array(
            'title'         => '',
            'number'        => 10, 
            'order'         => 'recent',
            'campaign'      => 'all',
            'show_location' => false,
            'show_amount'   => false,
            'show_name'     => false, 
            'hide_if_no_donors' => false
        );

        $args = wp_parse_args( $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>"><?php _e( 'Title', 'charitable' ) ?>:</label>
            <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>" value="<?php echo esc_attr( $args['title'] ) ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ) ?>"><?php _e( 'Number of donors to display', 'charitable' ) ?>:</label>
            <input type="number" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ) ?>" value="<?php echo intval( $args[ 'number' ] ) ?>" min="1" size="3" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ) ?>"><?php _e( 'Order by', 'charitable' ) ?>:</label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ) ?>">
                <option value="recent" <?php selected( 'recent', $args[ 'order' ] ) ?>><?php _e( 'Most recent', 'charitable' ) ?></option>
                <option value="amount" <?php selected( 'amount', $args[ 'order' ] ) ?>><?php _e( 'Amount donated', 'charitable' ) ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'campaign' ) ) ?>"><?php _e( 'Show donors by campaign', 'charitable' ) ?>:</label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'campaign' ) ) ?>">
                <option value="all" <?php selected( 'all', $args[ 'campaign' ] ) ?>><?php _e( 'Include all campaigns' ) ?></option>
                <option value="current-campaign" <?php selected( 'current-campaign', $args[ 'campaign' ] ) ?>><?php _e( 'Campaign currently viewed', 'charitable' ) ?></option>
                <optgroup label="<?php _e( 'Specific campaign', 'charitable' ) ?>">
                    <?php foreach ( Charitable_Campaigns::query()->posts as $campaign ) : ?>
                        <option value="<?php echo intval( $campaign->ID ) ?>" <?php selected( $campaign->ID, $args[ 'campaign' ] ) ?>><?php echo $campaign->post_title ?></option>
                    <?php endforeach ?>
                </optgroup>
            </select>                
        </p>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_name' ) ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_name' ) ); ?>" <?php checked( $args[ 'show_name' ] ) ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_name' ) ) ?>"><?php _e( 'Show donor\'s name', 'charitable' ) ?></label>            
        </p>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_amount' ) ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_amount' ) ); ?>" <?php checked( $args[ 'show_amount' ] ) ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_amount' ) ) ?>"><?php _e( 'Show donor\'s pledge amount', 'charitable' ) ?></label>            
        </p>
        <p>            
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_location' ) ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_location' ) ); ?>" <?php checked( $args[ 'show_location' ] ) ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_location' ) ) ?>"><?php _e( 'Show donor\'s location', 'charitable' ) ?></label>
        </p>
        <p>            
            <input id="<?php echo esc_attr( $this->get_field_id( 'hide_if_no_donors' ) ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'hide_if_no_donors' ) ); ?>" <?php checked( $args[ 'hide_if_no_donors' ] ) ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'hide_if_no_donors' ) ) ?>"><?php _e( 'Hide if there are no donors', 'charitable' ) ?></label>
        </p>
        <?php
    }

    /**
     * Update the widget settings in the admin. 
     *
     * @param   array $new_instance         The updated settings. 
     * @param   array $new_instance         The old settings. 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance[ 'title' ]                = isset( $new_instance[ 'title' ] )             ? $new_instance[ 'title' ]              : $old_instance[ 'title' ];
        $instance[ 'number' ]               = isset( $new_instance[ 'number' ] )            ? intval( $new_instance[ 'number' ] )   : $old_instance[ 'number' ];
        $instance[ 'order' ]                = isset( $new_instance[ 'order' ] )             ? $new_instance[ 'order' ]              : $old_instance[ 'order' ];
        $instance[ 'campaign' ]             = isset( $new_instance[ 'campaign' ] )          ? $new_instance[ 'campaign' ]           : $old_instance[ 'campaign' ];
        $instance[ 'show_location' ]        = isset( $new_instance[ 'show_location' ] ) && 'on' == $new_instance[ 'show_location' ];
        $instance[ 'show_amount' ]          = isset( $new_instance[ 'show_amount' ] ) && 'on' == $new_instance[ 'show_amount' ];
        $instance[ 'show_name' ]            = isset( $new_instance[ 'show_name' ] ) && 'on' == $new_instance[ 'show_name' ];
        $instance[ 'hide_if_no_donors' ]    = isset( $new_instance[ 'hide_if_no_donors' ] ) && 'on' == $new_instance[ 'hide_if_no_donors' ];        
        return $instance;
    }   

    /**
     * Return the donors to display in the widget. 
     *
     * @return  array
     * @access  protected
     * @since   1.0.0
     */
    protected function get_widget_donors( $instance ) {
        $query_args = array( 
            'number' => $instance[ 'number' ]
        );

        if ( 'amount' == $instance[ 'order' ] ) {
            $query_args[ 'orderby' ] = 'amount';
        }

        if ( 'all' != $instance[ 'campaign' ] ) {

            if ( 'current-campaign' == $instance[ 'campaign' ] ) {
                $query_args[ 'campaign' ] = charitable_get_current_campaign_id();
            }
            else {
                $query_args[ 'campaign' ] = intval( $instance[ 'campaign' ] );
            }
        }

        return new Charitable_Donors_Query( $query_args );
    }
}

endif; // End class_exists check