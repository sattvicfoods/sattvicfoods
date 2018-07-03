<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Load colors
$bg              = get_option( 'woocommerce_email_background_color' );
$body            = get_option( 'woocommerce_email_body_background_color' );
$base            = get_option( 'woocommerce_email_base_color' );
$base_text       = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text            = get_option( 'woocommerce_email_text_color' );

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
?>
#wrapper {
	background-color: <?php echo esc_attr( $bg ); ?>;
	margin: 0;
	padding: 23px 0 35px 0;
	-webkit-text-size-adjust: none !important;
	width: 100%;
}

#template_container {
	background-color: <?php echo esc_attr( $body ); ?>;
	border: 1px solid #eaeaea;
}

#template_header {
	background-color: <?php echo esc_attr( $base ); ?>;
	border-radius: 3px 3px 0 0 !important;
	color: <?php echo esc_attr( $base_text ); ?>;
	border-bottom: 0;
	font-weight: bold;
	line-height: 100%;
	vertical-align: middle;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1,
#template_header h1 a {
	color: <?php echo esc_attr( $base_text ); ?>;
}

#template_footer td {
	padding: 0;
	-webkit-border-radius: 6px;
}

#template_footer #credit {
	border:0;
	color: #89959e;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size:12px;
	line-height:125%;
	text-align:center;
	padding: 10px 12px 0px;
}

#template_footer #credit a {
	color: #3897d6;
	text-decoration: none;
}

#body_content {
	background-color: <?php echo esc_attr( $body ); ?>;
}

#body_content table td {
	padding: 20px 30px 23px;
	border: none;
}

#body_content table td td {
	padding: 12px 12px 12px 17px;
	border: none;
}

#body_content table td td.order_product {
	padding-left:24px;
}

#body_content table tfoot td.topline {
	border-top:1px solid #6bc194;
}

#body_content table .tracking td.tracking-number {
	color: #3897d6;
}

#body_content table .tracking td.order-actions a{
	color: #6bc194;
	text-transform: uppercase;
	text-decoration: none;
}

#body_content table td th {
	border: none;
	font-size: 14px;
	font-weight: 400;
	color: #fff;
	text-transform: uppercase;
	background-color: #4c5568;
	padding: 12px 9px 9px 17px;
}

#body_content table td th.order_product {
	padding-left:24px;
}

#body_content td ul.wc-item-meta {
	font-size: small;
	margin: 0;
	padding: 0;
	list-style: none;
}

#body_content td ul.wc-item-meta li {
	margin: 0;
	padding: 0;
}

#body_content td ul.wc-item-meta li p {
	margin: 0;
}

#body_content p {
	margin: 0 0 16px;
}

#body_content p.text {
	margin: 0 0 5px;
}

#body_content a.edit {
	display: block;
	margin-top: 8px;
	text-decoration: none;
	font-size: 14px;
}

#body_content a.edit img {
	margin-right: 7px;
}

#body_content_inner {
	color: <?php echo esc_attr( $text ); ?>;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 14px;
	line-height: 150%;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.td {
	color: <?php echo esc_attr( $text ); ?>;
	border: none;
}

.address {
	padding:12px 12px 0;
	color: <?php echo esc_attr( $text ); ?>;
	border: 1px solid <?php echo esc_attr( $body_darker_10 ); ?>;
}

.text {
	color: <?php echo esc_attr( $text ); ?>;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

.link {
	color: <?php echo esc_attr( $base ); ?>;
}

#header_wrapper {
	padding: 25px 80px;
	display: block;
}

h1 {
	color: <?php echo esc_attr( $base ); ?>;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 26px;
	font-weight: 400;
	line-height: 150%;
	text-transform: uppercase;
	margin: 0;
	text-align: center;
	text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
	-webkit-font-smoothing: antialiased;
}

#header_wrapper h1 {
	color: #fff;
}

h2 {
	color: <?php echo esc_attr( $base ); ?>;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 16px;
	font-weight: 400;
	line-height: 130%;
	text-transform: uppercase;
	margin: 18px 0 9px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
	color: <?php echo esc_attr( $base ); ?>;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 16px;
	font-weight: bold;
	line-height: 130%;
	margin: 16px 0 8px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
	color: <?php echo esc_attr( $base ); ?>;
	font-weight: normal;
	text-decoration: none;
}

img {
	border: none;
	display: inline;
	font-size: 14px;
	font-weight: bold;
	height: auto;
	line-height: 100%;
	outline: none;
	text-decoration: none;
	text-transform: capitalize;
}
<?php
