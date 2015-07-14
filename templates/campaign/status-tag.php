<?php 
/**
 * Displays the campaign status tag.
 *
 * @author  Studio 164a
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$campaign = $view_args[ 'campaign' ];

?>
<div class="campaign-status-tag">  
    <?php echo $campaign->get_status_tag() ?>
</div>