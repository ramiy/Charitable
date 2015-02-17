<?php 
/**
 * Displays the donate button to be displayed on campaign pages. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$campaign = charitable()->get_request()->get_current_campaign();
?>
<script type="text/javascript">

</script>
<form class="campaign-donation" method="post">
	<?php wp_nonce_field( 'charitable-donate-' . charitable_get_session()->get_session_id(), 'charitable-donate-now' ) ?>
	<input type="hidden" name="charitable_action" value="start-donation" />
	<input type="hidden" name="campaign_id" value="<?php echo $campaign->get_campaign_id() ?>" />
	<input type="submit" name="charitable_submit" value="<?php esc_attr_e( 'Donate', 'charitable' ) ?>" class="button button-primary" />
</form>