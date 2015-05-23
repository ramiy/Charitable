<?php 
/**
 * Displays the donate button to be displayed on campaign pages. 
 *
 * @author  Studio 164a
 * @since   1.0.0
 */

wp_print_scripts( 'lean-modal' );
wp_enqueue_style( 'lean-modal-css' );
?>
<div id="charitable-donation-form-modal" style="display: none;" class="charitable-modal">
    <?php charitable_get_current_campaign()->get_donation_form()->render() ?>
</div>
<script type="text/javascript">
( function( $ ) {
    $('[data-trigger-modal]').leanModal({
        closeButton : ".close-modal"
    });
})( jQuery );
</script>
