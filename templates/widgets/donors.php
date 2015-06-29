<?php
/**
 * Display a widget with donors, either for a specific campaign or sitewide.
 *
 * @author  Studio 164a
 * @since   1.0.0
 */

$widget_title   = apply_filters( 'widget_title', $view_args['title'] );
$donors         = $view_args[ 'donors' ];

/* If there are no donors and the widget is configured to hide when empty, return now. */
if ( ! $donors->count() && $view_args[ 'hide_if_no_donors' ] ) {
    return;
}

echo $view_args['before_widget'];

if ( ! empty( $widget_title ) ) :
    echo $view_args['before_title'] . $widget_title . $view_args['after_title'];
endif;

if ( $donors->count() ) : 
    ?>
    
    <ul class="donors-list">

        <?php foreach ( $donors as $donor ) : 

            $donor_object = Charitable_User::init_with_donor( $donor->donor_id );
            ?>

            <li class="donor">  

                <?php 

                echo $donor_object->get_avatar();
                
                if ( $view_args[ 'show_name'] ) : ?>

                    <h6 class="donor-name"><?php printf( '%s %s', $donor->first_name, $donor->last_name ) ?></h6>

                <?php 

                endif;

                if ( $view_args[ 'show_location' ] ) : ?>

                    <span clss="donor-location"><?php echo $donor_object->get_location() ?></span>

                <?php 

                endif;

                if ( $view_args[ 'show_amount' ] ) : ?>

                    <span clss="donor-donation-amount"><?php echo charitable_get_currency_helper()->get_monetary_amount( $donor->amount ) ?></span>

                <?php endif ?>

            </li>

        <?php endforeach ?>

    </ul>

<?php
else : 

    ?>

    <p><?php _e( 'No donors yet. Be the first!', 'charitable' ) ?></p>

    <?php

endif;

echo $view_args['after_widget'];