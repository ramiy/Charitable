<?php 
/**
 * Renders the donation details meta box for the Donation post type.
 *
 * @author  Studio 164a
 * @since   1.5.0
 */
global $post;

$meta = charitable_get_donation( $post->ID )->get_donation_meta();

?>
<div id="charitable-donation-actions-metabox" class="charitable-metabox">

<?php		
	global $donation;

	// This is used by some callbacks attached to hooks such as charitable_donation_actions which rely on the global to determine if actions should be displayed for certain orders.
	if ( ! is_object( $donation ) ) {
		$donation = charitable_get_donation( $post->ID );
	}

	$donation_type_object = get_post_type_object( $post->post_type );
	?>
	<ul class="donation_actions submitbox">

		<?php do_action( 'charitable_donation_actions_start', $post->ID ); ?>

		<li class="wide" id="actions">
			<select name="charitable_donation_action">
				<option value=""><?php _e( 'Actions', 'charitable' ); ?></option>
				<optgroup label="<?php esc_attr_e( 'Resend donation emails', 'charitable' ); ?>">
					<?php
					$mailer           = Charitable_Emails::get_instance();
					$available_emails = apply_filters( 'charitable_resend_donation_emails_available', array( 'donation_receipt', 'new_donation', 'campaign_end'     => 'Charitable_Email_Campaign_End', 'password_reset' ) );
					$mails            = $mailer->get_enabled_emails();
					$mail_names = $mailer->get_enabled_emails_names();

					if ( ! empty( $mails ) ) {
						foreach ( $mails as $id => $label ) {
							echo '<option value="send_email_'. esc_attr( $id ) .'">' . esc_html( $mail_names[$id] ) . '</option>';
						}
					}
					?>
				</optgroup>

				<option value="regenerate_download_permissions"><?php _e( 'Regenerate download permissions', 'charitable' ); ?></option>

				<?php foreach( apply_filters( 'charitable_donation_actions', array() ) as $action => $title ) { ?>
					<option value="<?php echo $action; ?>"><?php echo $title; ?></option>
				<?php } ?>
			</select>

			<button class="button wc-reload" title="<?php esc_attr_e( 'Apply', 'charitable' ); ?>"><span><?php _e( 'Apply', 'charitable' ); ?></span></button>
		</li>

		<li class="wide">
			<div id="delete-action"><?php

				if ( current_user_can( 'delete_post', $post->ID ) ) {

					if ( ! EMPTY_TRASH_DAYS ) {
						$delete_text = __( 'Delete Permanently', 'charitable' );
					} else {
						$delete_text = __( 'Move to Trash', 'charitable' );
					}
					?><a class="submitdelete deletion" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>"><?php echo $delete_text; ?></a><?php
				}
			?></div>

			<input type="submit" class="button save_order button-primary tips" name="save" value="<?php printf( __( 'Save %s', 'charitable' ), $donation_type_object->labels->singular_name ); ?>" data-tip="<?php printf( __( 'Save/update the %s', 'charitable' ), $donation_type_object->labels->singular_name ); ?>" />
		</li>

		<?php do_action( 'charitable_donation_actions_end', $post->ID ); ?>

	</ul>

</div>