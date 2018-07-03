<?php
/**
 * My Orders
 *
 * @deprecated  2.6.0 this template file is no longer used. My Account shortcode uses orders.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
	'order-number'  => __( '#', 'woocommerce' ),
	'order-date'    => __( 'Date', 'woocommerce' ),
	'order-status'  => __( 'Status', 'woocommerce' ),
	'order-items'  => __( 'Quantity', 'woocommerce' ),
	'order-total'   => __( 'Total', 'woocommerce' ),
	'order-actions' => '&nbsp;',
) );

$customer_orders1 = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
    'numberposts' => '-1',
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
)));
$total_records = count($customer_orders1);
$posts_per_page = 10;
$total_pages = ceil($total_records / $posts_per_page);
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$customer_orders = get_posts(array(
    'meta_key' => '_customer_user',
    'meta_value' => get_current_user_id(),
    'post_type' => wc_get_order_types('view-orders'),
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'post_status' => array_keys(wc_get_order_statuses())
    ));

if ( $customer_orders ) : ?>

	<table class="shop_table shop_table_responsive my_account_orders tabledesc hide_on_mobile">

		<thead>
			<tr>
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
				<?php $result_order = get_post_meta( $order->id, '_payment_method', true ); ?>		
				<tr class="order <?php if ($result_order === 'bacs') : echo $result_order; endif; ?>">
					<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
						<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<span id="load_click">
									<?php echo _x( '<i class="fa fa-caret-right" aria-hidden="true"></i>', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</span>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>
								<div class="order_details">
									<?php foreach($order->get_items() as $item) {
									    $product_id = $item['product_id'];
								            $product = wc_get_product( $product_id );
	                						    echo '<p>'. $product->get_image();
									    $product_name = $item['name'];
									    echo '<span>'. $product_name .'</span></p>'; 
									} ?>
								</div>
							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php if (wc_get_order_status_name( $order->get_status()) == 'Failed') {  ?>
									<span style="color:#f2f2f2;"><?php echo wc_get_order_status_name( $order->get_status()); ?></span>
								<?php } elseif (wc_get_order_status_name( $order->get_status()) == 'Cancelled') { ?>
									<span style="color:#e03f3f;"><?php echo wc_get_order_status_name( $order->get_status()); ?></span>
								<?php } elseif (wc_get_order_status_name( $order->get_status()) == 'On Hold') { ?>
									<span style="color:#e7ad0f;"><?php echo wc_get_order_status_name( $order->get_status()); ?></span>
								<?php } else { ?>
									<span style="color:#4c5568;"><?php echo wc_get_order_status_name( $order->get_status()); ?></span>
								<?php } ?>
							
							<?php elseif ( 'order-items' === $column_id ) : ?>
								<?php if ($item_count == '1') {
									echo $item_count . ' item';
								} else {
									echo $item_count . ' items';
								} ?>
								<div class="order_items_qty order_details">
									<?php foreach($order->get_items() as $item) {
									    $product_qty = $item['qty'];
									    if ($product_qty == '1') {
									    	echo '<p>'. $product_qty .' item</p>'; 
									    } else {
									    	echo '<p>'. $product_qty .' items</p>';
									    }
									} ?>
								</div>
							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php echo $order->get_formatted_order_total(); ?>
								<div class="order_items_total order_details">
									<?php foreach($order->get_items() as $item) {
									    echo '<p><span class="WebRupee">Rs. </span>'. $order->get_item_total( $item ).'</p>';
									} ?>
								</div>
							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
									$actions = array(
										'pay'    => array(
											'url'  => $order->get_checkout_payment_url(),
											'name' => __( 'Pay', 'woocommerce' )
										),
										'view'   => array(
											'url'  => $order->get_view_order_url(),
											'name' => __( 'View', 'woocommerce' )
										),
										'cancel' => array(
											'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
											'name' => __( 'Cancel', 'woocommerce' )
										)
									);

									if ( ! $order->needs_payment() ) {
										unset( $actions['pay'] );
									}

									if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed', 'on-hold' ), $order ) ) ) {
										unset( $actions['cancel'] );
									}

									if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										}
									}

								?>
								<?php $result = get_post_meta( $order->id, '_payment_method', true );
										if ($result === 'bacs') : ?>
											<div class="bank_details"><span class="sfoo_myacc_title_button" id="title_button">Our Bank Details</span>
												<p class="bank_details_info">
													<span class="bank_name">Sattvic Innovations - <strong>Kotak Bank</strong></span>
													<span>Account Number: <strong>9412221362</strong></span>
													<span>IFSC: <strong>KKBK0000701</strong></span>
													<span>Account Type: <strong>Current Account</strong></span>
													<span>Branch Name: <strong>Panaji Branch</strong></span>
												</p>
											</div>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	
	
	<table class="woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table tabledesc show_om_mobile">
	<?php $my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
		'order-number'  => __( 'Order', 'woocommerce' ),
		'order-date'    => __( 'Date', 'woocommerce' ),
		'order-status'  => __( 'Status', 'woocommerce' ),
		'order-items'  => __( 'Quantity', 'woocommerce' ),
		'order-total'   => __( 'Total', 'woocommerce' ),
		'order-actions' => '&nbsp;',
	) ); ?>
		<tbody>
			<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					
				<tr class="order <?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<td class="thead"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></td>
						<td class="body">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo wc_get_order_status_name( $order->get_status() ); ?>
							<?php elseif ( 'order-items' === $column_id ) : ?>
								<?php if ($item_count == '1') {
									echo $item_count . ' item';
								} else {
									echo $item_count . ' items';
								} ?>
							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php echo $order->get_formatted_order_total(); ?>

							
							<?php endif; ?>
						</td>
					
				</tr>
				<?php endforeach; ?>
				<?php $result_order = get_post_meta( $order->id, '_payment_method', true ); ?>	
				<tr class="actions <?php if ($result_order === 'bacs') : echo $result_order; endif; ?>">
					<td colspan="2">
						<?php if ( 'order-actions' === $column_id ) : ?>
								<?php
									$actions = array(
										'pay'    => array(
											'url'  => $order->get_checkout_payment_url(),
											'name' => __( 'Pay', 'woocommerce' )
										),
										'view'   => array(
											'url'  => $order->get_view_order_url(),
											'name' => __( 'View', 'woocommerce' )
										),
										'cancel' => array(
											'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
											'name' => __( 'Cancel', 'woocommerce' )
										)
									);

									if ( ! $order->needs_payment() ) {
										unset( $actions['pay'] );
									}

									if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed', 'on-hold' ), $order ) ) ) {
										unset( $actions['cancel'] );
									}

									if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										}
									}
								?>
								    <?php $result = get_post_meta( $order->id, '_payment_method', true );
										if ($result === 'bacs') : ?>
											<div class="bank_details"><span id="title_button">Our Bank Details</span>
												<p class="bank_details_info">
													<span class="bank_name">Sattvic Innovations - <strong>Kotak Bank</strong></span>
													<span>Account Number: <strong>9412221362</strong></span>
													<span>IFSC: <strong>KKBK0000701</strong></span>
													<span>Account Type: <strong>Current Account</strong></span>
													<span>Branch Name: <strong>Panaji Branch</strong></span>
												</p>
											</div>
									<?php endif; ?>
								<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	
	
<?php endif; ?>
<div class="pagination">
            <?php
            $args = array(
                'base' => '%_%',
                'format' => '?page=%#%',
                'total' => $total_pages,
                'current' => $paged,
                'show_all' => False,
                'end_size' => 5,
                'mid_size' => 5,
                'prev_next' => True,
                'prev_text' => __('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back'),
                'next_text' => __('Next <i class="fa fa-long-arrow-right" aria-hidden="true"></i>'),
                'type' => 'plain',
                'add_args' => False,
                'add_fragment' => ''
            );
            echo paginate_links($args);
            ?>
        </div>