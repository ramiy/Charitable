<?php
/**
 * Display the table of payment gateways. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$field 		= charitable_get_admin_settings()->get_current_field();
$helper		= charitable_get_helper( 'gateway' );
$gateway 	= charitable_get_option( 'gateway' );

echo 'hi';
echo count( $helper->get_available_gateways() );
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
		<?php if ( count( $helper->get_available_gateways() ) > 4 ) : ?>

		<?php else : ?>
			<tr>
				<td colspan="5"><?php _e( 'There are no gateways available', 'charitable' ) ?></td>
			</tr>
		<?php endif ?>
	</tbody>
</table>