<?php
/**
 * Display the seach form. 
 *
 * @author  Studio 164a
 * @package Charitable/Admin View/Settings
 * @since   1.0.0
 */

/**
 * @var     Charitable_Donations_Table
 */
$table = $view_args[ 'table' ];

if ( empty( $_REQUEST['s'] ) && ! $table->has_items() ) :
    return;
endif;

$input_id = $input_id . '-search-input';

if ( ! empty( $_REQUEST[ 'orderby' ] ) )
    echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST[ 'orderby' ] ) . '" />';
if ( ! empty( $_REQUEST[ 'order' ] ) )
    echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST[ 'order' ] ) . '" />';

?>
<p class="search-box">
    <?php do_action( 'charitable_donation_history_search' ); ?>
    <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
    <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query() ?>" />
    <?php submit_button( __( 'Search', 'charitable' ), 'button', false, false, array( 'ID' => 'search-submit' ) ) ?>
</p>