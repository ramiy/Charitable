<?php
/**
 * Display the date filters above the Donations table.
 *
 * @author  Studio 164a
 * @package Charitable/Admin View/Donations Page
 * @since   1.4.0
 */

$campaign_id = isset( $_GET['campaign_id'] ) ? intval( $_GET['campaign_id'] )   : '';
$start_date  = isset( $_GET['start_date'] )  ? sanitize_text_field( $_GET['start_date'] ) : '';
$end_date    = isset( $_GET['end_date'] )    ? sanitize_text_field( $_GET['end_date'] )   : '';
$campaigns   = get_posts( array(
	'post_type' => Charitable::CAMPAIGN_POST_TYPE,
	'nopaging'  => true,
));

?>

<label for="start_date" class="screen-reader-text"><?php _e( 'Start Date:', 'charitable' ) ?></label>
<input type="text" id="start_date" placeholder="<?php _e( 'Start Date', 'charitable' );?>" name="start_date" class="charitable-datepicker" value="<?php echo $start_date; ?>" />
<label for="end_date" class="screen-reader-text"><?php _e( 'End Date:', 'charitable' ) ?></label>     
<input type="text" id="end_date" placeholder="<?php _e( 'End Date', 'charitable' );?>" name="end_date" class="charitable-datepicker" value="<?php echo $end_date; ?>" />
<select class="campaign_id" name="campaign_id">
	<option value="all"><?php _e( 'All Campaigns', 'charitable' ) ?></option>
<?php foreach ( $campaigns as $campaign ) : ?>
	<option value="<?php echo $campaign->ID ?>" <?php selected( $campaign_id, $campaign->ID );?> ><?php echo get_the_title( $campaign->ID ) ?></option>
<?php endforeach ?>
</select>
<?php if( ! empty( $start_date ) || ! empty( $end_date ) || ! empty( $campaign_id ) ) : ?>
	<a href="<?php echo admin_url( 'edit.php?post_type=donation' ); ?>" class="button charitable-clear-filters"><?php _e( 'Clear', 'charitable' ); ?></a>
<?php endif;