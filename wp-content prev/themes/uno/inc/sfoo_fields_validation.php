<?php

/* Password validation on registration page */
function sfoo_validate_register_password ( $errors ) {
	global $woocommerce;
	extract( $_POST );
	if( ( isset($password) && (strlen($password) < 5 || strlen($password) > 12) ) || ( isset($account_password) && (strlen($account_password) < 5 || strlen($account_password) > 12) )  ) {
		return new WP_Error( 'registration-error', __('Password should be 5-12 characters' , 'woocommerce' ) );
	}
	return $errors;
}
add_filter('woocommerce_registration_errors', 'sfoo_validate_register_password');

/* Phone and Zip fields validation*/
// function sfoo_validate_customer_fields () {
// 	// Billing ZIP
// 	if ( isset($_POST['billing_postcode']) ){
// 		$billing_postcode = $_POST['billing_postcode'];
		
// 		if ( preg_match("/[^0-9]/", $billing_postcode) ) {
// 			wc_add_notice( __('<strong>Billing Postcode / ZIP</strong> field should contain only numbers.'), 'error' );
// 		}
// 	}

// 	// Shipping ZIP
// 	if ( isset($_POST['shipping_postcode']) ) {
// 		$shipping_postcode = $_POST['shipping_postcode'];

// 		if ( preg_match("/[^0-9]/", $shipping_postcode) ) {
// 			wc_add_notice( __('<strong>Shipping Postcode / ZIP </strong>field should contain only numbers.'), 'error' );
// 		}
// 	}

// 	// Shipping phone
// 	// if ( isset($_POST['shipping_phone']) ) {
// 	// 	$shipping_phone = $_POST['shipping_phone'];

// 	// 	if ($shipping_phone != '') {

// 	// 		if (strlen($shipping_phone) < 5) {
// 	// 			wc_add_notice( __('<strong>Shipping Phone Number</strong> must contain at least 5 characters. Try, please, again.'), 'error' );
// 	// 		}

// 	// 		if (preg_match("/[^0-9+]/", $shipping_phone)) {
// 	// 			wc_add_notice( __('<strong>Shipping Phone Number</strong> is not a valid phone number'), 'error' );
// 	// 		}

// 	// 	}
// 	// }
	

// }
// add_action('woocommerce_checkout_process', 'sfoo_validate_customer_fields');


// function sfoo_validate_myaccount_billing_postcode($errors) {

// 	if ( isset($_POST['billing_postcode']) ) {

// 		if( preg_match("/[^0-9]/", $_POST['billing_postcode']) ) {
// 			wc_add_notice( __('<strong>Postcode / ZIP </strong>field should contain only numbers.'), 'error' );
// 		}

// 	}
// 	return $errors;

// }
// add_filter( 'woocommerce_process_myaccount_field_billing_postcode' , 'sfoo_validate_myaccount_billing_postcode' );

// function sfoo_validate_myaccount_billing_phone($errors) {
	
// 	// if ( isset($_POST['billing_phone']) ) {

// 	// 	if( strlen($_POST['billing_phone']) < 5 ) {
// 	// 		wc_add_notice( __('<strong>Phone</strong> must contain at least 5 characters. Try, please, again.'), 'error' );
// 	// 	}

// 	// }
// 	return $errors;
// }

// add_filter( 'woocommerce_process_myaccount_field_billing_phone', 'sfoo_validate_myaccount_billing_phone' );

// function sfoo_validate_myaccount_shipping_postcode($errors) {

// 	if ( isset($_POST['shipping_postcode']) ) {

// 		if( preg_match("/[^0-9]/", $_POST['shipping_postcode']) ) {
// 			wc_add_notice( __('<storng>Postcode / ZIP </strong>field should contain only numbers.'), 'error' );
// 		}

// 	}
// 	return $errors;

// }
// add_filter( 'woocommerce_process_myaccount_field_shipping_postcode', 'sfoo_validate_myaccount_shipping_postcode' );

// function sfoo_validate_myaccount_shipping_phone($errors) {

// 	// if ( isset($_POST['shipping_phone']) ) {

// 	// 	$shipping_phone = $_POST['shipping_phone'];

// 	// 	if ( strlen($shipping_phone) < 5 ) {
// 	// 		wc_add_notice( __('<strong>Phone</strong> must contain at least 5 characters. Try, please, again.'), 'error' );
// 	// 	}

// 	// 	if ( preg_match('/[^0-9+]/', $shipping_phone) ) {
// 	// 		wc_add_notice( __('<strong>Phone</strong> is not a valid phone number'), 'error' );
// 	// 	}

// 	// }
// 	return $errors;

// }