<?php
/**
 * Display a list of campaigns.
 *
 * @author  Studio 164a
 * @since   1.0.0
 */

$campaigns = $view_args[ 'campaigns' ];

if ( ! $campaigns->have_posts() ) :
    return;
endif;

echo $view_args[ 'before_widget' ];

if ( ! empty( $view_args[ 'title' ] ) ) :

    echo $view_args[ 'before_title' ] . $view_args[ 'title' ] . $view_args[ 'after_title' ];

endif;
?>

<ul class="campaigns">

<?php while( $campaigns->have_posts() ) : 
    $campaigns->the_post();

    $campaign = new Charitable_Campaign( get_the_ID() );
    ?>

    <li class="campaign">
        <h6 class="campaign-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h6>
        <?php if ( ! $campaign->is_endless() ) : ?>
            
            <div class="campaign-time-left"><?php echo $campaign->get_time_left() ?>
        
        <?php endif ?>
    </li>

<?php endwhile ?>

</ul>

<?php

echo $view_args[ 'after_widget' ];