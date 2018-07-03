<?php
/**
 * Checkout terms and conditions checkbox
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$terms_page_id = wc_get_page_id( 'terms' );

if ( $terms_page_id > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) :
	$terms         = get_post( $terms_page_id );
	$terms_content = has_shortcode( $terms->post_content, 'woocommerce_checkout' ) ? '' : wc_format_content( $terms->post_content );

	if ( $terms_content ) {
		do_action( 'woocommerce_checkout_before_terms_and_conditions' );
		echo '<div class="woocommerce-terms-and-conditions" style="display: none; max-height: 200px; overflow: auto;">' . $terms_content . '</div>';
	}
	?>
	<p class="form-row terms wc-terms-and-conditions">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
			<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" /> 
			<svg shape-rendering="optimizeQuality" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<rect class="outer" fill="#BDBCB9" x="0" y="0" width="64" height="64" rx="8"></rect>
				<rect class="inner" fill="#BDBCB9" x="4" y="4" width="56" height="56" rx="4"></rect>
				<polyline class="check" stroke="#FFFFFF" stroke-dasharray="270" stroke-dashoffset="270" stroke-width="8" stroke-linecap="round" fill="none" stroke-linejoin="round" points="16 31.8 27.4782609 43 49 22"></polyline>
			  </svg>
			<span><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" class="woocommerce-terms-and-conditions-link">terms &amp; conditions</a>', 'woocommerce' ), esc_url( wc_get_page_permalink( 'terms' ) ) ); ?></span> <span class="required">*</span>
		</label>
		<input type="hidden" name="terms-field" value="1" />
	</p>
	<?php do_action( 'woocommerce_checkout_after_terms_and_conditions' ); ?>
<?php endif; ?>
