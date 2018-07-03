<?php

/**
 * WordPress settings API class
 *
 * @author Tareq Hasan
 */

class SatSMS_Setting_Options {

    private $settings_api;

    function __construct() {

        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    /**
     * Admin init hook
     * @return void
     */
    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Admin Menu CB
     * @return void
     */
    function admin_menu() {
        add_menu_page( __( 'SMS Settings', 'satosms' ), __( 'SMS Settings', 'satosms' ), 'manage_options', 'sat-order-sms-notification-settings', array( $this, 'plugin_page' ), 'dashicons-email-alt' );
        add_submenu_page( 'sat-order-sms-notification-settings', __( 'Send SMS to Any', 'satosms' ), __( 'Send SMS to Any', 'satosms' ), 'manage_options', 'sat-order-sms-send-any', array( $this, 'send_sms_to_any' ) );
    }

    /**
     * Send SMS to any submenu callback
     * @return void
     */
    function send_sms_to_any() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'Send SMS to Any Number', 'satosms' ); ?></h1>
            <div class="postbox send_sms_to_any_notice">
                <p><?php _e( 'Make sure that the message recipient number must have country code as an extension with phone number ( e.g: the number format must be like this <code>+8801671123456</code>), where <code>+88</code> country code as an extension', 'satosms' ); ?></p>
            </div>
            <?php if( isset( $_GET['message'] ) && $_GET['message'] == 'error' ): ?>
                <div class="error">
                    <p><?php _e( '<strong>Error:</strong> Receiver phone number must be required', 'satosms' ) ?></p>
                </div>
            <?php endif; ?>

            <?php if( isset( $_GET['message'] ) && $_GET['message'] == 'gateway_problem' ): ?>
                <div class="error">
                    <p><?php _e( '<strong>Error:</strong> Gateway doesn\'t Set', 'satosms' ); ?> </p>
                </div>
            <?php endif; ?>

            <?php if( isset( $_GET['message'] ) && $_GET['message'] == 'sending_failed' ): ?>
                <div class="error">
                    <p><?php _e( '<strong>Error:</strong> Message Sending faild, Please check your number or gateway settings', 'satosms' ); ?></p>
                </div>
            <?php endif; ?>

            <?php if( isset( $_GET['message'] ) && $_GET['message'] == 'success' ): ?>
                <div class="updated">
                    <p><?php _e( '<strong>Success:</strong> Message Sucessfully Send to Receiver', 'satosms' ) ?></p>
                </div>
            <?php endif; ?>

            <div class="postbox " id="satosms_send_sms_any">
                <h3 class="hndle">Send SMS to Any</h3>
                <div class="inside">
                    <form class="initial-form" id="satosms-send-sms-any-form" method="post" action="" name="post">
                        <p>
                            <label for="satosms_receiver_number"><?php _e( 'Receiver Number', 'satosms' ) ?></label><br>
                            <input type="text" name="satosms_receiver_number" id="satosms_receiver_number">
                            <span><?php _e( 'Enter your sms receiver number, number must have an extension') ?></span>
                        </p>

                        <p>
                            <label for="satosms_sms_body"><?php _e( 'SMS Body', 'satosms' ) ?></label><br>
                            <textarea name="satosms_sms_body" id="satosms_sms_body" cols="50" rows="6"></textarea>
                            <span><?php _e( 'Enter your message body what you like you want to send this receiver') ?></span>
                        </p>

                        <p>
                            <?php wp_nonce_field( 'send_sms_to_any_action','send_sms_to_any_nonce' ); ?>
                            <input type="submit" class="button button-primary" name="satosms_send_sms" value="<?php _e( 'Send SMS', 'satosms' ); ?>">
                        </p>

                    </form>
                </div>
            </div>

        </div>
        <?php
    }

    /**
     * Get All settings Field
     * @return array
     */
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'satosms_general',
                'title' => __( 'General Settings', 'satosms' )
            ),
            array(
                'id' => 'satosms_gateway',
                'title' => __( 'SMS Gateway Settings', 'satosms' )
            ),

            array(
                'id' => 'satosms_message',
                'title' => __( 'SMS Settings', 'satosms' )
            ),

            array(
                'id' => 'satosms_message_diff_status',
                'title' => __( 'SMS Body Settings', 'satosms' )
            )
        );
        return apply_filters( 'satosms_settings_sections' , $sections );
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {


        $buyer_message = "Thanks for purchasing\nYour [order_id] is now [order_status]\nThank you";
        $admin_message = "You have a new Order\nThe [order_id] is now [order_status]\n";
        $settings_fields = array(

            'satosms_general' => apply_filters( 'satosms_general_settings', array(
                array(
                    'name' => 'enable_notification',
                    'label' => __( 'Enable SMS Notifications', 'satosms' ),
                    'desc' => __( 'If checked then enable your sms notification for new order', 'satosms' ),
                    'type' => 'checkbox',
                ),

                array(
                    'name' => 'admin_notification',
                    'label' => __( 'Enable Admin Notifications', 'satosms' ),
                    'desc' => __( 'If checked then enable admin sms notification for new order', 'satosms' ),
                    'type' => 'checkbox',
                    'default' => 'on'
                ),

                array(
                    'name' => 'buyer_notification',
                    'label' => __( 'Enable buyer Notification', 'satosms' ),
                    'desc' => __( 'If checked then buyer can get notification options in checkout page', 'satosms' ),
                    'type' => 'checkbox',
                ),

                array(
                    'name' => 'force_buyer_notification',
                    'label' => __( 'Force buyer notification', 'satosms' ),
                    'desc' => __( 'If select yes then buyer notification option must be required in checkout page', 'satosms' ),
                    'type' => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'   => 'No'
                    )
                ),

                array(
                    'name' => 'buyer_notification_text',
                    'label' => __( 'Buyer Notification Text', 'satosms' ),
                    'desc' => __( 'Enter your text which is appeared in checkout page for buyer as a checkbox', 'satosms' ),
                    'type' => 'textarea',
                    'default' => 'Send me order status notifications via SMS (N.B.: Your SMS will be sent in your billing email. Make sure phone number must have an extension)'
                ),
                array(
                    'name' => 'order_status',
                    'label' => __( 'Check Order Status ', 'satosms' ),
                    'desc' => __( 'In which status you will send notifications', 'satosms' ),
                    'type' => 'multicheck',
                    'options' => wc_get_order_statuses()
                )
            ) ),

            'satosms_gateway' => apply_filters( 'satosms_gateway_settings',  array(
                array(
                    'name' => 'sms_gateway',
                    'label' => __( 'Select your Gateway', 'satosms' ),
                    'desc' => __( 'Select your sms gateway', 'satosms' ),
                    'type' => 'select',
                    'default' => '-1',
                    'options' => $this->get_sms_gateway()
                ),
            ) ),

            'satosms_message' => apply_filters( 'satosms_message_settings',  array(
                array(
                    'name' => 'sms_admin_phone',
                    'label' => __( 'Enter your Phone Number with extension', 'satosms' ),
                    'desc' => __( '<br>Admin order sms notifications will be send in this number. Please make sure that the number must have a extension (e.g.: +8801626265565 where +88 will be extension)', 'satosms' ),
                    'type' => 'text'
                ),
                array(
                    'name' => 'admin_sms_body',
                    'label' => __( 'Enter your SMS body', 'satosms' ),
                    'desc' => __( ' Write your custom message. When an order is create then you get this type of format message. For order id just insert <code>[order_id]</code> and for order status insert <code>[order_status]</code>. Similarly <code>[order_items]</code>, <code>[order_amount]</code>, <code>[billing_firstname]</code>, <code>[billing_lastname]</code>, <code>[billing_email]</code>, <code>[billing_address1]</code>, <code>[billing_address2]</code>, <code>[billing_country]</code>, <code>[billing_city]</code>, <code>[billing_state]</code>, <code>[billing_postcode]</code>, <code>[shipping_address1]</code>, <code>[shipping_country]</code>, <code>[shipping_city]</code>, <code>[shipping_state]</code>, <code>[shipping_postcode]</code>, <code>[payment_method]</code>,<code>[provider]</code>,<code>[tracking_number]</code>,<code>[date_shipped]</code>,', 'satosms' ),
                    'type' => 'textarea',
                    'default' => __( $admin_message, 'satosms' )
                ),

                array(
                    'name' => 'sms_body',
                    'label' => __( 'Enter buyer SMS body', 'satosms' ),
                    'desc' => __( ' Write your custom message. If enbale buyer notification options then buyer can get this message in this format. For order id just insert <code>[order_id]</code> and for order status insert <code>[order_status]</code>. Similarly <code>[order_items]</code>, <code>[order_amount]</code>, <code>[billing_firstname]</code>, <code>[billing_lastname]</code>, <code>[billing_email]</code>, <code>[billing_address1]</code>, <code>[billing_address2]</code>, <code>[billing_country]</code>, <code>[billing_city]</code>, <code>[billing_state]</code>, <code>[billing_postcode]</code>, <code>[shipping_address1]</code>, <code>[shipping_country]</code>, <code>[shipping_city]</code>, <code>[shipping_state]</code>, <code>[shipping_postcode]</code>, <code>[payment_method]</code>,<code>[provider]</code>,<code>[tracking_number]</code>,<code>[date_shipped]</code>,', 'satosms' ),
                    'type' => 'textarea',
                    'default' => __( $buyer_message, 'satosms' )
                ),
            ) ),

            'satosms_message_diff_status' => apply_filters( 'satosms_message_diff_status_settings',  array(

                array(
                    'name' => 'enable_diff_status_mesg',
                    'label' => __( 'Enable different message for different order status', 'satosms' ),
                    'desc' => __( 'If checked then admin and buyer get sms body content according with different enabled order status', 'satosms' ),
                    'type' => 'checkbox'
                ),

            ) ),
        );

        return apply_filters( 'satosms_settings_section_content', $settings_fields );
    }

    /**
     * Loaded Plugin page
     * @return void
     */
    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

    /**
     * Get sms Gateway settings
     * @return array
     */
    function get_sms_gateway() {
        $gateway = array(
            'none'         => __( '--select--', 'satosms' ),
            'talkwithtext' => __( 'Talk With Text', 'satosms' ),
            'twilio'       => __( 'Twilio', 'satosms' ),
            'clickatell'   => __( 'Clickatell', 'satosms' ),
            'nexmo'        => __( 'Nexmo', 'satosms' ),
            'smsglobal'    => __( 'SMS global', 'satosms' ),
            'hoiio'        => __( 'Hoiio', 'satosms' ),
            'intellisms'   => __( 'Intellisms', 'satosms' ),
            'infobip'      => __( 'Infobip', 'satosms' ),
            'atompark'      => __( 'AtomPark', 'satosms' ),
            'smsidindia'      => __( 'Sms Idindia', 'satosms' ),
        );

        return apply_filters( 'satosms_sms_gateway', $gateway );
    }

} // End of SatSMS_Setting_Options Class

/**
 * SMS Gateway Settings Extra panel options
 * @return void
 */
function satosms_settings_field_gateway() {

    $talkwithtext_username   = satosms_get_option( 'talkwithtext_username', 'satosms_gateway', '' );
    $talkwithtext_password   = satosms_get_option( 'talkwithtext_password', 'satosms_gateway', '' );
    $talkwithtext_originator = satosms_get_option( 'talkwithtext_originator', 'satosms_gateway', '' );
    $twilio_from_number      = satosms_get_option( 'twilio_from_number', 'satosms_gateway', '' );
    $twilio_sid              = satosms_get_option( 'twilio_sid', 'satosms_gateway', '' );
    $twilio_token            = satosms_get_option( 'twilio_token', 'satosms_gateway', '' );
    $clickatell_name         = satosms_get_option( 'clickatell_name', 'satosms_gateway', '' );
    $clickatell_password     = satosms_get_option( 'clickatell_password', 'satosms_gateway', '' );
    $clickatell_api          = satosms_get_option( 'clickatell_api', 'satosms_gateway', '' );
    $nexmo_api               = satosms_get_option( 'nexmo_api', 'satosms_gateway', '' );
    $nexmo_api_secret        = satosms_get_option( 'nexmo_api_secret', 'satosms_gateway', '' );
    $nexmo_from_name         = satosms_get_option( 'nexmo_from_name', 'satosms_gateway', '' );
    $smsglobal_name          = satosms_get_option( 'smsglobal_name', 'satosms_gateway', '' );
    $smsglobal_password      = satosms_get_option( 'smsglobal_password', 'satosms_gateway', '' );
    $smsglobal_sender_no     = satosms_get_option( 'smsglobal_sender_no', 'satosms_gateway', '' );
    $hoiio_appid             = satosms_get_option( 'hoiio_appid', 'satosms_gateway', '' );
    $hoiio_accesstoken       = satosms_get_option( 'hoiio_accesstoken', 'satosms_gateway', '' );
    $intellisms_username     = satosms_get_option( 'intellisms_username', 'satosms_gateway', '' );
    $intellisms_password     = satosms_get_option( 'intellisms_password', 'satosms_gateway', '' );
    $intellisms_sender_no    = satosms_get_option( 'intellisms_sender_no', 'satosms_gateway', '' );
    $infobip_username        = satosms_get_option( 'infobip_username', 'satosms_gateway', '' );
    $infobip_password        = satosms_get_option( 'infobip_password', 'satosms_gateway', '' );
    $infobip_sender_no       = satosms_get_option( 'infobip_sender_no', 'satosms_gateway', '' );

    $atompark_username        = satosms_get_option( 'atompark_username', 'satosms_gateway', '' );
    $atompark_password        = satosms_get_option( 'atompark_password', 'satosms_gateway', '' );
    $atompark_sender_no       = satosms_get_option( 'atompark_sender_no', 'satosms_gateway', '' );
   
    $smsidindia_username      = satosms_get_option( 'smsidindia_username', 'satosms_gateway', '' );
    $smsidindia_password      = satosms_get_option( 'smsidindia_password', 'satosms_gateway', '' );



    $twt_helper        = sprintf( 'Please fill talk with text username and password. If not then visit <a href="%s" target="_blank">%s</a>', 'http://my.talkwithtext.com/', 'Talk With Text' );
    $twilio_helper     = sprintf( 'Please fill Twilio informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'https://www.twilio.com/login', 'Twilio');
    $clickatell_helper = sprintf( 'Please fill Clickatell informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'https://www.clickatell.com/login/', 'Clickatell');
    $nexmo_helper      = sprintf( 'Please fill Nexmo informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'https://dashboard.nexmo.com/login', 'Nexmo');
    $smsglobal_helper  = sprintf( 'Please fill SMSGlobal informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'https://www.smsglobal.com/', 'SMSGlobal');
    $hoiio_helper      = sprintf( 'Please fill Hoiio informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'http://www.hoiio.com/', 'Hoiio');
    $intellisms_helper = sprintf( 'Please fill Intellisms informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'www.intellisms.co.uk', 'Intellisms');
    $infobip_helper    = sprintf( 'Please fill Infobip informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'http://www.infobip.com/sign_up/', 'Infobip');
    $atompark_helper   = sprintf( 'Please fill atompark informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'http://my.atompark.com/registration/', 'Atompark');
 $smsidindia_helper   = sprintf( 'Please fill smsidindia informations. If not then go to <a href="%s" target="_blank">%s</a> and get your informations', 'smsidindia', 'Sms Idindia');

    ?>

    <?php do_action( 'satosms_gateway_settings_options_before' ); ?>

    <div class="talkwithtext_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $twt_helper, 'satosms' ); ?></strong>
        </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text Username', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_username]" id="satosms_gateway[talkwithtext_username]" value="<?php echo $talkwithtext_username; ?>">
                    <span><?php _e( 'The HTTP API username that is supplied to your account (most of the times it is your email)', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text Password', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_password]" id="satosms_gateway[talkwithtext_password]" value="<?php echo $talkwithtext_password; ?>">
                    <span><?php _e( 'The HTTP API password of your account', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Talk with text Originator', 'satosms' ); ?></th>
                <td>
                    <input type="text" name="satosms_gateway[talkwithtext_originator]" id="satosms_gateway[talkwithtext_originator]" value="<?php echo $talkwithtext_originator; ?>">
                    <span><?php _e( 'The originator of your message (11 alphanumeric or 14 numeric values)', 'satosms' ); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="twilio_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $twilio_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Twilio From Number', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[twilio_from_number]" id="satosms_gateway[twilio_from_number]" value="<?php echo $twilio_from_number; ?>">
                    <span><?php _e( 'From which number the message will be sent to the users', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Twillo Account SID', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[twilio_sid]" id="satosms_gateway[twilio_sid]" value="<?php echo $twilio_sid; ?>">
                    <span><?php _e( 'Twilio Account SID', 'satosms' ); ?></span>

                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Twillo Authentication Token', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[twilio_token]" id="satosms_gateway[twilio_token]" value="<?php echo $twilio_token; ?>">
                    <span><?php _e( 'Twilio AUTH Token', 'satosms' ); ?></span>
                </td>
            </tr>
        </table>
    </div>


    <div class="clickatell_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $clickatell_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Clickatell name', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[clickatell_name]" id="satosms_gateway[clickatell_name]" value="<?php echo $clickatell_name; ?>">
                    <span><?php _e( 'Clickatell Username', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Clickatell Password', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[clickatell_password]" id="satosms_gateway[clickatell_password]" value="<?php echo $clickatell_password; ?>">
                    <span><?php _e( 'Clickatell password', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Clickatell api', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[clickatell_api]" id="satosms_gateway[clickatell_api]" value="<?php echo $clickatell_api; ?>">
                    <span><?php _e( 'Clickatell API id', 'satosms' ); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="nexmo_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $nexmo_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Nexmo API', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[nexmo_api]" id="satosms_gateway[nexmo_api]" value="<?php echo $nexmo_api; ?>">
                    <span><?php _e( 'Nexmo API key', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Nexmo API Secret', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[nexmo_api_secret]" id="satosms_gateway[nexmo_api_secret]" value="<?php echo $nexmo_api_secret; ?>">
                    <span><?php _e( 'Nexmo API secret', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Nexmo From Name', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[nexmo_from_name]" id="satosms_gateway[nexmo_from_name]" value="<?php echo $nexmo_from_name; ?>">
                    <span><?php _e( 'From which name the message will be sent to the users ( Default : NEXMO )', 'satosms' ); ?></span>
                </td>
            </tr>

        </table>
    </div>

    <div class="smsglobal_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $smsglobal_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'SMSGlobal Name', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[smsglobal_name]" id="satosms_gateway[smsglobal_name]" value="<?php echo $smsglobal_name; ?>">
                    <span><?php _e( 'SMS Global Username', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'SMSGlobal Password', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[smsglobal_password]" id="satosms_gateway[smsglobal_password]" value="<?php echo $smsglobal_password; ?>">
                    <span><?php _e( 'SMS Global password', 'satosms' ); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scrope="row"><?php _e( 'SMSGlobal Sender Id', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[smsglobal_sender_no]" id="satosms_gateway[smsglobal_sender_no]" value="<?php echo $smsglobal_sender_no; ?>">
                    <span><?php _e( 'Sender ID that the message will appear from. Eg: 61409317436  (Do not use +before the country code)', 'satosms' ) ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="hoiio_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $hoiio_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'App ID', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[hoiio_appid]" id="satosms_gateway[hoiio_appid]" value="<?php echo $hoiio_appid; ?>">
                    <span><?php _e( 'Ender your hoiio App ID', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Access Token', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[hoiio_accesstoken]" id="satosms_gateway[hoiio_accesstoken]" value="<?php echo $hoiio_accesstoken; ?>">
                    <span><?php _e( 'Enter your hoiio Access Token', 'satosms' ); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="intellisms_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $intellisms_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Intellisms Username', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[intellisms_username]" id="satosms_gateway[intellisms_username]" value="<?php echo $intellisms_username; ?>">
                    <span><?php _e( 'Enter your Intellisms Username', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Intellisms Password', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[intellisms_password]" id="satosms_gateway[intellisms_password]" value="<?php echo $intellisms_password; ?>">
                    <span><?php _e( 'Enter your Intellisms password', 'satosms' ); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scrope="row"><?php _e( 'Intellisms Sender Id', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[intellisms_sender_no]" id="satosms_gateway[intellisms_sender_no]" value="<?php echo $intellisms_sender_no; ?>">
                    <span><?php _e( 'Sender ID that the message will appear from. Eg: COMPANY or 9339339  (Do not use +before the country code)', 'satosms' ) ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="infobip_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $infobip_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Infobip Username', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[infobip_username]" id="satosms_gateway[infobip_username]" value="<?php echo $infobip_username; ?>">
                    <span><?php _e( 'Enter your Infobip Username', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Infobip Password', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[infobip_password]" id="satosms_gateway[infobip_password]" value="<?php echo $infobip_password; ?>">
                    <span><?php _e( 'Enter your Infobip password', 'satosms' ); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scrope="row"><?php _e( 'Infobip Sender Id', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[infobip_sender_no]" id="satosms_gateway[infobip_sender_no]" value="<?php echo $infobip_sender_no; ?>">
                    <span><?php _e( 'Sender ID that the message will appear from. Eg: COMPANY or 9339339  (Do not use +before the country code)', 'satosms' ) ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="atompark_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $atompark_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Atompark Username', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[atompark_username]" id="satosms_gateway[atompark_username]" value="<?php echo $atompark_username; ?>">
                    <span><?php _e( 'Enter your AtomPark Username', 'satosms' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scrope="row"><?php _e( 'Atompark Password', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[atompark_password]" id="satosms_gateway[atompark_password]" value="<?php echo $atompark_password; ?>">
                    <span><?php _e( 'Enter your Atompark password', 'satosms' ); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scrope="row"><?php _e( 'Atompark Sender Id', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[atompark_sender_no]" id="satosms_gateway[atompark_sender_no]" value="<?php echo $atompark_sender_no; ?>">
                    <span><?php _e( 'Max 11 Latin letters or numbers. Only Latin characters please, Unicode is not allowed.', 'satosms' ) ?></span>
                </td>
            </tr>
        </table>
    </div>
<div class="smsidindia_wrapper hide_class">
        <hr>
        <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
            <strong><?php _e( $smsidindia_helper, 'satosms' ); ?></strong>
       </p>
        <table class="form-table">
            <tr valign="top">
                <th scrope="row"><?php _e( 'Your authentication key', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[smsidindia_username]" id="satosms_gateway[smsidindia_username]" value="<?php echo $smsidindia_username; ?>">
                    <span><?php _e( 'Enter your Sms Idindia authentication key', 'satosms' ); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scrope="row"><?php _e( 'Sender ID', 'satosms' ) ?></th>
                <td>
                    <input type="text" name="satosms_gateway[smsidindia_password]" id="satosms_gateway[smsidindia_password]" value="<?php echo $smsidindia_password; ?>">
                    <span><?php _e( 'Sender ID,While using route4 sender id should be 6 characters long', 'satosms' ); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <?php do_action( 'satosms_gateway_settings_options_after' ) ?>
    <?php
}

// hook for Settings API for adding extra sections
add_action( 'wsa_form_bottom_satosms_gateway', 'satosms_settings_field_gateway' );

function satosms_settings_field_message_diff_status() {
    $enabled_order_status = satosms_get_option( 'order_status', 'satosms_general', array() );
    ?>
    <div class="satosms_different_message_status_wrapper satosms_hide_class">
        <hr>
        <?php if ( $enabled_order_status  ): ?>
            <h3>Set your sms content for Buyer</h3>
            <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
                <strong><?php _e( 'Set your sms content according to your enabled order status in General Settings', 'satosms' ); ?></strong><br>
                <span><?php _e( 'Write your custom message. When an order is create then you get this type of format message. For order id just insert <code>[order_id]</code>. Similarly <code>[order_items]</code>, <code>[order_amount]</code>, <code>[billing_firstname]</code>, <code>[billing_lastname]</code>, <code>[billing_email]</code>, <code>[billing_address1]</code>, <code>[billing_address2]</code>, <code>[billing_country]</code>, <code>[billing_city]</code>, <code>[billing_state]</code>, <code>[billing_postcode]</code>, <code>[shipping_address1]</code>, <code>[shipping_country]</code>, <code>[shipping_city]</code>, <code>[shipping_state]</code>, <code>[shipping_postcode]</code>, <code>[payment_method]</code>,<code>[provider]</code>,<code>[tracking_number]</code>,<code>[date_shipped]</code>,', 'satosms' ); ?></span>
            </p>
            <table class="form-table">
                <?php foreach ( $enabled_order_status as $buyer_status_key => $buyer_status_value ): ?>
                    <?php
                        $buyer_display_order_status = str_replace( 'wc-', '', $buyer_status_key );
                        $buyer_content_value = satosms_get_option( 'buyer-'.$buyer_status_key, 'satosms_message_diff_status', '' );
                    ?>
                    <tr valign="top">
                        <th scrope="row"><?php echo sprintf( '%s %s', ucfirst( str_replace( '-', ' ', $buyer_display_order_status ) ) , __( 'Order Status', 'satosms' ) ); ?></th>
                        <td>
                            <textarea class="regular-text" name="satosms_message_diff_status[buyer-<?php echo $buyer_status_key; ?>]" id="satosms_message_diff_status[buyer-<?php echo $buyer_status_key; ?>]" cols="55" rows="5"><?php echo $buyer_content_value; ?></textarea>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

            <hr>

            <h3>Set your sms content for Admin</h3>
            <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-style: italic; font-size: 14px;">
                <strong><?php _e( 'Set sms content according to your enabled order status in General Settings', 'satosms' ); ?></strong><br>
                <span><?php _e( 'Write your custom message. When an order is create then you get this type of format message. For order id just insert <code>[order_id]</code>. Similarly <code>[order_items]</code>, <code>[order_amount]</code>, <code>[billing_firstname]</code>, <code>[billing_lastname]</code>, <code>[billing_email]</code>, <code>[billing_address1]</code>, <code>[billing_address2]</code>, <code>[billing_country]</code>, <code>[billing_city]</code>, <code>[billing_state]</code>, <code>[billing_postcode]</code>, <code>[shipping_address1]</code>, <code>[shipping_country]</code>, <code>[shipping_city]</code>, <code>[shipping_state]</code>, <code>[shipping_postcode]</code>, <code>[payment_method]</code>,<code>[provider]</code>,<code>[tracking_number]</code>,<code>[date_shipped]</code>,', 'satosms' ); ?></span>
            </p>
            <table class="form-table">
                <?php foreach ( $enabled_order_status as $admin_status_key => $admin_status_value ): ?>
                    <?php
                        $admin_display_order_status = str_replace( 'wc-', '', $admin_status_key );
                        $admin_content_value = satosms_get_option( 'admin-'.$admin_status_key, 'satosms_message_diff_status', '' );
                    ?>
                    <tr valign="top">
                        <th scrope="row"><?php echo sprintf( '%s %s', ucfirst( str_replace( '-', ' ', $admin_display_order_status ) ) , __( 'Order Status', 'satosms' ) ); ?></th>
                        <td>
                            <textarea class="regular-text" name="satosms_message_diff_status[admin-<?php echo $admin_status_key; ?>]" id="satosms_message_diff_status[buyer-<?php echo $admin_status_key; ?>]" cols="55" rows="5"><?php echo $admin_content_value; ?></textarea>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

        <?php else: ?>
            <p style="margin-top:15px; margin-bottom:0px; padding-left: 20px; font-size: 14px;"><?php _e( 'Sorry no order status will be selected for sending SMS. Please select some order status from General Settings Tab') ?></p>
        <?php endif ?>
    </div>

    <?php
}

add_action( 'wsa_form_bottom_satosms_message_diff_status', 'satosms_settings_field_message_diff_status' );

