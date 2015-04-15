<?php
/**
 * The template used to display the donor's current details.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 * @version 1.0.0
 */

$form 	= $view_args[ 'form' ];
$donor 	= new Charitable_Donor( wp_get_current_user() );

if ( ! $donor->is_logged_in() ) {
	return;
}
?>
<address class="donor-address"><?php echo $donor->get_address() ?></address>
<p class="donor-contact-details">
	<?php printf( '%s: %s', __( 'Email', 'charitable '), $donor->user_email ) ?>
	<?php if ( $donor->__isset( 'donor_phone') ) : 
		printf( '<br />%s: %s', __( 'Phone number', 'charitable' ), $donor->get( 'donor_phone' ) );
	endif ?>
</p>