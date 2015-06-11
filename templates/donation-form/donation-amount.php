<?php
/**
 * The template used to display the donation amount inputs.
 *
 * @author  Studio 164a
 * @since   1.0.0
 * @version 1.0.0
 */

$campaign           = $view_args[ 'campaign' ];
$suggested_donations = $campaign->get_suggested_donations();
$currency_helper    = charitable()->get_currency_helper();

if ( empty( $suggested_donations ) && ! $campaign->get( 'allow_custom_donations' ) ) {
    return;
}

/**
 * @hook    charitable_donation_form_before_donation_amount
 */
do_action( 'charitable_donation_form_before_donation_amount', $view_args[ 'form' ] );

if ( count( $suggested_donations ) ) : 
?>
<ul class="donation-amounts">
    <?php   
    foreach ( $suggested_donations as $suggestion ) : 
        ?>
        <li class="donation-amount suggested-donation-amount">
            <input type="radio" name="donation-amount" value="<?php echo $suggestion[ 'amount' ] ?>" /><?php 
            printf( '<span class="amount">%s</span> <span class="description">%s</span>', 
                $currency_helper->get_monetary_amount( $suggestion[ 'amount' ] ), 
                strlen( $suggestion[ 'description' ] ) ? $suggestion[ 'description' ] : ''
            ) ?>
        </li>
        <?php 
    endforeach;

    if ( $campaign->get( 'allow_custom_donations' ) ) :
    ?>

    <li class="donation-amount custom-donation-amount">                
        <input type="radio" name="donation-amount" value="custom" />
        <span class="description"><?php _e( 'Custom amount', 'charitable' ) ?></span>
        <input type="text" name="custom-donation-amount" />
    </li>

    <?php endif ?>

</ul>

<?php elseif ( $campaign->get( 'allow_custom_donations' ) ) : ?>

    <div id="custom-donation-amount-field" class="charitable-form-field charitable-custom-donation-field-alone">
        <input type="text" name="custom-donation-amount" placeholder="<?php esc_attr_e( 'Enter donation amount', 'charitable' ) ?>" />
    </div>

<?php 

endif;

/**
 * @hook    charitable_donation_form_after_donation_amount
 */
do_action( 'charitable_donation_form_after_donation_amount', $view_args[ 'form' ]); ?>