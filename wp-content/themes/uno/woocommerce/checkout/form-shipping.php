<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
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
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="woocommerce-shipping-fields">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

		<h3 id="ship-to-different-address"><i class="fa fa-caret-right" aria-hidden="true"></i>
			<label for="ship-to-different-address-checkbox" class="checkbox"><?php _e( 'Ship to a different address?', 'woocommerce' ); ?></label>
			<input id="ship-to-different-address-checkbox" class="input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
		</h3>

		<div class="shipping_address">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
			<?php $i=0; $wrap_count = 4; ?>
			<?php foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) : ?>
				<?php if ($i == 6) { $wrap_count = 5; } ?>
				<?php  $i+=1; if($i%$wrap_count==1) : echo '<div class="blocks_shipping">';  endif; ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php if($i%$wrap_count==0) : echo '</div>'; endif; ?>
			<?php endforeach; ?>
				<?php if($i%$wrap_count!=0) : echo '</div>'; endif; ?>
			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>

	
</div>
<script>
/*var d1 = document.getElementById('shipping_phone');
d1.insertAdjacentHTML('afterend', '<label class="checkbox " id="sms"><input type="checkbox" class="input-checkbox " name="buyer_sms_notify" id="buyer_sms_notify" value="1" checked="checked"><svg shape-rendering="optimizeQuality" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect class="outer" fill="#BDBCB9" x="0" y="0" width="64" height="64" rx="8"></rect><rect class="inner" fill="#BDBCB9" x="4" y="4" width="56" height="56" rx="4"></rect><polyline class="check" stroke="#FFFFFF" stroke-dasharray="270" stroke-dashoffset="270" stroke-width="8" stroke-linecap="round" fill="none" stroke-linejoin="round" points="16 31.8 27.4782609 43 49 22"></polyline></svg> I would like to receive <span>transactional</span> SMS notification.<font>No promotional sms will be sent, only dispatch/delivery confirmation</font></label>');*/
</script>