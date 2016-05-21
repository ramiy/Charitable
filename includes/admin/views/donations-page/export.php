<?php
/**
 * Display the export button in the donation filters box.
 *
 * @author  Studio 164a
 * @package Charitable/Admin View/Donations Page
 * @since   1.0.0
 */

?>
<div class="alignright export">
	<a href="#charitable-donations-export-modal" class="charitable-donations-export button-secondary trigger-modal" data-trigger-modal><?php _e( 'Export', 'charitable' ) ?></a>
</div>
<?php charitable_admin_view( 'donations-page/export-form' );
