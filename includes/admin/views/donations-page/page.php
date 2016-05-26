<?php
/**
 * Display the donations page. 
 *
 * @author  Studio 164a
 * @package Charitable/Admin View/Settings
 * @since   1.0.0
 */

require_once charitable()->get_path( 'admin' ) . 'donations/class-charitable-donations-table.php';

$donation_post_type = get_post_type_object( 'donation' );

$donations_table = new Charitable_Donations_Table();
$donations_table->prepare_items();

$start_date = isset( $_GET['start_date'] )  ? sanitize_text_field( $_GET['start_date'] ) : null;
$end_date   = isset( $_GET['end_date'] )    ? sanitize_text_field( $_GET['end_date'] )   : null;
$status     = isset( $_GET['post_status'] ) ? $_GET['post_status'] : 'all';

?>
<div class="wrap">
    <h2><?php echo $donation_post_type->labels->menu_name ?></h2>
    <?php do_action( 'charitable_donations_page_top' ); ?>
    <form id="charitable-donations" method="get" action="<?php echo admin_url( 'admin.php?page=charitable-donations-table' ); ?>">
        <input type="hidden" name="page" value="charitable-donations-table" />

        <?php $donations_table->views() ?>

        <div id="charitable-donation-filters">
            <span id="charitable-donation-date-filters">
                <label for="start_date"><?php _e( 'Start Date:', 'charitable' ) ?>
                    <input type="text" id="start_date" name="start_date" class="charitable-datepicker" value="<?php echo $start_date; ?>" />
                </label>
                <label for="end_date"><?php _e( 'End Date:', 'charitable' ) ?>
                    <input type="text" id="end_date" name="end_date" class="charitable-datepicker" value="<?php echo $end_date; ?>" />
                </label>
                <input type="submit" class="button-secondary" value="<?php _e( 'Apply', 'charitable' ) ?>" />            
                <?php if( ! empty( $status ) ) : ?>
                    <input type="hidden" name="post_status" value="<?php echo esc_attr( $status ); ?>"/>
                <?php endif; ?>
                <?php if( ! empty( $start_date ) || ! empty( $end_date ) ) : ?>
                    <a href="<?php echo admin_url( 'admin.php?page=charitable-donations-table' ); ?>" class="charitable-clear-filters button-secondary"><?php _e( 'Clear Filter', 'charitable' ); ?></a>
                <?php endif; ?>
            </span>
            <a href="#charitable-donations-export-modal" class="charitable-donations-export button-secondary" data-trigger-modal><?php _e( 'Export', 'charitable' ) ?></a>
            <?php charitable_admin_view( 'donations-page/search', array( 'table' => $donations_table ) ) ?>            
        </div>
        
        <?php $donations_table->display() ?>
        
    </form>
    <?php charitable_admin_view( 'donations-page/export', array( 'table' => $donations_table ) ) ?>
</div>