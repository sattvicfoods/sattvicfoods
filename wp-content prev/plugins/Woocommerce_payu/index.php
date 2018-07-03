<?php
/*
Plugin Name: WooCommerce PayUMoney and Citrus combined
Plugin URI: http://citruspay.com/
Description: Extends WooCommerce with PayUMoney and Citrus pay In-Context Payment.
Version: 2.0
Author: Citruspay
Author URI: http://citruspay.com
Copyright: © 2017 Citruspay
*/

$bd=ABSPATH.'wp-content/plugins/'.dirname( plugin_basename( __FILE__ ) );

add_action('plugins_loaded', 'woocommerce_pumcp_init', 0);

function woocommerce_pumcp_init() {

  if ( !class_exists( 'WC_Payment_Gateway' ) ) return;  
  /**
   * Localisation
   */
  load_plugin_textdomain('wc-pumcp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
  
  if($_GET['msg']!=''){
    add_action('the_content', 'showPumcpMessage');
  }

  function showPumcpMessage($content){
    return '<div class="box '.htmlentities($_GET['type']).'-box">'.htmlentities(urldecode($_GET['msg'])).'</div>'.$content;
  }
  /**
   * Gateway class
   */
  class WC_Pumcp extends WC_Payment_Gateway {
    protected $msg = array();
    public function __construct(){
		global $wpdb;
      // Go wild in here
      $this -> id = 'pumcp';
      $this -> method_title = __('PayUbiz', 'pumcp');
      $this -> icon = WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/images/payulogo.png';
      $this -> has_fields = false;
      $this -> init_form_fields();
      $this -> init_settings();
      $this -> title = ''; //$this -> settings['title'];
      $this -> description = $this -> settings['description'];
      $this -> gateway_module = $this -> settings['gateway_module'];
      $this -> payment_url = $this -> settings['payment_url'];
      $this -> access_key = $this -> settings['access_key'];
      $this -> api_key = $this -> settings['api_key'];
      $this -> redirect_page_id = $this -> settings['redirect_page_id'];
      $this -> liveurl = 'http://www.citruspay.com';
	  $this -> pum_key = $this -> settings['pum_key'];
	  $this -> pum_salt = $this -> settings['pum_salt'];
	  $this -> route_citrus = $this -> settings['route_citrus'];
	  $this -> route_payum	= $this -> settings['route_payum'];
      $this -> msg['message'] = "";
      $this -> msg['class'] = "";
	
		//Decide on Citrus or PayUM
		if(!$this -> pum_key && !$this -> pum_salt) {
				$this -> title = 'CitrusPay';			
		}
		elseif(!$this -> payment_url  && !$this -> access_key && !$this -> api_key) {
				$this -> title = 'PayUMoney';
		}
		else {
			if($this -> route_citrus == 0)
			{
				$this -> title = 'PayUMoney';
			}
			elseif($this -> route_payum == 0)
			{
				$this -> title = 'CitrusPay';	
			}
			else {		
		
				$cper =$wpdb->get_row("SELECT COUNT(*) / T.total * 100 AS percent FROM `wp_comments` as I, (SELECT DISTINCT COUNT(*) AS total FROM `wp_comments` where `comment_type` like 'order_note' and `comment_agent` like 'WooCommerce') AS T WHERE `comment_type` like 'order_note' and `comment_agent` like 'WooCommerce' and `comment_content` like '%CitrusPay'");
				
				$pper = $wpdb->get_row("SELECT COUNT(*) / T.total * 100 AS percent FROM `wp_comments` as I, (SELECT DISTINCT COUNT(*) AS total FROM `wp_comments` where `comment_type` like 'order_note' and `comment_agent` like 'WooCommerce') AS T WHERE `comment_type` like 'order_note' and `comment_agent` like 'WooCommerce' and `comment_content` like '%PayUMoney'");			
				
				if($cper->percent > $this -> route_citrus && $pper->percent <= $this -> route_payum) {
					$this -> title = 'PayUMoney';
				}
				elseif($cper->percent <= $this -> route_citrus && $pper->percent > $this -> route_payum) {
					$this -> title = 'CitrusPay';
				}
				else {
					if($pper->percent >= $cper->percent)
							$this -> title = 'CitrusPay';
						else
							$this -> title = 'PayUMoney';
					}
				}
			}
			
			//Decided
	
		
		
      add_action('init', array(&$this, 'check_pumcp_response'));
      //update for woocommerce >2.0
      add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_pumcp_response' ) );

      add_action('valid-pumcp-request', array(&$this, 'SUCCESS'));
			
      if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
      } else {
        add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
      }
		
      add_action('woocommerce_receipt_pumcp', array(&$this, 'receipt_page'));
      add_action('woocommerce_thankyou_pumcp',array(&$this, 'thankyou_page'));
      
    }
    
    function init_form_fields(){

      $this -> form_fields = array(
        'enabled' => array(
            'title' => __('Enable/Disable', 'pumcp'),
            'type' => 'checkbox',
						'label' => __('Enable PayUbiz .', 'pumcp'),
            'default' => 'no'),
		  'description' => array(
			'title' => __('Description:', 'pumcp'),
			'type' => 'textarea',
			'description' => __('This controls the description which the user sees during checkout.', 'pumcp'),
			'default' => __('Pay securely by Credit or Debit card or net banking through PayUbiz.', 'pumcp')),
          'gateway_module' => array(
            'title' => __('Gateway Mode', 'pumcp'),
            'type' => 'select',
            'options' => array("0"=>"Select","sandbox"=>"Sandbox","production"=>"Production"),
            'description' => __('Mode of gateway subscription.','pumcp')
            ),
		  'pum_key' => array(
            'title' => __('PayUBiz Key', 'pumcp'),
            'type' => 'text',
            'description' =>  __('PayUBiz merchant key.', 'pumcp')
            ),
		  'pum_salt' => array(
            'title' => __('PayUBiz Salt', 'pumcp'),
            'type' => 'text',
            'description' =>  __('PayUBiz merchant salt.', 'pumcp')
            ),
          'redirect_page_id' => array(
            'title' => __('Return Page'),
            'type' => 'select',
            'options' => $this -> get_pages('Select Page'),
            'description' => "URL of success page"
            )
          );
    }
    
    /**
     * Admin Panel Options
     * - Options for bits like 'title' and availability on a country-by-country basis
     **/
    public function admin_options(){
      echo '<h3>'.__('PayUbiz payment', 'pumcp').'</h3>';
      echo '<p>'.__('PayUbiz is the most popular payment gateways for online shopping in India').'</p>';
	  echo '<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>';
      echo '<table class="form-table">';
      $this -> generate_settings_html();
      echo '</table>';
	  echo '<script type="text/javascript">
			function routeen()
			{
				if( $(\'input[name=woocommerce_pumcp_pum_key]\').val()==="" || $(\'input[name=woocommerce_pumcp_pum_salt]\').val()==="" || $(\'input[name=woocommerce_pumcp_payment_url]\').val()==="" || $(\'input[name=woocommerce_pumcp_access_key]\').val()==="" || $(\'input[name=woocommerce_pumcp_api_key]\').val()==="")
			    {		   
		   			$(\'input[name=woocommerce_pumcp_route_citrus]\').attr("readonly", true);
		   			$(\'input[name=woocommerce_pumcp_route_payum]\').attr("readonly", true);
	   			}
	   			else {		
	   	   			$(\'input[name=woocommerce_pumcp_route_citrus]\').removeAttr("readonly");
		   			$(\'input[name=woocommerce_pumcp_route_payum]\').removeAttr("readonly");
			   }
			}

			$(document).ready(function() { routeen(); });
			$(\'#mainform\').change(function() { routeen();  });
 
			$(\'#woocommerce_pumcp_route_payum\').bind(\'change\',function() {
				var val = parseInt(this.value,10);	
				if(val > 100)
				{
					$(\'input[name=woocommerce_pumcp_route_payum]\').val(100);
					$(\'input[name=woocommerce_pumcp_route_citrus]\').val(0);
				}
				else if(val < 0)
				{
					$(\'input[name=woocommerce_pumcp_route_payum]\').val(0);
					$(\'input[name=woocommerce_pumcp_route_citrus]\').val(100);
				}
				else {
					$(\'input[name=woocommerce_pumcp_route_citrus]\').val(Math.abs(100 - val));	
				}	
			});

			$(\'#woocommerce_pumcp_route_citrus\').bind(\'change\',function() {
				var val = parseInt(this.value,10);	
				if(val > 100)
				{
					$(\'input[name=woocommerce_pumcp_route_citrus]\').val(100);
					$(\'input[name=woocommerce_pumcp_route_payum]\').val(0);		
				}
				else if(val < 0)
				{
					$(\'input[name=woocommerce_pumcp_route_citrus]\').val(0);
					$(\'input[name=woocommerce_pumcp_route_payum]\').val(100);
		
				}
				else {	
					$(\'input[name=woocommerce_pumcp_route_payum]\').val(Math.abs(100 - val));	
				}
			});

			</script>';

    }
		
    /**
     *  There are no payment fields for Citrus, but we want to show the description if set.
     **/
    function payment_fields(){
      if($this -> description) echo wpautop(wptexturize($this -> description));
    }
		
    /**
     * Receipt Page
     **/
    function receipt_page($order){
      echo '<p>'.__('Thank you for your order, please click the button below to pay.', 'pumcp').'</p>';
      echo $this -> generate_pumcp_form($order);
    }
    
    /**
     * Process the payment and return the result
     **/   
     function process_payment($order_id){
            $order = new WC_Order($order_id);

            if ( version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
                return array(
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->id,
                        add_query_arg('key', $order->order_key, $order->get_checkout_payment_url(true)))
                );
            }
            else {
                return array(
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->id,
                        add_query_arg('key', $order->order_key, get_permalink(get_option('woocommerce_pay_page_id'))))
                );
            }
        }
    /**
     * Check for valid Citrus server callback
     **/    
    function check_Pumcp_response(){
      
		global $woocommerce;
		
		if (!isset($_GET['pg'])) {
			//invalid response	
			$this -> msg['class'] = 'error';
			$this -> msg['message'] = "Invalid payment gateway response...";
			
			wc_add_notice( $this->msg['message'], $this->msg['class'] );
			
			$redirect_url = add_query_arg( array('msg'=> urlencode($this -> msg['message']), 'type'=>$this -> msg['class']), $redirect_url );

			wp_redirect( $redirect_url );
			exit;
		}
		
		if($_GET['pg'] == 'PayUMoney') {
			$postdata = $_POST;			
			
			if (isset($postdata ['key']) && ($postdata['key'] == $this -> pum_key)) {
				$txnid = $postdata['txnid'];
    	    	$order_id = explode('_', $txnid);
				$order_id = (int)$order_id[0];    //get rid of time part
				
				$amount      		= 	$postdata['amount'];
				$productInfo  		= 	$postdata['productinfo'];
				$firstname    		= 	$postdata['firstname'];
				$email        		=	$postdata['email'];
				
				$keyString 	  		=  	$this -> pum_key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'||||||||||';
				
				$keyArray 	  		= 	explode("|",$keyString);
				$reverseKeyArray 	= 	array_reverse($keyArray);
				$reverseKeyString	=	implode("|",$reverseKeyArray);
				$addedon      		= 	$postdata['addedon'];
				$payment_source 	= 	$postdata['payment_source'];
				$mode  				= 	$postdata['mode'];
				
				$order = new WC_Order($order_id);
				
				if (isset($postdata['status']) && $postdata['status'] == 'success') {
					
					if (isset($_POST["additionalCharges"])) {
						$additionalCharges=$_POST["additionalCharges"];
						$saltString     = $additionalCharges.'|'.$this -> pum_salt.'|'.$postdata['status'].'|'.$reverseKeyString;
					}
					else{
						$saltString     = $this -> pum_salt.'|'.$postdata['status'].'|'.$reverseKeyString;
					}
				
					$sentHashString = strtolower(hash('sha512', $saltString));
				 	$responseHashString=$postdata['hash'];
				
					$this -> msg['class'] = 'error';
					$this -> msg['message'] = "Your Payment was cancelled or has been declined. Please try again.";
					
					if($sentHashString==$responseHashString){											
						$this -> msg['message'] = "Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be shipping your order to you soon. <br/><br/>
						<b>Order Details :</b><br/>
						<ul>
							<li>Order Number: $order_id</li>
							<li>Amount: $amount</li>
							<li>Date: $addedon</li>
							<li>Payment Method: $payment_source | $mode</li>
						</ul>";
						$this -> msg['class'] = 'success';
								
						if($order -> status == 'processing' || $order -> status == 'completed' )
						{
							//do nothing
						}
						else
						{
							//complete the order
							$order -> payment_complete();
							$order -> add_order_note('PayUBiz has processed the payment. Ref Number: '. $txnid);
							$order -> add_order_note($this->msg['message']);
							$order -> add_order_note("Paid by PayUBiz");
							$woocommerce -> cart -> empty_cart();
						}
					}
					else {
						//tampered
						$this->msg['class'] = 'error';
						$this->msg['message'] = "Thank you for shopping with us. However, the payment failed";
						$order -> update_status('failed');
						$order -> add_order_note('Failed');
						$order -> add_order_note($this->msg['message']);						
					}
				} else {
		    		$this -> msg['class'] = 'error';
					$this -> msg['message'] = "Your Payment was cancelled or has been declined. Please try again.";
							//Here you need to put in the routines for a failed
							//transaction such as sending an email to customer
							//setting database status etc etc			
				} 
			}
		
		}
			//manage msessages
		if (function_exists('wc_add_notice')) {
			wc_add_notice( $this->msg['message'], $this->msg['class'] );
		}
		else {
			if($this->msg['class']=='success'){
				$woocommerce->add_message($this->msg['message']);
			}
			else{
				$woocommerce->add_error($this->msg['message']);
			}
			$woocommerce->set_messages();
		}
			
		$redirect_url = ($this -> redirect_page_id=="" || $this -> redirect_page_id==0)?get_site_url() . "/":get_permalink($this -> redirect_page_id);
		//For wooCoomerce 2.0
		//$redirect_url = add_query_arg( array('msg'=> urlencode($this -> msg['message']), 'type'=>$this -> msg['class']), $redirect_url );
		wp_redirect( $redirect_url );
		exit;
			
    }
    
    
    
    /*
     //Removed For WooCommerce 2.0
    function showMessage($content){
         return '<div class="box '.$this -> msg['class'].'-box">'.$this -> msg['message'].'</div>'.$content;
     }*/
    
    /**
     * Generate Citrus button link
     **/    
    public function generate_pumcp_form($order_id){
      
		global $woocommerce;
		$order = new WC_Order($order_id);
	
		$redirect_url = ($this -> redirect_page_id=="" || $this -> redirect_page_id==0)?get_site_url() . "/":get_permalink($this -> redirect_page_id);
      
		//For wooCoomerce 2.0
		$redirect_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );
		$redirect_url = add_query_arg( 'pg',$this -> title, $redirect_url );  //pass gateway selection in response
		// Seconds deleted from txnid, cause it's dfferent from system seconds, can't get result
		//$order_id = $order_id.'_'.date("ymds");
		$order_id = $order_id.'_'.date("ymd");
      
		//do we have a phone number?
		//get currency      
		$address = $order -> billing_address_1;
		if ($order -> billing_address_2 != "")
		$address = $address.' '.$order -> billing_address_2;
      
	  	//decide Citrus or PayU
		
		if($this -> title == 'PayUMoney')
		{
			$action = 'https://secure.payu.in/_payment.php';
			
			if($this->gateway_module == 'sandbox')
				$action = 'https://test.payu.in/_payment.php';
				
			$amount = $order -> order_total;
			$productInfo = "Product Information";
			$firstname = $order -> billing_first_name;
			$lastname = $order -> billing_last_name;
			$zipcode = $order -> billing_postcode;
			$email = $order -> billing_email;
			$phone = $order -> billing_phone;			
        	$state = $order -> billing_state;
        	$city = $order -> billing_city;
        	$country = $order -> billing_country;
			$Pg = 'CC';
			$surl = $redirect_url;
			$furl = $redirect_url;
			$curl = $redirect_url;
			
			$hash=hash('sha512', $this -> pum_key.'|'.$order_id.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||||||||'.$this -> pum_salt); 
			$user_credentials = $this -> pum_key.':'.$email;

	
			$html = "<html><body><form action=\"".$action ."\" method=\"post\" id=\"payu_form\" name=\"payu_form\">
						<input type=\"hidden\" name=\"key\" value=\"". $this -> pum_key. "\" />
						<input type=\"hidden\" name=\"txnid\" value=\"".$order_id."\" />
						<input type=\"hidden\" name=\"amount\" value=\"".$amount."\" />
						<input type=\"hidden\" name=\"productinfo\" value=\"".$productInfo."\" />
						<input type=\"hidden\" name=\"firstname\" value=\"". $firstname."\" />
						<input type=\"hidden\" name=\"Lastname\" value=\"". $lastname."\" />
						<input type=\"hidden\" name=\"Zipcode\" value=\"". $zipcode. "\" />
						<input type=\"hidden\" name=\"email\" value=\"". $email."\" />
						<input type=\"hidden\" name=\"phone\" value=\"".$phone."\" />
						<input type=\"hidden\" name=\"surl\" value=\"". $surl. "\" />
						<input type=\"hidden\" name=\"furl\" value=\"". $furl."\" />
						<input type=\"hidden\" name=\"curl\" value=\"".$curl."\" />
						<input type=\"hidden\" name=\"Hash\" value=\"".$hash."\" />
						<input type=\"hidden\" name=\"address1\" value=\"".$address ."\" />
				        <input type=\"hidden\" name=\"address2\" value=\"\" />
					    <input type=\"hidden\" name=\"city\" value=\"". $city."\" />
				        <input type=\"hidden\" name=\"country\" value=\"".$country."\" />
				        <input type=\"hidden\" name=\"state\" value=\"". $state."\" />
				        <button style='display:none' id='submit_payum_payment_form' name='submit_payum_payment_form'>Pay Now</button>
					</form>
					<script type=\"text/javascript\">document.getElementById(\"payu_form\").submit();</script>
					</body></html>";
					
			return $html;
			
		}//PayUMoney end
    }
    
        
    function get_pages($title = false, $indent = true) {
      $wp_pages = get_pages('sort_column=menu_order');
      $page_list = array();
      if ($title) $page_list[] = $title;
      foreach ($wp_pages as $page) {
        $prefix = '';
        // show indented child pages?
        if ($indent) {
          $has_parent = $page->post_parent;
          while($has_parent) {
            $prefix .=  ' - ';
            $next_page = get_page($has_parent);
            $has_parent = $next_page->post_parent;
          }
        }
        // add to page list array array
        $page_list[$page->ID] = $prefix . $page->post_title;
      }
      return $page_list;
    }

  }
	 	

  /**
   * Add the Gateway to WooCommerce
   **/
  function woocommerce_add_pumcp_gateway($methods) {
    $methods[] = 'WC_Pumcp';
    return $methods;
  }

  add_filter('woocommerce_payment_gateways', 'woocommerce_add_pumcp_gateway' );
}

?>