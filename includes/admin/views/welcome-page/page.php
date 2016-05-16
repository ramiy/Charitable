<?php 
/**
 * Display the Welcome page. 
 *
 * @author  Studio 164a
 * @package Charitable/Admin View/Welcome Page
 * @since   1.0.0
 */

wp_enqueue_style( 'charitable-admin-pages' );

$gateways = Charitable_Gateways::get_instance()->get_active_gateways_names();
$campaigns = wp_count_posts( 'campaign' );
$campaigns_count = $campaigns->publish + $campaigns->draft + $campaigns->future + $campaigns->pending + $campaigns->private;
$emails = charitable_get_helper( 'emails' )->get_enabled_emails_names();
$install = isset( $_GET[ 'install' ] ) && $_GET[ 'install' ];

?>
<div class="wrap about-wrap charitable-wrap">
    <h1>
        <strong>Charitable</strong>
        <sup class="version"><?php echo charitable()->get_version() ?></sup>
    </h1>
    <div class="badge">
        <a href="https://www.wpcharitable.com/?utm_source=welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=home&amp;utm_content=icon" target="_blank"><i class="icon-charitable"></i></a>
    </div>
    <div class="intro">
        <?php 
        if ( $install ) : 
            _e( 'Thank you for installing Charitable!', 'charitable' );
        else : 
            printf( 
                __( 'Enjoying Charitable? Why not <a href="%s" target="_blank">leave a %s review</a> on WordPress.org? We\'d really appreciate it.', 'charitable' ), 
                'https://wordpress.org/support/view/plugin-reviews/charitable?rate=5#postform', 
                '<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>' 
            );
        endif;
        ?>
    </div>
    <hr />
    <div class="column-left">
        <div class="column-inside">
            <h2><?php _e( 'The WordPress Fundraising Toolkit', 'charitable' ) ?></h2>
            <p><?php _e( 'Charitable is everything you need to start accepting donations today. PayPal and offline donations work right out of the box, and when your organization is ready to grow, our extensions give you the tools you need to move forward.', 'charitable' ) ?></p>
            <hr />
            <h2><?php _e( 'Getting Started', 'charitable' ) ?></h2>
            <ul class="checklist">
                <?php if ( count( $gateways ) > 0 ) : ?>
                    <li class="done"><?php 
                        printf( 
                            _x( 'You have activated %s. <a href="%s">Change settings</a>', 'You have activated x and y. Change gateway settings.', 'charitable' ),
                            charitable_list_to_sentence_part( $gateways ),
                            admin_url( 'admin.php?page=charitable-settings&tab=gateways' )
                        ) ?>
                    </li>
                <?php else : ?>
                    <li class="not-done"><a href="<?php echo admin_url( 'admin.php?page=charitable-settings&tab=gateways' ) ?>"><?php _e( 'You need to enable a payment gateway', 'charitable' ) ?></a></li>
                <?php endif ?>
                <?php if ( $campaigns_count > 0 ) : ?>
                    <li class="done"><?php 
                        printf( 
                            __( 'You have created your first campaign. <a href="%s">Create another one.</a>', 'charitable' ), 
                            admin_url( 'post-new.php?post_type=campaign' ) 
                        ) ?>
                    </li>
                <?php else : ?>
                    <li class="not-done"><a href="<?php echo admin_url( 'post-new.php?post_type=campaign' ) ?>"><?php _e( 'Create your first campaign', 'charitable' ) ?></a></li>
                <?php endif ?>
                <?php if ( count( $emails ) > 0 ) : ?>
                    <li class="done"><?php 
                        printf( 
                            _x( 'You have turned on the %s. <a href="%s">Change settings</a>', 'You have activated x and y. Change email settings.', 'charitable' ),
                            charitable_list_to_sentence_part( $emails ),
                            admin_url( 'admin.php?page=charitable-settings&tab=emails' )
                        ) ?>
                    </li>
                <?php else : ?>
                    <li class="not-done"><a href="<?php echo admin_url( 'admin.php?page=charitable-settings&tab=emails' ) ?>"><?php _e( 'Turn on email notifications', 'charitable' ) ?></a></li>
                <?php endif ?>
            </ul>
            <p style="margin-bottom: 0;"><?php 
                printf( 
                    __( 'Need a hand with anything? You might find the answer in <a href="%s">our documentation</a>, or you can always get in touch with us via <a href="%s">our support page</a>.', 'charitable' ), 
                    'https://www.wpcharitable.com/documentation/?utm_source=welcome-page&utm_medium=wordpress-dashboard&utm_campaign=documentation',
                    'https://www.wpcharitable.com/support/?utm_source=welcome-page&utm_medium=wordpress-dashboard&utm_campaign=support'
                ) ?>
            </p>
        </div>
        <div class="upgrade">
            <h2><?php _e( 'Upgrade for a price you can afford', 'charitable' ) ?></h2>
            <p><?php _e( 'With Charitable, <strong>you choose how much you pay</strong> to upgrade. Why? Because we think that every great organization deserves awesome fundraising software, regardless of the size of its budget.', 'charitable' ) ?></p>
            <p><a href="https://www.wpcharitable.com/packages/?utm_source=welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=pwyw-packages" class="button-primary">Unlock more features</a></p>
        </div>
    </div>
    <div class="column-right">
        <div class="column-inside">            
            <img src="<?php echo charitable()->get_path( 'assets', false ) ?>images/reach-mockup.png" alt="<?php _e( 'Screenshot of Reach, a WordPress fundraising theme designed to complement Charitable', 'charitable' ) ?>" title="<?php _e( 'Reach is a WordPress fundraising theme designed to complement Charitable', 'charitable' ) ?>" style="margin-bottom: 21px;" width="336" height="166" />
            <h2><?php _e( 'Try Reach, a free theme designed for fundraising', 'charitable' ) ?></h2>
            <p><?php _e( 'We built Reach to help non-profits &amp; social entrepreneurs run beautiful online fundraising campaigns. Whether you’re creating a website for your organization’s peer-to-peer fundraising event or building an online crowdfunding platform, Reach is the perfect starting point.', 'charitable' ) ?></p>
            <p><a href="https://www.wpcharitable.com/download-reach/?utm_source=welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=reach" class="button-primary" style="margin-right: 8px;" target="_blank"><?php _e( 'Download it free', 'charitable' ) ?></a><a href="http://demo.wpcharitable.com/reach/?utm_source=welcome-page&amp;utm_medium=wordpress-dashboard&amp;utm_campaign=reach" class="button-secondary" target="_blank"><?php _e( 'View demo', 'charitable' ) ?></a></p>
            <hr />
            <h2><?php _e( 'Contribute to Charitable', 'charitable' ) ?></h2>
            <p><?php printf( 
                    __( 'Found a bug? Want to contribute a patch or create a new feature? <a href="%s">GitHub is the place to go!</a> Or would you like to translate Charitable into your language? <a href="%s">Get involved on WordPress.org</a>.', 'charitable' ),
                    'https://github.com/Charitable/Charitable',  
                    'https://translate.wordpress.org/projects/wp-plugins/charitable' 
                ) ?>
            </p>
        </div>    
    </div>
</div>