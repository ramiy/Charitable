<?php 
/**
 * Display the Welcome page. 
 *
 * @author  Studio 164a
 * @package Charitable/Admin View/Welcome Page
 * @since   1.0.0
 */

$page = $view_args[ 'page' ];

?>
<div class="wrap about-wrap">
    <h1>
        <?php echo $page->get_page_title() ?>
        <span class="charitable-version"><?php echo charitable()->get_version() ?></span>
    </h1>
    <div class="about-text">
    </div>
    <div class="charitable-badge">
        <i class="icon-charitable"></i>
    </div>
</div>