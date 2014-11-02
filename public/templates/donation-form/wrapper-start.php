<?php 
/**
 * Displays the opening form wrapper.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

/**
 * @var 	Charitable_Donation_Form
 */
$form = charitable_get_current_donation_form();

if ( ! $form ) {
	return;
}
?>
<form method="post" class="charitable-donation-form">
	<?php $form->nonce_field() ?>