<?php 
/**
 * Displays the campaign donation form.
 *
 * @author Studio 164a
 * @since 0.1
 */

$campaign = get_charitable()->get_request()->get_current_campaign();
$donation_form = new Charitable_Donation_Form( $campaign );

/**
 * @hook charitable_donation_form_before
 */
do_action('charitable_donation_form_before');
?>
<form class="charitable-donation-form">
	
</form>
<?php
/**
 * @hook charitable_donation_form_after
 */
do_action('charitable_donation_form_after');