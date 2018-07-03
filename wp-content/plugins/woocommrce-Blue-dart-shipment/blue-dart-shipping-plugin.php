<?php
/*
Plugin Name: BLue Dart shipment Extension for woocommerce
Description: BLue Dart shipment Extension for woocommerce
Author: Softprodigy System Solutions (P) Ltd.
Author URI: http://www.softprodigy.com
Version: 1.0.0

	Copyright: © 2014 Softprodigy System Solutions (P) Ltd. (email : experts@softprodigy.com)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Check if WooCommerce is active
 */
global $woocommerce, $product, $woocommerce_loop,$wpdb;
global $post;

if ( !function_exists( 'WC' ) ) {
        function sp_bluedart_install_woocommerce_admin_notice() {
            ?>
            <div class="error">
                <p><?php echo  "Bluedart  plugin is enabled but not effective. It requires WooCommerce in order to work."; ?></p>
        </div>
        <?php
        } 
        add_action( 'admin_notices', 'sp_bluedart_install_woocommerce_admin_notice' );
        return;
}else{
    function sp_bluedart_createTable(){
            global $wpdb;
            if($wpdb->get_var("show tables like ".$wpdb->prefix."orders_manifests") != ''.$wpdb->prefix.'orders_manifests'){
                $sql = "CREATE TABLE ".$wpdb->prefix."orders_manifests(
                id int(99) NOT NULL AUTO_INCREMENT,
                order_id bigint(255) NOT NULL,
                awb_no  bigint(255)  NOT NULL,
                customer_name   varchar(255)     NOT NULL,
                shipping_address varchar(255) NOT NULL,
                pin_code    varchar(255) NOT NULL,
                items   varchar(255) NOT NULL,
                weight  varchar(240) NOT NULL,
                declared_value  varchar(240) NOT NULL,
                collectable     varchar(240) NOT NULL,
                mode    varchar(240) NOT NULL,
                destination VARCHAR( 255 ) NOT NULL,
                created_at  datetime NOT NULL,
                UNIQUE KEY id (id)
                );";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
    }
    register_activation_hook(__FILE__,'sp_bluedart_createTable');
    
}

add_action( 'woocommerce_after_add_to_cart_button','cod_single_product_page' );
function cod_single_product_page() {
    
    global $product;
    $single_product_price=$product->get_price(); 
?>
<div class="cod-view">
    <div style="clear: both;padding-bottom: 10px;padding-top: 13px;">Check COD Availability</div>
    <input type="text" placeholder="Enter your pincode" id="pincodevalue" value="" name="pincode" style="margin-bottom: 18px;"><br/>
    <input type="hidden" id="single_product_price" value="<?php echo $single_product_price; ?>" name="price_single_product" style="margin-bottom: 18px;"><br/>
    <a href="javascript:void(0);" id="cod_check" style="text-decoration:none; background:#EEEEEE; padding:10px; border:1px solid #ccc;" >Check</a>
    <div style="display:none;width: 20px;" id="loader">
        <img src="<?php echo plugin_dir_url(__FILE__);?>/images/opc-ajax-loader.gif">
    </div>
    <div style="padding-top:3px;" id="pinresult"></div>
</div>
<?php   }

/*
 * Adding Plugin Css
 * */
add_action('wp_enqueue_scripts', 'css_styles');
function css_styles() {  
    wp_register_style('bluedart_global_style', plugins_url('css/global.css', __FILE__));  
    wp_enqueue_style('bluedart_global_style');     
    
}
add_action('wp_ajax_get_remote_content_admin', 'get_remote_content_admin');
function get_remote_content_admin(){
	include_once( plugin_dir_path(__FILE__).'awb_no_generation.php' );
    
}

add_action('wp_ajax_get_remote_content', 'get_remote_content');
add_action('wp_ajax_nopriv_get_remote_content', 'get_remote_content');
function get_remote_content(){
    
    $postal_code = trim($_POST['pin']);
    include_once( plugin_dir_path(__FILE__).'DebugSoapClient.php' );
    
    $Bluedart_information_licence_key= unserialize(get_option('Bluedart_information_licence_key'));
    $Bluedart_information_loginid= unserialize(get_option('Bluedart_information_loginid'));
    

    
    $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/Demo/ShippingAPI/Finder/ServiceFinderQuery.svc';

    $soap = new DebugSoapClient( $ApiUrl.'?wsdl', array(
                                                       'trace'         => 1,  
                                                       'style'         => SOAP_DOCUMENT,
                                                       'use'           => SOAP_LITERAL,
                                                       'soap_version'  => SOAP_1_2
                                                    )
                                );

    $soap->__setLocation($ApiUrl);
    $soap->sendRequest = true;
    $soap->printRequest = false;
    $soap->formatXML = true;
    $actionHeader = new SoapHeader(
                        'http://www.w3.org/2005/08/addressing','Action',
                        'http://tempuri.org/IServiceFinderQuery/GetServicesforPincode',true );
    $soap->__setSoapHeaders($actionHeader);
    $params = array( 
                    'pinCode' => $postal_code,
                    'profile' => array( 
                                    'Api_type'      => 'S',
                                    'Area'          => '',
                                    'Customercode'  => '',
                                    'IsAdmin'       => '',
                                    'LicenceKey'    => $Bluedart_information_licence_key,
                                    'LoginID'       => $Bluedart_information_loginid,
                                    'Password'      => '',
                                    'Version'       => '1.3'
                                )
                );
    $result = $soap->__soapCall('GetServicesforPincode',array($params));
    $response['is_error'] = $result->GetServicesforPincodeResult->ErrorMessage;
    $response['place'] = $result->GetServicesforPincodeResult->PincodeDescription;
    $response['cod_in'] = $result->GetServicesforPincodeResult->eTailCODAirInbound;
    $response['cod_out'] = $result->GetServicesforPincodeResult->eTailCODAirOutbound;
    $response['value_limit'] = $result->GetServicesforPincodeResult->AirValueLimit;
        
    echo json_encode($response);
    exit;
    
}

add_action( 'admin_footer', 'admin_js_css' );
function admin_js_css(){
	wp_register_script('jquery.js',plugin_dir_url(__FILE__).'js/jquery.min.js');
	wp_enqueue_script('jquery.js');
	wp_register_script('jquery.validate.min',plugin_dir_url(__FILE__).'js/jquery.validate.min.js');
	wp_enqueue_script('jquery.validate.min');
	wp_register_script('bluedart_admin_global_js', plugin_dir_url(__FILE__).'js/admin_global.js');  
	$ajax_url = admin_url('admin-ajax.php');
	    wp_localize_script( 'bluedart_admin_global_js', 'ajax_url', array('url'=>$ajax_url ));
	    wp_enqueue_script('bluedart_admin_global_js');
	    wp_register_style('jquery-ui-css',plugin_dir_url(__FILE__).'css/jquery-ui.css'); 
	wp_enqueue_style('jquery-ui-css');
	wp_register_script('jquery-ui', plugin_dir_url(__FILE__).'js/jquery-ui.js');  
	 wp_enqueue_script('jquery-ui');
echo '<div id="load2" style="display:none"><img src="'.plugin_dir_url(__FILE__).'/images/opc-ajax-loader.gif"/></div>';
}

add_action( 'admin_footer', 'css_js_files' );
add_action( 'wp_footer', 'css_js_files' );
function css_js_files(){
    wp_register_style('bluedart_global_css',plugin_dir_url(__FILE__).'css/global.css'); 
	wp_enqueue_style('bluedart_global_css');
	
	wp_register_script('jquery.validate.min',plugin_dir_url(__FILE__).'js/jquery.validate.min.js');
    wp_register_script('bluedart_global_js', plugin_dir_url(__FILE__).'js/global.js');  
    $ajax_url = admin_url('admin-ajax.php');
    wp_localize_script( 'bluedart_global_js', 'ajax_url', array('url'=>$ajax_url ));
  
    wp_enqueue_script('jquery.validate.min');
    wp_enqueue_script('bluedart_global_js');  
}

//do_action( 'woocommerce_admin_order_data_after_order_details', $order ); 
add_action( 'woocommerce_admin_order_data_after_order_details', 'action_woocommerce_admin_order_actions_start',10,1);
function action_woocommerce_admin_order_actions_start($order){
	$version = '2.1';
    $order_id = defined( 'WC_VERSION' ) && version_compare( WC_VERSION, $version, '>=' ) ? $order->get_id() : $order->id;
    $field_awb_no="order_".$order_id."_awbno";
    $awb_no=get_post_meta($order_id,'awb_no',true);
    $pdf_link= get_post_meta($order_id,'pdf_link',true);

    if(isset($awb_no) && $awb_no !=""){
        
	$pdf_link=plugin_dir_url(__FILE__).'pdf_invoice_bluedart/order_'.$order_id.'.pdf';
	
?>
	<div id="bluedart_box" style="marign-top:30px;">
		   <label><strong>AWB NO.</strong><?=$awb_no;?></label>

            <a href="<?php echo $pdf_link; ?>" target="_blank" />
            <input type="button" id="" name="download_bluedart_shipment" value="DownLoad Bluedart Shipment pdf" style="margin-top: 27px;"></a>
	
        </div>
	<br/>
	
<?php 
	 }else{
        
?>
		<br/>
        <div id="bluedart_box">
			
            <span><b>Packing Box Dimensions (In cm)</b></span><br/>
            <span id="error" style="color:red"></span><br/>
            <label>Length</label><input type="text" id="length" name="length">
            <label>breadth</label><input type="text" id="breadth" name="breadth">
            <label>height</label><input type="text" id="height" name="height">
            <label>weight (in kgs) </label><input type="text" id="weight" name="weight">
            <input type="button" id="shipment_button" name="send_bluedart_shipment" value="send bluedart shipment" />
            <div style="display:none;width: 20px;" id="loader">
              <img src="<?php echo plugin_dir_url(__FILE__);?>/images/opc-ajax-loader.gif">
              </div>
        </div>
<?php 
 } 
    
?>		 <input type="hidden" id ="order_id" name="order_id" value="<?php echo $order_id; ?>" data-action="awb_no"/>	
        <span id="awb_no"></span>

<?php
}
/*
 * Adding Bluedart menu in admin section
 * */
 
class Bluedart_Options_Page {
    
    function __construct() {
	
        add_action( 'admin_menu', array( $this, 'Blue_Dart_Shipment_menu' ) );
        
    }
	function Blue_Dart_Shipment_menu() {
	
        add_menu_page( 'Blue Dart Shipment Configuaraion page','BlueDart Settings','manage_options',
			'Blue-Dart-Shipment', array( $this, 'Blue_Dart_Shipment_Settings_Page' ) );
	 }    
	
    /*
     * Page shown in admin section When click on Bluedart menu start
     */
	function Blue_Dart_Shipment_Settings_Page() {
		 require_once( plugin_dir_path(__FILE__).'admin_settings.php' );
		
    }
}
new Bluedart_Options_Page;?>
