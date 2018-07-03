<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
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
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2><?php _e( 'Addresses', 'woocommerce' ); ?></h2>
<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0">
	<tr>
		<th class="td order_product" style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">
			<?php _e( 'Billing address', 'woocommerce' ); ?>
		</th>
		<th class="td" style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">
			<?php _e( 'Shipping address', 'woocommerce' ); ?>
		</th>
	</tr>
	<tr>
		<td class="td order_product" style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">

			<?php
				$email_billing_fields = explode( "&lt;br/&gt;", nl2br( htmlspecialchars($order->get_formatted_billing_address()) ) );
				
				foreach ($email_billing_fields as $field_value) { 
					echo '<p class="text">' . $field_value . '</p>';
				}
			?>
			
			<table style="border:none; padding:0; width:100%; margin: 0" >
				<tr>
					<td style="margin:0; padding:0; border:none; vertical-align:top;">
						<a href="<?php echo home_url(); ?>/my-account/edit-address/billing" class="edit">
							<img src="<?php echo home_url(); ?>/wp-content/uploads/2018/01/edit_address_icon.png" alt="">
						</a>
					</td>
					<td style="margin:0; padding:0; border:none">
						<a href="<?php echo home_url(); ?>/my-account/edit-address/billing" class="edit">Edit billing address for future orders only</a>
					</td>
				</tr>
			</table>
		</td>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>
			<td class="td" style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">

				<?php
					$email_shipping_fields = explode( "&lt;br/&gt;", nl2br( htmlspecialchars($shipping) ) );
				
					foreach ($email_shipping_fields as $field_value) { 
						echo '<p class="text">' . $field_value . '</p>';
					}
				?>
				
				<table style="border:none; padding:0; width:100%; margin: 0" >
					<tr>
						<td style="margin:0; padding:0; border:none; vertical-align:top;">
							<a href="<?php echo home_url(); ?>/my-account/edit-address/shipping" class="edit">
								<img src="<?php echo home_url(); ?>/wp-content/uploads/2018/01/edit_address_icon.png" alt="">
							</a>
						</td>
						<td style="margin:0; padding:0; border:none">
							<a href="<?php echo home_url(); ?>/my-account/edit-address/shipping" class="edit">Edit shipping address for future orders only</a>
						</td>
					</tr>
				</table>
			</td>
		<?php endif; ?>
	</tr>
</table>
