<?php 
/**
 * Renders the suggested donation amounts field inside the donation options metabox for the Campaign post type.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

global $post;

if ( ! isset( $view_args[ 'fields' ] ) || empty( $view_args[ 'fields' ] ) ) {
	return;
}

$fields					= $view_args['fields'];
$title 					= isset( $view_args['label'] ) 		? $view_args['label'] 	: '';
$tooltip 				= isset( $view_args['tooltip'] )	? '<span class="tooltip"> '. $view_args['tooltip'] . '</span>'	: '';
$description			= isset( $view_args['description'] )? '<span class="charitable-helper">' . $view_args['description'] . '</span>' 	: '';
$suggested_donations 	= get_post_meta( $post->ID, '_campaign_suggested_donations', true );

if ( ! $suggested_donations ) {
	$suggested_donations = array();
}
?>
<div id="charitable-campaign-suggested-donations-metabox-wrap" class="charitable-metabox-wrap">
	<table id="charitable-campaign-suggested-donations" class="widefat">
		<thead>
			<tr class="table-header">
				<th colspan="<?php echo count( $fields ) ?>"><label for="campaign_suggested_donations"><?php echo $title ?></label></th>
			</tr>
			<tr>
				<?php foreach ( $fields as $key => $field ) : ?>
					<th class="<?php echo $key ?>-col"><?php echo $field[ 'column_header' ] ?></th>
				<?php endforeach ?>				
			</tr>
		</thead>		
		<tbody>
		<?php 
			if ( $suggested_donations ) : 
				foreach ( $suggested_donations as $i => $donation ) : 
				?>
					<tr data-index="<?php echo $i ?>">
						<?php foreach ( $fields as $key => $field ) : 

							if ( is_array( $donation ) && isset( $donation[ $key ] ) ) {
								$value = $donation[ $key ];
							}
							elseif ( 'amount' == $key ) {
								$value = $donation;
							}
							else {
								$value = '';
							}

							?>
							<td class="<?php echo $key ?>-col"><input 
								type="text" 
								id="campaign_suggested_donations_<?php echo $i ?>" 
								name="_campaign_suggested_donations[<?php echo $i ?>][<?php echo $key ?>]" 
								value="<?php echo esc_attr( $value ) ?>" 
								placeholder="<?php echo esc_attr( $field[ 'placeholder' ] ) ?>" />
							</td>
						<?php endforeach ?>						
					</tr>
				<?php 
				endforeach;
			else : 
			?>
				<tr class="no-suggested-amounts">
					<td colspan="<?php echo count( $fields ) ?>"><?php _e( 'No suggested amounts have been created yet.', 'charitable' ) ?></td>
				</tr>
			<?php 
			endif;
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<?php echo count( $fields ) ?>"><a class="button" href="#" data-charitable-add-row="suggested-amount"><?php _e( '+ Add a Suggested Amount', 'charitable' ) ?></a></td>
			</tr>
		</tfoot>
	</table>	
</div>