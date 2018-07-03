<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
<table style="width: 100%; border: none; padding: 0;">
	<tr>
		<td style="padding: 0px;">
					
<?php if ( ! $sent_to_admin ) : ?>
	<h2 style="font-size: 18px;"><?php printf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?></h2>
<?php else : ?>
	<h2><a class="link" href="<?php echo esc_url( admin_url( 'post.php?post=' . $order->id . '&action=edit' ) ); ?>"><?php printf( __( 'Order #%s', 'woocommerce'), $order->get_order_number() ); ?></a> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>
<?php endif; ?>

		</td>

		<td style="padding:9px 0px 0px; text-align: right; font-size: 14px; color: #737373">

<?php 
	$ship_phone = get_post_meta( $order->id, '_shipping_phone', true ) ? get_post_meta( $order->id, '_shipping_phone', true ) : get_post_meta( $order->id, '_billing_phone', true );
    echo '<span style="font-weight:600; color:#4c5568;">' . __( 'Shipping phone' ) . ':</span> ' . $ship_phone;
?>

		</td>
	</tr>
</table>

<?php 
$days_from_free = date_i18n( 'F j', strtotime( $order->order_date . "+ 5 day" ) );
$days_to_free = date_i18n( 'F j', strtotime( $order->order_date . "+ 12 day" ) );
$days_from_main = date_i18n( 'F j', strtotime( $order->order_date . "+ 2 day" ) );
$days_to_main = date_i18n( 'F j', strtotime( $order->order_date . "+ 5 day" ) );
$days_from_int = date_i18n( 'F j', strtotime( $order->order_date . "+ 10 day" ) );
$days_to_int = date_i18n( 'F j', strtotime( $order->order_date . "+ 30 day" ) );
$shipping_items = $order->get_items( 'shipping' );
	foreach($shipping_items as $el){
		$shipping_method_id = $el['method_id'] ;
		        if ($shipping_method_id == 'free_shipping:1') {
			   echo '<p style="margin:5px 0;">Estimated delivery: <span style="font-weight:bold;">'. $days_from_free .' - '. $days_to_free .'</span></p>';
			}
			if ($shipping_method_id == 'main') {
			   echo '<p style="margin:5px 0;">Estimated delivery: <span style="font-weight:bold;">'. $days_from_main .' - '. $days_to_main .'</span></p>';
			}
			if ($shipping_method_id == '1466337967') {
			   echo '<p style="margin:5px 0;">Estimated delivery: <span style="font-weight:bold;">'. $days_from_int .' - '. $days_to_int .'</span></p>';
			}
	}
?>
<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left; width:50%;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="td" scope="col" style="text-align:left; width:73px;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( array(
			'show_sku'      => $sent_to_admin,
			'show_image'    => false,
			'image_size'    => array( 32, 32 ),
			'plain_text'    => $plain_text,
			'sent_to_admin' => $sent_to_admin
		) ); ?>

		<?php if ( $totals = $order->get_order_item_totals() ) { ?>

		<tr>
			<td class="td" scope="row" colspan="2" style="text-align:left;"><?php echo $totals['cart_subtotal']['label']; ?></td>
			<td class="td" style="text-align:left;"><?php echo $totals['cart_subtotal']['value']; ?></td>
		</tr>
		<tr>
			<td class="td" scope="row" colspan="2" style="text-align:left;"><?php echo $totals['shipping']['label']; ?></td>
			<td class="td" style="text-align:left;"><?php echo $totals['shipping']['value']; ?></td>
		</tr>
		<tr>
			<td class="td" scope="row" colspan="2" style="text-align:left;"><?php echo $totals['payment_method']['label']; ?></td>
			<td class="td" style="text-align:left;"><?php echo $totals['payment_method']['value']; ?></td>
		</tr>

		<?php } ?>

	</tbody>
	<tfoot>
		<?php 

		if ( $totals ) { 
			$exploded_totals = explode('(', strip_tags($totals['order_total']['value']) );
			$total_price = 'TOTAL: ' . trim($exploded_totals[0]);
			$total_includes = trim($exploded_totals[1]); 
			$total_includes = ucfirst( substr($total_includes, 0, -1) );

		?>
						
		<tr>
			<td class="td topline" scope="row" colspan="2" style="text-align:right; padding:10px; color:#6bc194;"><?php echo $total_includes; ?></td>
			<td class="td topline" style="text-align:center; padding:10px; word-wrap:break-word; color:#fff; background-color:#6bc194;"><?php echo $total_price; ?></td>
		</tr>

		<?php } ?>
	</tfoot>
</table>

<?php 
	do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); 
?>

<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
<table class="td customer_details" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td order_product" scope="col" style="width:50%;text-align:left;"><?php _e( 'Email', 'woocommerce' ); ?></th>
			<th class="td" scope="col" style="width:50%;text-align:left;"><?php _e( 'Telephone', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<?php
				$customer_email = strip_tags( get_post_meta( $order->id, '_billing_email', true ) );
			?>
			<td class="td order_product" style="text-align:left;"><?php echo '<a href="mailto:' . $customer_email . '" style="color:#3897d6; text-decoration:none;">' . $customer_email . '</a>'; ?></td>
			<td class="td" style="text-align:left;"><?php echo get_post_meta( $order->id, '_billing_phone', true ); ?></td>
		</tr>
	</tbody>
</table>
