<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see      https://docs.woocommerce.com/document/template-structure/
 * @author   WooThemes
 * @package  WooCommerce/Templates
 * @version  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form id="order_review" method="post">

	<table class="shop_table">
		<thead>
		<tr>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total">
				<!-- <div class="sfoo_checkout_product_total"> -->
					<?php _e( 'Price', 'woocommerce' ); ?>
				<!-- </div> -->
			</th>
		</tr>
	</thead>
		<tbody>
			<?php if ( sizeof( $order->get_items() ) > 0 ) : ?>
				<?php foreach ( $order->get_items() as $item_id => $item ) : ?>
					<?php
						if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
							continue;
						}
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
						<td class="product-name">
							<div class="sfoo_checkout_product_name">
								<?php 
									// echo $item['name'] . '&nbsp;'; 
									$sfoo_item_id = wc_get_order_item_meta( $item_id, '_product_id', true );
									$sfoo_product_title = get_product($sfoo_item_id)->get_title();
									echo $sfoo_product_title . '&nbsp;';
								?>									
							</div>
							<div class="sfoo_checkout_product_quantity"><?php echo apply_filters( 'woocommerce_order_item_quantity_html', ' <span class="product-quantity">' . sprintf( '&times; %s', esc_html( $item->get_quantity() ) ) . '</span>', $item ); ?></div>
							<div class="sfoo_checkout_product_size">
							<?php
							$pa_size = wc_get_order_item_meta( $item_id, 'pa_size', true );
							$size = wc_get_order_item_meta( $item_id, 'size', true );
							if ($pa_size === 'Free Sample' || $size === 'Free Sample'){
								echo '<div class="sfoo_checkout_product_sample">Free sample</div>';
							} else {
								if (wc_get_order_item_meta( $item_id, 'pa_size', true )) {
										$size = wc_get_order_item_meta( $item_id, 'pa_size', true );
									} else {
										$size = wc_get_order_item_meta( $item_id, 'size', true );
									}
									$size = str_replace('-', ' ', $size);
									echo 'Size: ' . $size;
							}
							?>
							</div>
							<div class="sfoo_checkout_product_packaging">
								<?php 
									$pa_size = wc_get_order_item_meta( $item_id, 'pa_size', true );
										$size = wc_get_order_item_meta( $item_id, 'size', true );
										if ($pa_size === 'Free Sample' || $size === 'Free Sample' || !wc_get_order_item_meta( $item_id, 'packaging', true )){
											//
										} else {
											echo 'Packaging: ' . wc_get_order_item_meta( $item_id, 'packaging', true );
										}
								?>
								
							</div>
						</td>
						<td class="product-total"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<?php if ( $totals = $order->get_order_item_totals() ) : ?>
				<?php foreach ( $totals as $total ) : ?>
					<?php if($total['label']=='Total:'){ ?>
						<tr class="order-total"><td colspan="2"><p><?php echo $total['label']; ?> <?php echo $total['value']; ?></p></td></tr>
					<?php } else { ?>
						<tr class="cart-subtotal"><td class="subt" colspan="2"><?php echo $total['label']; ?> <?php echo $total['value']; ?></td></tr>
					<?php } ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</tfoot>
	</table>

	<div id="payment">
		<?php if ( $order->needs_payment() ) : ?>
			<ul class="wc_payment_methods payment_methods methods">
				<?php
					if ( ! empty( $available_gateways ) ) { ?>
					<div class="labels">
					<script type="text/javascript">
					jQuery(document).ready(function($){
						$('#order_review #payment .wc_payment_methods .labels label input').click(function() {
								$('#order_review #payment .wc_payment_methods .labels label').removeClass('checked');
								$parent_box = $(this).closest('label');
								$parent_box.addClass('checked');
							});
					});
					</script>
					<?php
						foreach ( $available_gateways as $gateway ) {
							wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					} ?>
					</div>
					<div class="descriptons">
					<?php foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method_descr.php', array( 'gateway' => $gateway ) );
					} ?>
					</div> <?php
					} else {
						echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ) . '</li>';
					}
				?>
			</ul>
		<?php endif; ?>
		<div class="form-row">
			<input type="hidden" name="woocommerce_pay" value="1" />

			<?php wc_get_template( 'checkout/terms.php' ); ?>

			<?php do_action( 'woocommerce_pay_order_before_submit' ); ?>

			<?php echo apply_filters( 'woocommerce_pay_order_button_html', '<input type="submit" class="button alt" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

			<?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

			<?php wp_nonce_field( 'woocommerce-pay' ); ?>
		</div>
	</div>
</form>
