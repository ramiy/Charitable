<?php
/**
 * Display a list of campaign categories or tags.
 *
 * @author  Studio 164a
 * @since   1.0.0
 */

echo $view_args[ 'before_widget' ];

if ( ! empty( $view_args[ 'title' ] ) ) :

    echo $view_args[ 'before_title' ] . $view_args[ 'title' ] . $view_args[ 'after_title' ];

endif;
?>
<ul class="charitable-terms-widget">
    <?php wp_list_categories( array(
        'title_li' => '',
        'taxonomy' => $view_args[ 'taxonomy' ], 
        'show_count' => $view_args[ 'show_count' ], 
        'hide_empty' => $view_args[ 'hide_empty' ]
    ) ) ?>
</ul><!-- .charitable-terms-widget -->

<?php echo $view_args[ 'after_widget' ];