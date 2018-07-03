<?php
/***********************************************************/
// Free Sample from WP admin
/***********************************************************/
add_action( 'woocommerce_order_item_add_action_buttons', 'action_woocommerce_order_item_add_action_buttons', 10, 1);
function action_woocommerce_order_item_add_action_buttons( $order ) {
    echo '<button type="button" id="sample" class="button generate-items">' . __( 'Add Free Sample', 'hungred' ) . '</button>';
    echo '<input type="hidden" value="1" name="renew_order" />';
	echo '<div id="sample-products" style="display:none;">';
   $featured = new WP_Query(array(
      'post_type' => 'product',
      'meta_key' => 'sample_enamble',
      'meta_value' => true,
	  'post_status' => 'publish',
      'posts_per_page' => -1));
      if($featured->have_posts()) :
         echo '<div class="widefat" cellspacing="0">';
      while ($featured->have_posts()) : $featured->the_post();
         //echo '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
		 echo get_the_title();
		 echo '<form action="" class="cart sample" method="post" enctype="multipart/form-data">';
		 echo '<div class="single_variation_wrap variations_button" style="">';
		 echo '<button type="submit" class="single_add_to_cart_button button alt single_add_sample_to_cart_button btn btn-default">Add free sample to cart</button>'; 
		 echo '<input type="hidden" name="sample" id="sample" value="true">';
		 echo '<input type="hidden" name="add-to-cart" id="sample_add_to_cart" value="326">';
		 echo '</div>';
		 echo '</form>';	 
      endwhile;
         echo '</div>';
      else:
         echo 'There are no Free Samples';
      endif;
      wp_reset_query();
   echo '</div>';
   ?>
<style>
#sample-products {
	position: absolute;
    z-index: 1;
    left: 0px;
    right: 0px;
    text-align: left;
    padding: 1em;
    background: rgb(248, 248, 248);
    bottom: -11em;
    border: 1px solid #dfdfdf;
    width: 50%;
    margin: 0 auto;
	height: 8rem;
    overflow: scroll;
    overflow-x: hidden;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('button#sample').on('click', function(){
        var self = $(this);
        $('#sample-products').fadeToggle( "slow", "linear" );
        return false;
    });
});
</script>
   <?php
};
add_action('save_post', 'renew_save_again', 10, 3);
function renew_save_again($post_id, $post, $update){
    $slug = 'shop_order';
    if(is_admin()){
            // If this isn't a 'woocommercer order' post, don't update it.
            if ( $slug != $post->post_type ) {
                    return;
            }
            if(isset($_POST['renew_order']) && $_POST['renew_order']){
                    // alert ('save');
            }
    }
}

/***********************************************************/
// Add Custom Style to Admin Panel
/***********************************************************/
add_action('admin_head', 'my_custom_fonts');
function my_custom_fonts() {
  echo '<style>
    .post-type-shop_order #tiptip_holder {
      display:block !important;
      opacity:1 !important;
    } 
    .wrap .page-title-action.dublicate_order {position: absolute;top: 21px;left: 15rem;}
    @media screen and (max-width: 782px) {
    	p.search-box {
	    float: left;
	    position: relative;
	}
	.post-type-shop_order #postbox-container-1 #send_sms_to_buyer,
	.post-type-shop_order #postbox-container-1 #order-tracking-information,
	.post-type-shop_order #postbox-container-2 #postcustom,
	.post-type-shop_order #postbox-container-2 #woocommerce-order-downloads {display:none;}
	.wrap .page-title-action.dublicate_order {position:relative;top:0;left:0;display: inline-block;}
    }
    .post-type-shop_order .wp-list-table thead #order_total,
    .post-type-shop_order .wp-list-table tbody .order_total {width: 14%;}
    .post-type-shop_order .wp-list-table tbody .order_actions,
    .post-type-shop_order .wp-list-table thead #order_actions {width: 120px;}
    .post-type-shop_order .wp-list-table thead #order_items,
    .post-type-shop_order .wp-list-table tbody .order_items {width: 10%;}
    .post-type-shop_order .wp-list-table thead #shipping_address,
    .post-type-shop_order .wp-list-table tbody .shipping_address {width: 18%;}
	.alignleft.custom_field_hsn {clear:both;}
	@media screen and (max-width: 480px){
		.post-type-shop_order .subtitle {padding-left: 0;display: block;}
	}
  </style>';
}
require_once 'ccavenue/API.php'; 
        
/***********************************************************/
// Payu Verification columns
/***********************************************************/
add_filter( 'manage_edit-shop_order_columns', 'woo_order_payu_column' );
function woo_order_payu_column( $columns ) {
  $new = array();
  foreach($columns as $key => $title) {
    if ($key=='order_actions') 
      $new['pumcp'] = __( 'Payu Verification', 'woocommerce' );
    $new[$key] = $title;
  }
  return $new;
}

add_action( 'manage_shop_order_posts_custom_column', 'woo_custom_order_weight_column', 2 );
function woo_custom_order_weight_column($column) {
	global $woocommerce, $post;
	$key = "mZnOKb";
	$salt = "BBKho8Pc";
	$command = "verify_payment";
	$date = get_the_date('ymd',$post->ID);
	$var1 = $post->ID.'_'.$date;
	
	$hash_str = $key  . '|' . $command . '|' . $var1 . '|' . $salt ;
	$hash = strtolower(hash('sha512', $hash_str));
		$r = array('key' => $key , 'hash' =>$hash , 'var1' => $var1, 'command' => $command);
		$wsUrl = "https://info.payu.in/merchant/postservice?form=2";
		$qs= http_build_query($r);
				$c = curl_init();
				curl_setopt($c, CURLOPT_URL, $wsUrl);
				curl_setopt($c, CURLOPT_POST, 1);
				curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
				curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
				$o = curl_exec($c);
				 if (curl_errno($c)) {
				  $sad = curl_error($c);
				  throw new Exception($sad);
				}
				curl_close($c);	
				$valueSerialized = @unserialize($o);
		
	    if ( $column == 'pumcp' ) {
	        $payment_gateway = get_post_meta( $post->ID, '_payment_method', true ); 
	        if ($payment_gateway == 'pumcp') {
				$someArray = json_decode($o);
					foreach($someArray->transaction_details as $trans => $single_t) {
						if ($single_t->status === 'success') {
							echo '<span style="color:green;text-transform: capitalize;">' . $single_t->status .'</span>';
						} elseif ($single_t->status === 'failure') {
							echo '<span style="color:red;text-transform: capitalize;">' . $single_t->status .'</span>';
						} else {
							echo '<span style="color:#ffb300;">' . $single_t->status .'</span>';
						}
					}
			} else if ($payment_gateway == 'ccavenue') {

        $ccavenue_api = new CCAvenue_API();

        $cc_order_id = get_post_meta($post->ID,'_ccave_order_id',true);

        if (!$cc_order_id) {
          $order = new WC_Order($post->ID);
          $order_data = $order->get_data();
          // $itmeta = wc_display_item_meta( $order );
          // $transID = $order->get_transaction_id();
          // $ref_no = 567116;
          $order_date_created = $order_data['date_created']->date('ymd');

          $cc_order_id = $post->ID . '_' . $order_date_created;
          // $order_date_modified = $order_data['date_modified']->date('ymd');
        }

        $cc_post_data = array("order_no" => $cc_order_id);
       
        $cc_t_data = trim($ccavenue_api->orderStatusTracker($cc_post_data));

        $cc_t_data_end =  strrpos($cc_t_data, '}');
        $cc_t_data = substr($cc_t_data, 0, $cc_t_data_end + 1);

        $cc_response = json_decode($cc_t_data);

        $cc_order_status = $cc_response->order_status;

        if ($cc_order_status == 'Success' ||
            $cc_order_status == 'Shipped' ||
            $cc_order_status == 'Successful') {

          echo '<span style="color:green;text-transform: capitalize;">' . $cc_order_status .'</span>';

        } else if ($cc_order_status == 'Awaited' ||
                   $cc_order_status == 'Chargeback' ||
                   $cc_order_status == 'Fraud' ||
                   $cc_order_status == 'Initiated' ||
                   $cc_order_status == 'Refunded' ||
                   $cc_order_status == 'Refunded' ||
                   $cc_order_status == 'System refund' ) {

          echo '<span style="color:#ffb300;">' . $cc_order_status .'</span>';

        } else if ($cc_order_status == 'Auto-Cancelled' ||
                   $cc_order_status == 'Aborted' ||
                   $cc_order_status == 'Failure' ||
                   $cc_order_status == 'Cancelled' ||
                   $cc_order_status == 'Invalid' ||
                   $cc_order_status == 'Unsuccessful' ){

          echo '<span style="color:red;text-transform: capitalize;">' . $cc_order_status .'</span>';

        } else {

          echo '<span style="color:black;text-transform: capitalize;"> Cancelled </span>';

          /* error debug */
          // var_dump($cc_response);
          // var_dump( $cc_order_id);
          // echo $order_date_created . ' - ' . $order_date_modified;
        }

      } else {
				echo 'Not Payu Gateway';
			}
	    }
}
/***********************************************************/
// CUSTOM HSN CODE  
/***********************************************************/
add_action( 'woocommerce_product_options_general_product_data', 'wc_custom_add_custom_fields' );
function wc_custom_add_custom_fields() {
    woocommerce_wp_text_input( array(
        'id' => '_hsn',
        'label' => 'HSN',
        'desc_tip' => 'false',
        'placeholder' => 'HSN code'
    ) );
}
add_action( 'woocommerce_process_product_meta', 'wc_custom_save_custom_fields' );
function wc_custom_save_custom_fields( $post_id ) {
    if ( ! empty( $_POST['_hsn'] ) ) {
        update_post_meta( $post_id, '_hsn', esc_attr( $_POST['_hsn'] ) );
    }
}

//Add custom column into Product Page
add_filter('manage_edit-product_columns', 'my_columns_into_product_list');
function my_columns_into_product_list($defaults) {
    $defaults['_hsn'] = 'HSN for Product';
    return $defaults;
}
 
//Add rows value into Product Page
add_action( 'manage_product_posts_custom_column' , 'my_custom_column_into_product_list', 10, 2 );
function my_custom_column_into_product_list($column, $post_id ){
    switch ( $column ) {
    case '_hsn':
        echo get_post_meta( $post_id , '_hsn' , true );
    break;
    }
}


function action_woocommerce_product_quick_edit_end( $post_ids ) { 
    ?>
        <label class="alignleft custom_field_hsn">
            <span class="title"><?php _e('HSN', 'woocommerce' ); ?></span>
            <span class="input-text-wrap"><?php echo get_post_meta( $post_id , '_hsn' , true ); ?>
				<input type="text" name="_hsn" class="text" placeholder="Update HSN" value="">
			</span>
        </label>
    <?php
}; 
add_action( 'woocommerce_product_quick_edit_end', 'action_woocommerce_product_quick_edit_end', 10, 0 ); 

add_action('woocommerce_product_quick_edit_save', function($product){
if ( $product->is_type('simple') || $product->is_type('variable') ) {
    $post_id = $product->id;
    if ( isset( $_REQUEST['_hsn'] ) ) {
        $hsn = trim(esc_attr( $_REQUEST['_hsn'] ));
        update_post_meta( $post_id, '_hsn', wc_clean( $hsn ) );
    }
}
}, 10, 1);

// Add the information in the order as meta data
add_action('woocommerce_add_order_item_meta','add_hsn_to_order_item_meta', 1, 3 );
function add_hsn_to_order_item_meta( $item_id, $values, $cart_item_key ) {
    $prod_id = wc_get_order_item_meta( $item_id, '_product_id', true );
    $warranty = get_post_meta( $product_id, '_hsn', true );
    wc_add_order_item_meta($item_id, 'HSN', $warranty, true);
}

/***********************************************************/
// EMAIL status info in order page
/***********************************************************/
add_action( 'add_meta_boxes', 'email_add_meta_boxes' );
if ( ! function_exists( 'email_add_meta_boxes' ) )
{
    function email_add_meta_boxes()
    {
        global $woocommerce, $order, $post;

        add_meta_box( 'email_other_fields', __('Emails sent to customer:','woocommerce'), 'email_add_other_fields_for_emailing', 'shop_order', 'side', 'core' );
    }
}

// adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'email_save_wc_order_other_fields' ) ) {
    function email_add_other_fields_for_emailing() {
		global $woocommerce, $post;
		$mailer           = WC()->mailer();
		$available_emails = apply_filters( 'woocommerce_resend_order_emails_available', array( 'new_order', 'cancelled_order', 'customer_processing_order', 'customer_completed_order', 'customer_invoice', 'customer_refunded_order' ) );
		$mails            = $mailer->get_emails($post->ID);
			foreach ( $mails as $mail ) {
				if ( in_array( $mail->id, $available_emails )) {
					echo esc_html( $mail->title );
				}
			}
    }
}

/***********************************************************/
// Dublicate orders button in single order edit page
/***********************************************************/
/*function dublicate_button() {
	global $post;
	if ($post->post_type=='shop_order') {
		$url = admin_url( 'edit.php?post_type=shop_order&order_id=' . $post->ID );  
	    $copy_link = wp_nonce_url( add_query_arg( array( 'duplicate' => 'init' ), $url ), 'edit_order_nonce' );
		echo '<a href="'. $copy_link .'" class="page-title-action dublicate_order">Copy Order</a>';
	}
				
}
add_action( 'edit_form_top', 'dublicate_button' );*/

/***********************************************************/
// Navigation inside order
/***********************************************************/
// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'mv_add_meta_boxes' );
if ( ! function_exists( 'mv_add_meta_boxes' ) ) {
    function mv_add_meta_boxes() {
        global $woocommerce, $order, $post;
        add_meta_box( 'mv_other_fields', __('Navigate to:','woocommerce'), 'mv_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
    }
}

// adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'mv_save_wc_order_other_fields' ) ) {
    function mv_add_other_fields_for_packaging() {
        global $woocommerce, $post;
		$mypoststatus = get_post_status($post->ID);
		if ($mypoststatus == 'wc-on-hold') {
           echo 'Packing Orders';
        } elseif ($mypoststatus == 'wc-completed')  {
           echo 'Packing Orders';
		} elseif ($mypoststatus == 'wc-pending')  {
           echo 'Packing Orders';
		} elseif ($mypoststatus == 'wc-processing')  {
           echo 'Packing Orders';
		} elseif ($mypoststatus == 'wc-cancelled')  {
           echo 'Packing Orders';
		} elseif ($mypoststatus == 'wc-refunded')  {
           echo 'Packing Orders';
		} elseif ($mypoststatus == 'wc-failed')  {
           echo 'Packing Orders';
		} elseif ($mypoststatus == 'wc-packing')  {
           echo 'Packing Orders';
		} elseif ($mypoststatus == 'wc-delivered')  {
           echo 'Packing Orders';
        } else {
           echo 'Orders';
        }
		echo '<br /><select name="orders" onchange="location = this.value;" style="width: 100%;margin: 5px 0;">';
		$args = array(
			'post_type' => 'shop_order',
			'post_status' => 'wc-packing',
			'posts_per_page' => '50'
			);
			$my_query = new WP_Query($args);
			$customer_orders = $my_query->posts;
			foreach ($customer_orders as $customer_order) {
			$billing_name =  get_post_meta( $customer_order->ID, '_billing_first_name', true );
			$billing_name_last =  get_post_meta( $customer_order->ID, '_billing_last_name', true );
			echo '<option value="post.php?post='. $customer_order->ID .'&action=edit">Order #' . $customer_order->ID . ' by ' . $billing_name .' ' . $billing_name_last . '</option>';
			}
		echo '</select>';
		echo 'Go to list of:';
		echo '<br /><select name="statuses" onchange="location = this.value;" style="width: 100%;margin: 5px 0;">';
			echo '<option>Choose a List</option>';
			echo '<option value="edit.php?post_status=wc-pending&post_type=shop_order">Pending Payment</option>';
			echo '<option value="edit.php?post_status=trash&post_type=shop_order">Trash</option>';
			echo '<option value="edit.php?post_status=wc-processing&post_type=shop_order">Processing</option>';
			echo '<option value="edit.php?post_status=wc-on-hold&post_type=shop_order">On Hold</option>';
			echo '<option value="edit.php?post_status=wc-completed&post_type=shop_order">Shipped</option>';
			echo '<option value="edit.php?post_status=wc-cancelled&post_type=shop_order">Cancelled</option>';
			echo '<option value="edit.php?post_status=wc-refunded&post_type=shop_order">Refunded</option>';
			echo '<option value="edit.php?post_status=wc-failed&post_type=shop_order">Failed</option>';
			echo '<option value="edit.php?post_status=wc-packing&post_type=shop_order">Packing</option>';
			echo '<option value="edit.php?post_status=wc-delivered&post_type=shop_order">Delivered</option>';
			echo '<option value="edit.php?post_type=shop_order">All</option>';
		echo '</select>';
    }
}

?>