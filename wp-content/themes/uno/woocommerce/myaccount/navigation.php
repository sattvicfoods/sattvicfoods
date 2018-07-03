<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="<?php echo $label; ?>">
				<?php if ($label == 'Dashboard') { ?>
					<i class="fa fa-align-left" aria-hidden="true"></i><span>Orders</span>	
				<?php } ?>
				<?php if ($label == 'Addresses') { ?>
					<i class="fa fa-map-marker" aria-hidden="true"></i><?php echo esc_html( $label ); ?>	
				<?php } ?>
				<?php if ($label == 'Account details') { ?>
					<i class="fa fa-user" aria-hidden="true"></i><?php echo esc_html( $label ); ?>
				<?php } ?>
				<?php if ($label == 'Wishlists') { ?>
					<i class="fa fa-heart" aria-hidden="true"></i><span>My Wishlist</span>
				<?php } ?>
				<?php if ($label == 'Payment details') { ?>
					<i class="fa fa-bank" aria-hidden="true"></i><?php echo esc_html( $label ); ?>
				<?php } ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php $woo_ma_home=home_url(); ?>
	<a href="<?php echo wp_logout_url($woo_ma_home); ?>" class="logout">Log Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
