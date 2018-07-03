<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** @global WC_Checkout $checkout */

?>
<div class="woocommerce-billing-fields">
	<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3><?php _e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

	<?php else : ?>

		<h3><?php _e( 'Billing Details', 'woocommerce' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>
	
	<div id="checkout_sliced">
	<?php 
		$i=0; $wrap_count = 5;
			foreach ( $checkout->checkout_fields['billing'] as $key => $field ) :
				if ($i == 7) { $wrap_count = 6; }
				$i+=1;
				if($i%$wrap_count==1) : echo '<div class="blocks">';  endif;
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				if($i%$wrap_count==0) {
					 echo '</div>';
				}
			endforeach;
		if($i%$wrap_count!=0) : echo '</div>'; endif; 
	?>
	</div>
	
	<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>

	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

			<h3><?php _e( 'Additional Information', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>

			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
	
	
	<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

		<?php if ( $checkout->enable_guest_checkout ) : ?>

			<p class="form-row form-row-wide create-account">
				<label for="createaccount" class="checkbox">
					<input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> 
					<svg shape-rendering="optimizeQuality" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect class="outer" fill="#BDBCB9" x="0" y="0" width="64" height="64" rx="8"></rect><rect class="inner" fill="#BDBCB9" x="4" y="4" width="56" height="56" rx="4"></rect><polyline class="check" stroke="#FFFFFF" stroke-dasharray="270" stroke-dashoffset="270" stroke-width="8" stroke-linecap="round" fill="none" stroke-linejoin="round" points="16 31.8 27.4782609 43 49 22"></polyline></svg>
					<?php _e( 'Create account', 'woocommerce' ); ?>
				</label>
			</p>

		<?php endif; ?>
		
		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

			<div class="create-account">

				<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'woocommerce' ); ?></p>

				<?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

				<?php endforeach; ?>

				<div class="clear"></div>

			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

	<?php endif; ?>
</div>
<script>
var d1 = document.getElementById('billing_phone');
d1.insertAdjacentHTML('afterend', '<label class="checkbox " id="sms"><input type="checkbox" class="input-checkbox " name="buyer_sms_notify" id="buyer_sms_notify" value="1" checked="checked"><svg shape-rendering="optimizeQuality" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect class="outer" fill="#BDBCB9" x="0" y="0" width="64" height="64" rx="8"></rect><rect class="inner" fill="#BDBCB9" x="4" y="4" width="56" height="56" rx="4"></rect><polyline class="check" stroke="#FFFFFF" stroke-dasharray="270" stroke-dashoffset="270" stroke-width="8" stroke-linecap="round" fill="none" stroke-linejoin="round" points="16 31.8 27.4782609 43 49 22"></polyline></svg> I would like to receive <span>transactional</span> SMS notification.<font>No promotional sms will be sent, only dispatch/delivery confirmation</font></label>');
</script>