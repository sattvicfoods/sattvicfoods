<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>

<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TTB2WVQ');</script>
<!-- End Google Tag Manager -->
<?php wp_head(); ?>
<?php woo_head(); ?>

</head>
<body <?php body_class(); ?>>

<?php woo_top(); ?>
<div id="wrapper" <?php if ( !is_user_logged_in() ) { echo 'class="logged_out"';} ?>>

	<div id="inner-wrapper">

	<?php woo_header_before(); ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="only_mobile" title="Home"><img alt="Sattvic Foods" src="/wp-content/themes/uno/images/mobile_logo.svg" /></a>

	<header id="header" class="col-full">

	

		<?php woo_header_inside(); ?>
		
		
		<!-- Usario for sattvicfoods.in --><script async src="//cdn.usario.com/client.min.js?uuid=37c92590-f790-11e7-a435-09651d1191ac" id="usario_tracking"></script>
	</header>

	<?php woo_header_after(); ?>
