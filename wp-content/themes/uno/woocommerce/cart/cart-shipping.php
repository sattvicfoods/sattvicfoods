<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="shipping shipping_methods_new">
	<td data-title="<?php echo esc_attr( $package_name ); ?>" id="shipping_content" colspan="2">
		<div id="label_shipping"><?php echo wp_kses_post( $package_name ); ?></div>
		<?php if ( 1 < count( $available_methods ) ) : ?>
			<ul id="shipping_method">
				<?php foreach ( $available_methods as $method ) : ?>
					<li>
						<?php
							printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />
								<label for="shipping_method_%1$d_%2$s">
								<span class="radio_input"><svg shape-rendering="optimizeQuality" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
									<circle class="outer" fill="#BDBDBD" cx="32" cy="32" r="32"></circle>
									<circle class="inner" fill="#FFFFFF" cx="32" cy="32" r="28"></circle>
								</svg></span>
								<span class="radio_label">%5$s</span></label>',
								$index, sanitize_title( $method->id ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ), wc_cart_totals_shipping_method_label( $method ) );

							do_action( 'woocommerce_after_shipping_rate', $method, $index );
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php elseif ( 1 === count( $available_methods ) ) :  ?>
			<div class="sfoo_single_shipping_method">
			<?php
				$method = current( $available_methods );
				printf( '%3$s <input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d" value="%2$s" class="shipping_method" />', $index, esc_attr( $method->id ), wc_cart_totals_shipping_method_label( $method ) );
				do_action( 'woocommerce_after_shipping_rate', $method, $index );
			?>
			</div>
		<?php elseif ( ! WC()->customer->has_calculated_shipping() ) : ?>
			<?php echo wpautop( __( 'Shipping costs will be calculated once you have provided your address.', 'woocommerce' ) ); ?>
		<?php else : ?>
			<?php echo apply_filters( is_cart() ? 'woocommerce_cart_no_shipping_available_html' : 'woocommerce_no_shipping_available_html', wpautop( __( 'There are no shipping methods available. Please double check your address, or contact us if you need any help.', 'woocommerce' ) ) ); ?>
		<?php endif; ?>

		<?php if ( $show_package_details ) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
		<?php endif; ?>

		<?php if ( ! empty( $show_shipping_calculator ) ) : ?>
			<?php woocommerce_shipping_calculator(); ?>
		<?php endif; ?>
	</td>
</tr>