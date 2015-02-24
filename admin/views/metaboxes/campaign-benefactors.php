<?php 
/**
 * Renders a benefactors addon metabox. Used by any plugin that utilizes the Benefactors Addon.
 *
 * @since 		1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a 
 */
global $post;

if ( ! isset( $view_args['extension'] ) ) {
	_doing_it_wrong( 'charitable_campaign_meta_boxes', 'Campaign benefactors metabox requires an extension argument.', '1.0.0' );
	return;
}

$extension		= $view_args['extension'];
$benefactors 	= charitable()->get_db_table( 'benefactors' )->get_campaign_benefactors_by_extension( $post->ID, $extension );
?>
<div class="charitable-metabox">
	<?php 
	if ( false == $benefactors || empty( $benefactors ) ) : ?>
		
		<p><?php __( 'No benefactor relationships have been set up yet.', 'charitable' ) ?></p>

	<?php else :
		foreach ( $benefactors as $benefactor ) :
		?>
		<div class="charitable-metabox-block charitable-benefactor">
			<?php do_action( 'charitable_campaign_benefactor_meta_box', new Charitable_Benefactor( $benefactor ), $extension ) ?>
		</div>
		<?php
		endforeach;
	endif;
	?>
	<p><a href="#" class="button" data-charitable-action="open-benefactor-form"><?php _e( '+ Create Relationship', 'charitable' ) ?></a></p>
</div>