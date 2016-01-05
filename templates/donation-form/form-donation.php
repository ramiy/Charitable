<?php
/**
 * The template used to display the default form.
 *
 * @author  Studio 164a
 * @package Charitable/Templates/Donation Form
 * @since   1.0.0
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$form = $view_args[ 'form' ];
$user_fields = $form->get_user_fields();
$user = wp_get_current_user();

if ( ! $form ) {
    return;
}

?>
<form method="post" id="charitable-donation-form" class="charitable-form">
    <?php 
    /**
     * @hook    charitable_form_before_fields
     */
    do_action( 'charitable_form_before_fields', $form ) ?>
    
    <div class="charitable-form-fields cf">        
    <?php 

    $i = 1;

    foreach ( $form->get_fields() as $key => $field ) :

        do_action( 'charitable_form_field', $field, $key, $form, $i );
    
        $i += apply_filters( 'charitable_form_field_increment', 1, $field, $key, $form, $i );

    endforeach;

    ?>
    
    </div>

    <?php
    /**
     * @hook    charitable_form_after_fields
     */
    do_action( 'charitable_form_after_fields', $form );

    ?>
    <div class="charitable-form-field charitable-submit-field">
        <input class="button button-primary" type="submit" name="donate" value="<?php esc_attr_e( 'Donate', 'charitable' ) ?>" />
        <div class="charitable-form-processing" style="display: none;">
            <img src="<?php echo charitable()->get_path( 'assets', false ) ?>/images/charitable-loading.gif" width="60" height="60" alt="<?php esc_attr_e( 'Loading&hellip;', 'charitable' ) ?>" />
        </div>
    </div>
</form>