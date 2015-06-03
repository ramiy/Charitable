<?php
/**
 * Display the table of payment gateways. 
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

$helper		= charitable_get_helper( 'gateways' );
$default 	= charitable_get_option( 'default_gateway' );

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
		<?php foreach ( $helper->get_available_gateways() as $gateway ) : 
			$is_active = $helper->is_active_gateway( $gateway::GATEWAY_ID );
			$action_url = esc_url( add_query_arg( array(
				'charitable_action' => $is_active ? 'disable_gateway' : 'enable_gateway',
				'gateway_id' => $gateway::GATEWAY_ID, 
				'_nonce' => wp_create_nonce( 'gateway' )
			), admin_url( 'admin.php?page=charitable-settings&tab=gateways' ) ) );
			?>
			<tr>
				<td><?php echo $gateway::GATEWAY_NAME ?></td>
				<td><?php echo $gateway::GATEWAY_ID ?></td>
				<td>
					<?php if ( $is_active ) : 
						$settings_url = esc_url( add_query_arg( array(
							'edit_gateway' => $gateway::GATEWAY_ID
						), admin_url( 'admin.php?page=charitable-settings&tab=gateways' ) ) );
						?>
						<a href="<?php echo $settings_url ?>" class="button "><?php _e( 'Gateway Settings', 'charitable' ) ?></a>
					<?php endif ?>
				</td>
				<td>
					<?php if ( $is_active ) : ?>
						<a href="<?php echo $action_url ?>" class="button"><?php _e( 'Disable Gateway', 'charitable' ) ?></a>
					<?php else : ?>
						<a href="<?php echo $action_url ?>" class="button"><?php _e( 'Enable Gateway', 'charitable' ) ?></a>
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?php else : ?>
	<?php _e( 'There are no gateways available in your system.', 'charitable' ) ?>
<?php endif ?>