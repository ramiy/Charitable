<?php 
/**
 * Renders the campaign's donation options in the settings metabox for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

global $post;

$custom_donations_enabled 	= get_post_meta( $post->ID, '_campaign_custom_donations_enabled', true );
$suggested_donations 		= get_post_meta( $post->ID, '_campaign_suggested_donations', true );
?>
<section class="charitable-metabox-section">
	<h4 class="charitable-metabox-section-header"><?php _e( 'Donation Options', 'charitable' ) ?></h4>	
	<p class="charitable-metabox-field">
		<label for="campaign_suggested_donations">
			<?php _e( 'Suggested donations', 'charitable' ) ?>
		</label>
		<input type="text" id="campaign_suggested_donations" name="_campaign_suggested_donations" value="<?php echo $suggested_donations ?>" />		
	</p>
</section>