<?php 
/**
 * Renders the goal end date in the settings metabox for the Campaign post type.
 *
 * @author Studio 164a
 * @since 0.1
 */

global $post;

$end_date_enabled 	= get_post_meta( $post->ID, '_campaign_end_date_enabled', true );
$end_date 			= get_post_meta( $post->ID, '_campaign_end_date', true );
?>
<section class="charitable-metabox-section">
	<h4 class="charitable-metabox-section-header"><?php _e( 'End date', 'charitable' ) ?></h4>
	<p class="charitable-metabox-field">
		<label for="campaign_end_date_enabled">
			<?php _e( 'Does this campaign have an end date?', 'charitable' ) ?>
		</label>
		<input type="checkbox" id="campaign_end_date_enabled" name="_campaign_end_date_enabled" <?php checked( $end_date_enabled ) ?> />
	</p>
	<p class="charitable-metabox-field">
		<label for="campaign_end_date">
			<?php _e( 'End date', 'charitable' ) ?>
		</label>
		<input type="text" id="campaign_end_date" name="_campaign_end_date" class="datepicker" value="<?php echo $end_date ?>" />
	</p>
</section>