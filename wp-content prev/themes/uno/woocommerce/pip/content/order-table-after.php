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
 * Template Body after content
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
					</table>
					<?php

					/**
					 * Fires after the document's body (order table).
					 *
					 * @since 3.0.0
					 * @param string $type Document type
					 * @param string $action Current action running on Document
					 * @param WC_PIP_Document $document Document object
					 * @param WC_Order $order Order object
					 */
					do_action( 'wc_pip_after_body', $type, $action, $document, $order );

					?>

					<?php if ( $document->show_coupons_used() ) : ?>

						<?php $coupons = $document->get_coupons_used(); ?>

						<?php if ( $coupons && is_array( $coupons ) ) : ?>

							<?php
								/* translators: Placeholder: %1$s - opening <strong> tag, %2$s - coupons count (used in order), %3$s - closing </strong> tag - %4$s - coupons list */
								printf( '<div class="coupons-used">' . _n( '%1$sCoupon used:%3$s %4$s', '%1$sCoupons used (%2$s):%3$s %4$s', count( $coupons ), 'woocommerce-pip' ) . '</div><br>', '<strong>', count( $coupons ), '</strong>', '<span class="coupon">' . implode( '</span>, <span class="coupon">', $coupons ) . '</span>' );
							?>

						<?php endif; ?>

					<?php endif; ?>

					

					<?php if ( $document->show_customer_note() ) : ?>

						<div class="customer-note"><blockquote><?php echo $document->get_customer_note(); ?></blockquote></div>

					<?php endif; ?>

					<?php

					/**
					 * Fires after customer details.
					 *
					 * @since 3.0.0
					 * @param string $type Document type
					 * @param string $action Current action running on Document
					 * @param WC_PIP_Document $document Document object
					 * @param WC_Order $order Order object
					 */
					do_action( 'wc_pip_order_details_after_customer_details', $type, $action, $document, $order );

					?>
				</main>

				<br>

				<footer class="document-footer <?php echo $type; ?>-footer">
					<?php

					/**
					 * Fires before the document's footer.
					 *
					 * @since 3.0.0
					 * @param string $type Document type
					 * @param string $action Current action running on Document
					 * @param WC_PIP_Document $document Document object
					 * @param WC_Order $order Order object
					 */
					do_action( 'wc_pip_before_footer', $type, $action, $document, $order );

					?>

					<?php if ( $document->show_terms_and_conditions() ) : ?>

						<div class="terms-and-conditions"><?php echo $document->get_return_policy(); ?></div>

					<?php endif; ?>

					<hr>

					<?php if ( $document->show_footer() ) : ?>

						<div class="document-colophon <?php echo $type; ?>-colophon">
							<?php echo $document->get_footer(); ?>
						</div>

					<?php endif; ?>

					<?php

					/**
					 * Fires after the document's footer.
					 *
					 * @since 3.0.0
					 * @param string $type Document type
					 * @param string $action Current action running on Document
					 * @param WC_PIP_Document $document Document object
					 * @param WC_Order $order Order object
					 */
					do_action( 'wc_pip_after_footer', $type, $action, $document, $order );

					?>
				</footer>

				<?php if ( 'pick-list' === $type ) : ?>

					<div class="containerr">
				
                       <header>
						<div style="width: 100%;">
					    <div style="float: left; width: 49%;">
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
                                            <div style="float: right; width: 49%;">
						<h3 style="font-size:14px;">Declaration Letter<br />
                                                To Whomsoever It May Concern</h3>
			  		    </div>
					
		  			</div> 
					<div style="clear:both;"></div>
                    </header>
					
					 <p>I, <?php echo $order->shipping_first_name;?> <?php echo $order->shipping_last_name;?>, have placed the order for </p>
					                                <div class="datagrid" style="width:100%;">
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
					<thead class="order-table-head">
						<tr>
							<?php $column_widths = $document->get_column_widths(); ?>

							<?php foreach( $document->get_table_headers() as $column_id => $title ): ?>
								<th class="<?php echo esc_attr( $column_id ); ?>" style="width: <?php echo esc_attr( $column_widths[ $column_id ] ); ?>%"><?php echo esc_html( $title ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>

                                        <tbody class="order-table-body">

						<?php $table_rows = $document->get_table_rows(); ?>

						<?php foreach( $table_rows as $rows ) : ?>

							<?php if ( ! empty( $rows['headings'] ) && is_array( $rows['headings'] ) ) : ?>

								<tr class="row heading">

									<?php foreach ( $rows['headings'] as $cell_id => $cell ) : ?>

										<?php if ( ! empty( $cell['content'] ) ) : ?>

											<th class="<?php echo esc_attr( $cell_id ); ?>" <?php if ( ! empty( $cell['colspan'] ) ) { echo 'colspan="' . (int) $cell['colspan'] . '"'; } ?>>
												<?php echo $cell['content']; ?>
											</th>

										<?php endif; ?>

									<?php endforeach; ?>

								</tr>

							<?php endif; ?>

							<?php if ( ! empty( $rows['items'] ) ) : $i = 0; ?>

								<?php foreach ( $rows['items'] as $items ) : ?>

									<?php if ( ! empty( $items ) && is_array( $items ) ) : $i++; ?>

										<tr class="row item <?php echo $i % 2 === 0 ? 'even' : 'odd'; ?>">

											<?php foreach ( $items as $cell_id => $cell_content ) : ?>

												<td class="<?php echo esc_attr( $cell_id ); ?>">
													<?php echo $cell_content; ?>
												</td>

											<?php endforeach; ?>

										</tr>

									<?php endif; ?>

								<?php endforeach; ?>

							<?php endif; ?>

						<?php endforeach; ?>

					</tbody>
					

				  </table>	

                                  </div>

                                  <div style="float:left; width: 50%;">
                                      <h3 style="font-size: 14px;"><?php _e('Order', 'woocommerce-pip'); ?> <?php echo $order->get_order_number(); ?> &mdash; <time datetime="<?php echo date("Y/m/d", strtotime($order->order_date)); ?>"><?php echo date("Y/m/d", strtotime($order->order_date)); ?></time></h3>

                                       <p>
                                                         <?php echo $order->shipping_first_name; ?> <?php echo $order->shipping_last_name; ?> <br/>
                                                         <?php echo $order->shipping_address_1 ?>  <br /> 
                                                         <?php echo $order->shipping_address_2 ?>  <br /> 
                                                         <?php echo $order->shipping_city; ?>, <?php echo $order->shipping_state; ?> - <?php echo $order->shipping_postcode; ?>  <br />
                                                         <?php if ($order->billing_phone) : ?>
								<?php _e('Tel:', 'woocommerce-pip'); ?> <?php echo $order->billing_phone; ?>
							<?php endif; ?>                                              
                                                      </p>

				</div>

			<div style="clear:both;"></div>

            <p>I hereby confirm that said above goods are being purchased for my internal or personal purpose and not for re-sale. I further understand and agree to Terms and Conditions of Sale available at Sattvic Foods (<a href="http://13.127.121.242/">http://13.127.121.242/</a>) or upon request.</p>

            </div>

			<?php endif; ?>

			</div><!-- .container -->
			<div class="pagebreak"></div>
			<?php
