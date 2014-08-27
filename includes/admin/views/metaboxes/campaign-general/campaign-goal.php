<?php 
/**
 * Renders the campaign goal block in the settings metabox for the Campaign post type.
 *
 * @author Studio 164a
 * @since 0.1
 */

global $post;

$goal_enabled = get_post_meta( $post->ID, 'campaign_goal_enabled', true );
$goal = get_post_meta( $post->ID, 'campaign_goal', true );
?>
<section class="charitable-metabox-section">
	<h4 class="charitable-metabox-section-header"><?php _e( 'Goal', 'charitable' ) ?></h4>
	<p class="charitable-metabox-field">
		<label for="campaign_goal_enabled">
			<?php _e( 'Set a goal', 'charitable' ) ?>
		</label>
		<input type="checkbox" id="campaign_goal_enabled" name="campaign_goal_enabled" <?php checked($goal_enabled) ?> />
	</p>
	<p class="charitable-metabox-field">
		<label for="campaign_goal" class="charitable-metabox-label">
			<?php _e( 'Goal', 'charitable' ) ?>
		</label>
		<input type="text" id="campaign_goal" name="campaign_goal" value="<?php echo $goal ?>" />
	</p>
</section>