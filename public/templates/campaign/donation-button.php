<?php 
/**
 * Displays the donate button to be displayed on campaign pages. 
 *
 * @author Studio 164a
 * @since 0.1
 */

$campaign = get_charitable()->get_request()->get_current_campaign();
$session = get_charitable()->get_session();
?>
<script type="text/javascript">

</script>
<form class="campaign-donation" method="post">
	<?php wp_nonce_field( 'charitable-donate-' . $session->get_session_id(), 'charitable-donate-now' ) ?>
	<input type="hidden" name="charitable-action" value="donate-now" />
	<input type="hidden" name="campaign-id" value="<?php echo $campaign->get_campaign_id() ?>" />
	<input type="submit" name="charitable-submit" value="<?php _e( 'Donate', 'charitable' ) ?>" />
</form>
