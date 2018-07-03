<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shipment Tracking
 *
 * Shows tracking information in the HTML order email
 *
 * @author  WooThemes
 * @package WooCommerce Shipment Tracking/templates/email
 * @version 1.6.4
 */

if ( $tracking_items ) : ?>
	<h2><?php echo apply_filters( 'woocommerce_shipment_tracking_my_orders_title', __( 'Tracking Information', 'woocommerce-shipment-tracking' ) ); ?></h2>

	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%;" border="1">

		<thead>
			<tr>
				<th class="tracking-provider order_product" scope="col" class="td" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #fff;"><?php _e( 'Provider', 'woocommerce-shipment-tracking' ); ?></th>
				<th class="tracking-number" scope="col" class="td" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #fff;"><?php _e( 'Tracking Number', 'woocommerce-shipment-tracking' ); ?></th>
				<th class="date-shipped" scope="col" colspan="2" class="td" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #fff; width:50%;"><?php _e( 'Date', 'woocommerce-shipment-tracking' ); ?></th>
			</tr>
		</thead>

		<tbody><?php
		foreach ( $tracking_items as $tracking_item ) {
				?><tr class="tracking">
					<td class="tracking-provider order_product" data-title="<?php _e( 'Provider', 'woocommerce-shipment-tracking' ); ?>" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373;">
						<?php echo esc_html( $tracking_item['formatted_tracking_provider'] ); ?>
					</td>
					<td class="tracking-number" data-title="<?php _e( 'Tracking Number', 'woocommerce-shipment-tracking' ); ?>" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373;">
						<?php echo esc_html( $tracking_item['tracking_number'] ); ?>
					</td>
					<td class="date-shipped" data-title="<?php _e( 'Status', 'woocommerce-shipment-tracking' ); ?>" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373;">
						<time datetime="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>" title="<?php echo date( 'Y-m-d', $tracking_item['date_shipped'] ); ?>"><?php echo date_i18n( get_option( 'date_format' ), $tracking_item['date_shipped'] ); ?></time>
					</td>
					<td class="order-actions" style="text-align: center; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373;">
							<a href="<?php echo esc_url( $tracking_item['formatted_tracking_link'] ); ?>" target="_blank"><?php _e( 'Track', 'woocommerce-shipment-tracking' ); ?></a>
					</td>
				</tr><?php
		}
		?></tbody>
	</table>

<?php
endif;
