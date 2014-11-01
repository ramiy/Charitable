<?php 
/**
 * Renders the campaign's donation options in the settings metabox for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

global $post;

$custom_donations_enabled 	= get_post_meta( $post->ID, '_campaign_custom_donations_enabled', true );
$suggested_donations 		= (array) get_post_meta( $post->ID, '_campaign_suggested_donations', true );
?>
<section class="charitable-metabox-section">
	<h4 class="charitable-metabox-section-header"><?php _e( 'Donation Options', 'charitable' ) ?></h4>
	<p class="charitable-metabox-field">
		<label for="campaign_custom_donations_enabled">
			<?php _e( 'Allow donors to donate any amount?', 'charitable' ) ?>
		</label>
		<input type="checkbox" id="campaign_custom_donations_enabled" name="_campaign_custom_donations_enabled" <?php checked($custom_donations_enabled) ?> />
	</p>
	<p class="charitable-metabox-field">
		<label for="campaign_suggested_donations">
			<?php _e( 'Suggested donations', 'charitable' ) ?>
		</label>
		<?php foreach ( $suggested_donations as $donation ) : ?>
			<input type="text" id="campaign_suggested_donations" name="_campaign_suggested_donations[]" value="<?php echo $donation ?>" />
		<?php endforeach ?>
		
	</p>
</section>