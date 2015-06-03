<?php
/**
 * Email model
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Email
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Email' ) ) : 

/**
 * Charitable_Email
 *
 * @abstract
 * @since       1.0.0
 */
abstract class Charitable_Email {    

    /**
     * @var     string  The email's unique identifier.
     */
    const ID = '';

    /**
     * @var     string  Descriptive name of the email.
     * @access  protected
     * @since   1.0.0
     */
    protected $name;

    /**
     * Return the email name.
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get from name for email. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_from_name() {
        return wp_specialchars_decode( charitable_get_option( 'email_from_name' ) );
    }

    /**
     * Get from address for email. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_from_address() {
        return charitable_get_option( 'email_from_address' );
    }

    /**
     * Get the email content type
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_content_type() {
        return apply_filters( 'charitable_email_content_type', 'text/html', $this );
    }

    /**
     * Get the email headers.
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_headers() {
        if ( ! $this->headers ) {
            $this->headers  = "From: {$this->get_from_name()} <{$this->get_from_address()}>\r\n";
            $this->headers .= "Reply-To: {$this->get_from_address()}\r\n";
            $this->headers .= "Content-Type: {$this->get_content_type()}; charset=utf-8\r\n";
        }

        return apply_filters( 'charitable_email_headers', $this->headers, $this );
    }

    /**
     * Return the value of a specific field to be displayed in the email. 
     *
     * This is used by Charitable_Emails::email_shortcode() to obtain the value of the
     * particular field that was referenced in the shortcode. The second argument is
     * an optional array of arguments.
     *
     * @param   string  $field
     * @param   array   $args   Optional. May contain additional arguments. 
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_value( $field, $args = array() ) {
        $fields = $this->get_fields();

        if ( ! isset( $fields[ $field ] ) ) {
            return '';
        }

        if ( isset( $args[ 'preview' ] ) && $args[ 'preview' ] ) {
            return $this->get_preview_field_content( $field );
        }

        add_filter( 'charitable_email_content_field_value_' . $field, $fields[ $field ], 10, 3 );

        return apply_filters( 'charitable_email_content_field_value_' . $field, '', $field, $args );
    }

    /**
     * Returns all fields that can be displayed using the [charitable_email] shortcode.
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function get_fields() {
        return apply_filters( 'charitable_email_content_fields', array(
            'site_name'     => array( $this, 'get_site_name' ), 
            'site_url'      => array( $this, 'home_url' )
        ), $this );
    }

    /**
     * Return the site/blog name. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_site_name() {
        return get_option( 'blogname' );
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
            'subject' => array(
                'type'      => 'text',
                'title'     => __( 'Email Subject Line', 'charitable' ), 
                'help'      => __( 'The email subject line when it is delivered to recipients.', 'charitable' ),
                'priority'  => 6, 
                'class'     => 'wide', 
                'default'   => $this->get_default_subject()
            ), 
            'headline' => array(
                'type'      => 'text',
                'title'     => __( 'Email Headline', 'charitable' ), 
                'help'      => __( 'The headline displayed at the top of the email.', 'charitable' ),
                'priority'  => 10, 
                'class'     => 'wide', 
                'default'   => $this->get_default_headline()
            ), 
            'body' => array(
                'type'      => 'editor',
                'title'     => __( 'Email Body', 'charitable' ), 
                'help'      => __( 'The content of the email that will be delivered to recipients. HTML is accepted.', 'charitable' ), 
                'priority'  => 14, 
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
                'priority'  => 18
            )
        );
    } 

    /**
     * Sends the email.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function send() {
        do_action( 'charitable_before_send_email', $this );

        $subject = do_shortcode( $this->get_subject() );

        do_action( 'charitable_after_send_email', $this );
    }

    /**
     * Preview the email. This will display a sample email within the browser. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function preview() {
        add_filter( 'shortcode_atts_charitable_email', array( $this, 'set_preview_mode' ) );

        do_action( 'charitable_before_preview_email', $this );        

        return $this->build_email();
    }

    /**
     * Set preview mode in the shortcode attributes. 
     *
     * @param   array   $atts
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function set_preview_mode( $atts ) {
        $atts[ 'preview' ] = true;
        return $atts;
    }

    /**
     * Build the email.  
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function build_email() {
        ob_start();

        charitable_template( 'emails/header.php', array( 'email' => $this ) );

        charitable_template( 'emails/body.php', array( 'email' => $this ) );

        charitable_template( 'emails/footer.php', array( 'email' => $this ) );

        $message = ob_get_clean();

        return apply_filters( 'charitable_email_message', $message, $this );
    }

    /**
     * Returns the body content of the email, formatted as HTML. 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_body() {
        $body = $this->get_option( 'body', $this->get_default_body() );
        $body = do_shortcode( $body );
        $body = wpautop( $body );
        return apply_filters( 'charitable_email_body', $body, $this );
    }

    /**
     * Return the value of an option specific to this email. 
     *
     * @param   string  $key
     * @return  mixed
     * @access  protected
     * @since   1.0.0
     */
    protected function get_option( $key, $default ) {
        return charitable_get_option( array( $this::ID, $key ), $default );
    }

    /**
     * Return the default recipient for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_recipient() {
        return "";
    }

    /**
     * Return the default subject line for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_subject() {
        return "";   
    }

    /**
     * Return the default headline for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_headline() {
        return "";   
    }

    /**
     * Return the default body for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_body() {
        return "";
    }  

    /**
     * Returns the value of a particular field (generally 
     * called through the [charitable_email] shortcode). 
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_field_content( $field ) {
        $fields = $this->get_fields();

        if ( ! isset( $fields[ $field ] ) ) {
            return '';
        }

        return call_user_func( $fields[ $field ] );
    }    

    /**
     * Return the value of a field for the preview.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_preview_field_content( $field ) {
        $values = apply_filters( 'charitable_email_preview_content_fields', array(
            'site_name'     => get_option( 'blogname' ), 
            'site_url'      => home_url()
        ), $this );

        if ( ! isset( $values[ $field ] ) ) {
            return $field;
        }

        return $values[ $field ];
    }
}

endif; // End class_exists check