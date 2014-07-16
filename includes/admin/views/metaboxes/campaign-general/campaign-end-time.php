<?php 
/**
 * Renders the goal end time in the settings metabox for the Campaign post type.
 *
 * @author Studio 164a
 * @since 0.0.1
 */

global $post;

$end_time_enabled = get_post_meta( $post->ID, 'campaign_end_time_enabled', true );
$end_time = get_post_meta( $post->ID, 'campaign_end_time', true );
?>
<section class="charitable-metabox-section">
	<h4 class="charitable-metabox-section-header"><?php _e( 'End time', 'charitable' ) ?></h4>
	<p class="charitable-metabox-field">
		<label for="campaign_end_time_enabled">
			<?php _e( 'Does this campaign have an end date?', 'charitable' ) ?>
		</label>
		<input type="checkbox" id="campaign_end_time_enabled" name="campaign_end_time_enabled" <?php checked($end_time_enabled) ?> />
	</p>
	<p class="charitable-metabox-field">
		<label for="campaign_end_time">
			<?php _e( 'End date', 'charitable' ) ?>
		</label>
		<input type="text" id="campaign_end_time" name="campaign_end_time" class="datepicker" value="<?php echo $end_time ?>" />
	</p>
</section>