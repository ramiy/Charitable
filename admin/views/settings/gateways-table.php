<?php
/**
 * Display the table of payment gateways. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$field 		= charitable_get_admin_settings()->get_current_field();
$settings 	= get_option( 'charitable_settings' );
?>
<table class="charitable-table charitable-gateways-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th><?php _e( 'Default', 'charitable' ) ?></th>
			<th><?php _e( 'Gateway', 'charitable' ) ?></th>
			<th><?php _e( 'Gateway ID', 'charitable' ) ?></th>
			<th colspan="2"><?php _e( 'Status', 'charitable' ) ?></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>