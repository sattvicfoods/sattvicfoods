<?php 

class WOEL_Settings_Integration extends WC_Integration {
    /**
     * Init and hook in the integration.
     */
    public function __construct() {
        global $woocommerce;
        $this->id                 = 'woel-settings-screen';
        $this->method_title       = 'Email Log Settings';
        $this->method_description = $this->method_description();
       
       // Load the settings.
        $this->init_form_fields();
        $this->init_settings();
        
        // Define user set variables.
        $this->woel_main_license_key          = $this->get_option( 'woel_main_license_key' );
        
        // Actions.
        add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );
    }
    
    public function method_description(){
        ob_start();
        ?>
        
        <h2>Go Premium Today!</h2>
            
        <a href="http://raiserweb.com/product/woocommerce-order-email-log-premium-plugin-license/" class="button button-primary" target="_blank">Purchase A License ></a>
                
        <p>I would like to thank you for using this free plugin. Please rate it on our <a href="https://wordpress.org/plugins/order-emails-log-for-woocommerce/reviews/" target="_blank">wordpress plugin page</a></p>

        <p>Why not upgrade to the premium version, for a small one off fee of only <b>&pound;14.99</b>? This fee helps support my work as a web developer in the wordpres community, to help make more great plugins.</p>
        
        <p>The premium plugin includes the following additional features:</p>

        <h4>View Email Content</h4>
        <p>The premium version lets you view the content of all logged emails sent.</p>
        
        <h4>Resend Emails</h4>
        <p>The premium version lets you re-send any of the logged emails. You can also specify any email address, incase the customer wants to use an alternative inbox.</p>
                    
        <p>Any updates to the premium plugin will be available to you at no extra cost.</p>
        
        <h2>Easy to buy</h2>
        
        <p>Purchasing a license key couldn't be easier. Simply click the button below. You will be taken to our website to make the purchase, and receive your unique license key.<p>
        <p>Then simply enter the license key into the licence key input box below, and click 'Save changes'. If the license key is valid, you will be given the option to update this plugin to the premium plugin via the wordpress <a href="<?php echo admin_url('plugins.php');?>" >plugins menu</a>.  </p>
        
        <a href="http://raiserweb.com/product/woocommerce-order-email-log-premium-plugin-license/" class="button button-primary" target="_blank">Purchase A License ></a>
        
            
        <?php
        return ob_get_clean();

        
    }
    
    
    /**
     * Initialize integration settings form fields.
     */
    public function init_form_fields() {
        
        $this->form_fields = array(
            'woel_main_license_key' => array(
                'title'             => 'Premium License Key',
                'type'              => 'text',
                'description'       => 'Enter your License Key.',
                'desc_tip'          => true,
                'default'           => ''
            )
        );
        
    }
    

    
}     


    
  