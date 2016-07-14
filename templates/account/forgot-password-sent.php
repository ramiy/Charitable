<?php
/**
 * The template used to display the success message after a Password Reset email has been sent off.
 *
 * Override this template by copying it to yourtheme/charitable/account/forgot-password-sent.php
 *
 * @author  	Eric Daams
 * @package 	Charitable/Templates/Account
 * @since   	1.4.0
 * @version 	1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

?>
<p><?php _e( 'Your password reset request has been received. Please check your email for a link to reset your password.', 'charitable' ) ?></p>
