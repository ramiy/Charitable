<?php
/**
 * Display the table of payment gateways. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$helper	= charitable_get_helper( 'gateway' );
$gateway = charitable_get_option( 'defaultgateway' );

if ( count( $helper->get_available_gateways() ) ) : 
?>
<table class="charitable-table charitable-gateways-table widefat" cellspacing="0">
	<thead>
		<tr>
			<th><?php _e( 'Gateway', 'charitable' ) ?></th>
			<th><?php _e( 'Gateway ID', 'charitable' ) ?></th>
			<th colspan="2"><?php _e( 'Actions', 'charitable' ) ?></th>
		</tr>
	</thead>
	<tbody>		
		<?php foreach ( $helper->get_available_gateways() as $gateway ) : ?>
			<tr>
				<td><?php echo $gateway::GATEWAY_NAME ?></td>
				<td><?php echo $gateway::GATEWAY_ID ?></td>
				<td>
					<?php if ( $gateway::is_enabled() ) : ?>
						<a class="button"><?php _e( 'Disable Gateway', 'charitable' ) ?></a>
					<?php else : ?>
						<a class="button"><?php _e( 'Enable Gateway', 'charitable' ) ?></a>
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?php else : ?>
	<?php _e( 'There are no gateways available in your system.', 'charitable' ) ?>
<?php endif ?>