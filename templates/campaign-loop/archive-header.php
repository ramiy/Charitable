<?php 
/**
 * Adds page header to campaign archive template.
 *
 * The classis based on Underscores, so this should work with any themes based 
 * on Underscores. If you notice problems with your theme, you can override this 
 * template by copying it to yourtheme/charitable/campaign-loop/archive-header.php
 *
 * @author  Studio 164a
 * @package Charitable/Templates/Campaign
 * @since   1.3.0
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<header class="page-header">
    <?php
        the_archive_title( '<h1 class="page-title">', '</h1>' );
        the_archive_description( '<div class="taxonomy-description">', '</div>' );
    ?>
</header><!-- .page-header -->