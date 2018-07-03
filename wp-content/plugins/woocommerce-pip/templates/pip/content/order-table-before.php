<?php
/**
 * WooCommerce Print Invoices/Packing Lists
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Print
 * Invoices/Packing Lists to newer versions in the future. If you wish to
 * customize WooCommerce Print Invoices/Packing Lists for your needs please refer
 * to http://docs.woocommerce.com/document/woocommerce-print-invoice-packing-list/
 *
 * @package   WC-Print-Invoices-Packing-Lists/Templates
 * @author    SkyVerge
 * @copyright Copyright (c) 2011-2017, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * PIP Template Body before content
 *
 * @type \WC_Order $order Order object
 * @type int $order_id Order ID
 * @type \WC_PIP_Document Document object
 * @type string $type Document type
 * @type string $action Current document action
 *
 * @version 3.3.0
 * @since 3.0.0
 */

		?>
		<div id="order-<?php echo esc_attr( $order_id ); ?>" class="container">

			<header>
				<?php

				/**
				 * Fires before the document's header.
				 *
				 * @since 3.0.0
				 * @param string $type Document type
				 * @param string $action Current action running on document, one of 'print' or 'send_email'
				 * @param WC_PIP_Document $document Document object
				 * @param WC_Order $order Order object
				 */
				do_action( 'wc_pip_before_header', $type, $action, $document, $order );

				?>
				<div class="document-header <?php echo $type; ?>-header">

					<div style="float:left; width: 100%;border-bottom: 1px dotted #000;" class="address_cust">
							<?php echo $woocommerce_shipping_method_title; ?>
							<?php if (get_post_meta( $order_id, '_wcms_packages', true )) { ?>
							<?php $packages = get_post_meta( $order_id, '_wcms_packages', true );
								foreach ($packages as $package):
									echo '<p>' . WC()->countries->get_formatted_address( $package['full_address'] ) . '</p>';
								endforeach;
							?>
						<?php }
							else { ?>
							<p>
								<?php echo $order->get_formatted_shipping_address(); ?>
							</p>
							<?php } ?>
					  		<?php if ($order->billing_phone) : ?>
								<p><?php _e('Tel:', 'woocommerce-pip'); ?><?php echo $order->billing_phone; ?></p>
							<?php endif; ?>

					 </div>	
					 <div style="float: left; width: 49%;">
					<?php

					/**
					 * Fires inside the document's header after company information.
					 *
					 * @since 3.0.0
					 * @param string $type Document type
					 * @param string $action Current action running on document, one of 'print' or 'email'
					 * @param WC_PIP_Document $document Document object
					 * @param WC_Order $order Order object
					 */
					do_action( 'wc_pip_header', $type, $action, $document, $order ); ?>
					<h3 class="date_order"><?php _e('Order', 'woocommerce-pip'); ?> <?php echo $order->get_order_number(); ?> &mdash; <time datetime="<?php echo date("Y/m/d", strtotime($order->order_date)); ?>"><?php echo date("Y/m/d", strtotime($order->order_date)); ?></time></h3>
					</div>
					
					<div style="float: right; width: 49%;">
						<div class="company-title <?php echo empty( $align_title ) ? 'left' : $align_title; ?>">

							<?php
								$logo  = $document->get_company_logo();
								$title = ! empty( $logo ) ? $document->get_company_logo() : $document->get_company_name();
							?>

							<h1 class="title"><?php echo $document->get_company_link( $title ); ?></h1>

							<?php $subtitle = $document->get_company_extra_info(); ?>

							<?php if ( $subtitle ) : ?>
								<h5 class="company-subtitle align-<?php echo $align_title; ?>"><?php echo $subtitle; ?></h5>
							<?php endif; ?>
						</div>
					</div>
					
					<div style="clear:both;"></div>

					<div class="customer-addresses">
					 <div style="float:left; width: 49%;">
							<div class="customer-address billing-address">
                                                           <?php if ( $document->show_billing_address() ) : ?>
								<h3><?php esc_html_e( 'Billing Address', 'woocommerce-pip' ); ?></h3>

								<address class="customer-addresss">
									<?php
										/**
										 * Filters the customer's billing address.
										 *
										 * @since 3.0.0
										 * @param string $billing_address The formatted billing address
										 * @param string $type WC_PIP_Document type
										 * @param WC_Order $order The WC Order object
										 */
										echo apply_filters( 'wc_pip_billing_address', $order->get_formatted_billing_address(), $type, $order );
									?>
								</address>
							    <?php endif; ?>
       
                                                            <?php do_action( 'wc_print_invoice_packing_template_body_after_billing_address', $order ); ?>
							    <?php if (get_post_meta($order->id, 'VAT Number', TRUE) && $action == 'print_invoice') : ?>
								<p><strong><?php _e('VAT:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta($order->id, 'VAT Number', TRUE); ?></p>
							    <?php endif; ?>
							    <?php if ($order->billing_email) : ?>
								<p><strong><?php _e('Email:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_email; ?></p>
							    <?php endif; ?>
							    <?php if ($order->billing_phone) : ?>
								<p><strong><?php _e('Tel:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_phone; ?></p>
							    <?php endif; ?>
							</div>
					  </div>
					  <div style="float:right; width: 49%;">

							<div class="customer-address shipping-address">
                                                                <?php if ( $document->show_shipping_address() ) : ?>
								<h3><?php esc_html_e( 'Shipping Address', 'woocommerce-pip' ); ?></h3>

								<address class="customer-address">
									<?php
										/**
										 * Filters the customer's shipping address.
										 *
										 * @since 3.0.0
										 * @param string $shipping_address The formatted shipping address
										 * @param string $type WC_PIP_Document type
										 * @param WC_Order $order The WC Order object
										 */
										echo apply_filters( 'wc_pip_shipping_address', $order->get_formatted_shipping_address(), $type, $order );
									?>
								</address>
                                                                <?php endif; ?>
                                                              
                                                                <?php if (get_post_meta( $order_id, '_tracking_provider', true )) : ?>
								     <p><strong><?php _e('Tracking provider:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta( $order_id, '_tracking_provider', true ); ?></p>
							        <?php endif; ?>
							        <?php if (get_post_meta( $order_id, '_tracking_number', true )) : ?>
								     <p><strong><?php _e('Tracking number:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta( $order_id, '_tracking_number', true ); ?></p>
							        <?php endif; ?>

                                                                <?php if ( $document->show_shipping_method() ) : ?>
								   <p class="shipping-method"><?php esc_html_e( 'Shipping Method', 'woocommerce-pip' ); ?> <em><?php echo $document->get_shipping_method(); ?></em></p>
						                <?php endif; ?>
                                                                
							</div>


					  </div>
		
					</div>

					<div style="clear:both;"></div>	
					<?php if ( 'pick-list' === $type ) : ?>
						<div>
							<strong><?php _e( 'Shipping:', 'woocommerce-pip' ); ?></strong>
							<?php echo $order->get_shipping_method(); ?>
						</div>						
					<?php endif; ?>

					<?php if ($order->customer_note) { ?>
						<div>
							<h3><?php _e('Order notes', 'woocommerce-pip'); ?></h3>
							<?php echo $order->customer_note; ?>
						</div>
					<?php } ?>
					
					<?php

					/**
					 * Fires after the customer's address is printed on the document.
					 *
					 * @since 3.0.0
					 * @param string $type Document type
					 * @param string $action Current action running on Document
					 * @param WC_PIP_Document $document Document object
					 * @param WC_Order $order Order object
					 */
					do_action( 'wc_pip_after_customer_addresses', $type, $action, $document, $order );

					?>

					<?php if ( $document->show_header() ) : ?>

						<div class="document-heading <?php echo $type; ?>-heading">
							<?php echo $document->get_header(); ?>
						</div>

					<?php endif; ?>

				</div>
				<?php

				/**
				 * Fires after the document's header.
				 *
				 * @since 3.0.0
				 * @param string $type Document type
				 * @param string $action Current action running on Document
				 * @param WC_PIP_Document $document Document object
				 * @param WC_Order $order Order object
				 */
				do_action( 'wc_pip_after_header', $type, $action, $document, $order );

				?>
			</header>

			<main class="document-body <?php echo $type; ?>-body">
				<?php

				/**
				 * Fires before the document's body (order table).
				 *
				 * @since 3.0.0
				 * @param string $type Document type
				 * @param string $action Current action running on Document
				 * @param WC_PIP_Document $document Document object
				 * @param WC_Order $order Order object
				 */
				do_action( 'wc_pip_before_body', $type, $action, $document, $order );

				?>
				<table class="order-table <?php echo $type; ?>-order-table">
					<?php
