<?php 
/**
 * Displays the campaign video. 
 *
 * @author  Studio 164a
 * @since   1.0.0
 */

$campaign = $view_args[ 'campaign' ];

?>
<div class="campaign-video">  
    <?php echo $campaign->embed_video() ?>
</div>