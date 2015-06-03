<?php
/**
 * Class that models the new donation email.
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Email_New_Donation
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Email_New_Donation' ) ) : 

/**
 * New Donation Email 
 *
 * @since       1.0.0
 */
class Charitable_Email_New_Donation extends Charitable_Email {

    /**
     * @var     string
     */
    CONST ID = 'new_donation';

    /**
     * @var     Charitable_Donation
     */
    protected $donation;

    /**
     * Instantiate the email class, defining its key values.
     *
     * @param   Charitable_Donation|null $donation 
     * @access  public
     * @since   1.0.0
     */
    public function __construct( $donation = null ) {
        $this->name = apply_filters( 'charitable_email_new_donation_name', __( 'New Donation Notification', 'charitable' ) );        
        $this->donation = $donation;
    }

    /**
     * Register email settings. 
     *
     * @param   array   $settings
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function email_settings( $settings ) {
        return array(
            'section_email' => array(
                'type'      => 'heading',
                'title'     => $this->get_name(),
                'priority'  => 2
            ),         
            'recipients' => array(
                'type'      => 'text',
                'title'     => __( 'Recipients', 'charitable' ), 
                'help'      => __( 'A comma-separated list of email address that will receive this email.', 'charitable' ),
                'priority'  => 4, 
                'class'     => 'wide', 
                'default'   => $this->get_default_recipient()
            ),
            'subject' => array(
                'type'      => 'text',
                'title'     => __( 'Email Subject Line', 'charitable' ), 
                'help'      => __( 'The ubject line of the email when it is delivered to recipients.', 'charitable' ),
                'priority'  => 6, 
                'class'     => 'wide', 
                'default'   => $this->get_default_subject()
            ), 
            'body' => array(
                'type'      => 'editor',
                'title'     => __( 'Email Body', 'charitable' ), 
                'help'      => __( 'The content of the email that will be delivered to recipients. HTML is accepted.', 'charitable' ), 
                'priority'  => 10, 
                'default'   => $this->get_default_body()
            ),
            'preview' => array(
                'type'      => 'content',
                'title'     => __( 'Preview', 'charitable' ),
                'content'   => sprintf( '<a href="%s" title="%s" target="_blank" class="button">%s</a>', 
                    esc_url( 
                        add_query_arg( array( 
                            'charitable_action' => 'preview_email',
                            'email_id' => $this::ID
                        ), site_url() )
                    ), 
                    __( 'Preview email in your browser', 'charitable' ),
                    __( 'Preview email', 'charitable' )
                ),
                'priority'  => 14
            )
        );        
    }

    /**
     * Return the default recipient for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_recipient() {
        return get_option( 'admin_email' );
    }

    /**
     * Return the default subject line for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_subject() {
        return __( 'You have received a new donation', 'charitable' );   
    }

    /**
     * Return the default headline for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_headline() {
        return apply_filters( 'charitable_email_donation_receipt_default_headline', __( 'New Donation', 'charitable' ), $this );    
    }

    /**
     * Return the default body for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_body() {
        ob_start();
?>
        <p>[charitable_email show=donor] has just made a donation!</p>
        <p>Summary:<br />
        [charitable_email show=donation_summary]</p>
<?php
        $body = ob_get_clean();

        return apply_filters( 'charitable_email_new_donation_default_body', $body, $this );
    }    
}

endif; // End class_exists check