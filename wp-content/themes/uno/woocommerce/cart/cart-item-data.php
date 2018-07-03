<?php
/**
 * Cart item data (when outputting non-flat)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-item-data.php.
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
 * @version 	2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('.woocommerce-cart  table.cart tbody tr.cart_item td.product-size dl dd.variation-Size p').each(function () {
		var t = $(this).html();
		$(this).html(t.replace(' (', '<br/><span>(') + '</span>');
	});
});
</script>
<dl class="variation">
	<?php foreach ( $item_data as $data ) : ?>
		<dt class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?>:</dt>
		<dd class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( wpautop( $data['display'] ) ); ?></dd><br class="<?php echo sanitize_html_class( $data['key'] ); ?>">
	<?php endforeach; ?>
</dl>
