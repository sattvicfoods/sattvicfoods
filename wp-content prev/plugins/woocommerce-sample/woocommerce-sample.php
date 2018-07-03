<?php
/**
 * Plugin Name: WooCommerce Sample
 * Plugin URI: http://www.isikom.net/
 * Description: Include Get Sample Button in products of your online store.
 * Author: Michele Menciassi
 * Author URI: https://plus.google.com/+MicheleMenciassi
 * Version: 0.8.0
 * License: GPLv2 or later
 */
 
// Exit if accessed directly
if (!defined('ABSPATH'))
  exit;

//Checks if the WooCommerce plugins is installed and active.
	if (!class_exists('WooCommerce_Sample')) {
		class WooCommerce_Sample {
		/**
		 * Gets things started by adding an action to initialize this plugin once
		 * WooCommerce is known to be active and initialized
		 */
		public function __construct() {
			add_action('woocommerce_init', array(&$this, 'init'));
		}

		/**
		 * to add the necessary actions for the plugin
		 */
		public function init() {
	        // backend stuff
	        add_action('woocommerce_product_write_panel_tabs', array($this, 'product_write_panel_tab'));
	        add_action('woocommerce_product_write_panels', array($this, 'product_write_panel'));
	        add_action('woocommerce_process_product_meta', array($this, 'product_save_data'), 10, 2);
	        // frontend stuff
	        add_action('woocommerce_after_add_to_cart_form', array($this, 'product_sample_button'));      
			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
			//add_action('woocommerce_add_to_cart', $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data );
			//do_action( 'woocommerce_add_to_cart', $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data );
			// Prevent add to cart
			add_filter('woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 40, 4 );
			add_filter('woocommerce_add_cart_item_data', array( $this, 'add_sample_to_cart_item_data' ), 10, 2 );
			add_filter('woocommerce_add_cart_item', array( $this, 'add_sample_to_cart_item' ), 10, 3 );
			add_filter('woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );
			add_action( 'woocommerce_before_calculate_totals', array( $this, 'add_custom_price' ), 10, 1);
			add_filter( 'woocommerce_cart_item_price',array( $this, 'sv_change_product_price_cart' ), 10, 3 );
			add_filter('woocommerce_get_cart_item_from_session', array( $this, 'filter_session'), 10, 3);
			add_filter('woocommerce_in_cart_product_title', array( $this, 'cart_title'), 10, 3);
			add_filter('woocommerce_cart_widget_product_title', array( $this, 'cart_widget_product_title'), 10, 2);
			add_filter('woocommerce_cart_item_quantity', array( $this, 'cart_item_quantity'), 10, 2);
	
			add_filter('woocommerce_shipping_free_shipping_is_available', array( $this, 'enable_free_shipping'), 40, 1);
			add_filter('woocommerce_available_shipping_methods', array( $this, 'free_shipping_filter'), 10, 1);

			add_action('woocommerce_add_order_item_meta', array($this, 'add_order_item_meta'), 10, 2);
			
			// filter for Minimum/Maximum plugin override overriding
			if (in_array('woocommerce-min-max-quantities/min-max-quantities.php', apply_filters('active_plugins', get_option('active_plugins')))) {
				add_filter('wc_min_max_quantity_minimum_allowed_quantity', array($this, 'minimum_quantity'), 10, 4 );                //echo '<pre>$this '; print_r($this); echo '</pre>'; exit();
				add_filter('wc_min_max_quantity_maximum_allowed_quantity', array($this, 'maximum_quantity'), 10, 4 );
				add_filter('wc_min_max_quantity_group_of_quantity', array($this, 'group_of_quantity'), 10, 4 );			
			}

			// filter for Measurement Price Calculator plugin override overriding
			if (in_array('woocommerce-measurement-price-calculator/woocommerce-measurement-price-calculator.php', apply_filters('active_plugins', get_option('active_plugins')))) {
				add_filter('wc_measurement_price_calculator_add_to_cart_validation', array($this, 'measurement_price_calculator_add_to_cart_validation'), 10, 4 );
			}
         add_filter('woocommerce_general_settings', array($this, 'add_order_number_start_setting'), 100);
		}
function add_order_number_start_setting( $settings ) {

    $updated_settings = array();
    foreach ( $settings as $section ) {

if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&

   isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

  $updated_settings[] = array(

    'name'     => __( 'Minimum order value for free sample', 'wc_seq_order_numbers' ),
   // 'desc_tip' => __( 'Minimum order value for free sample', 'wc_seq_order_numbers' ),
    'id'       => 'woocommerce_minorder_free_sample',
    'type'     => 'text',
    'css'      => 'min-width:300px;',
    'std'      => '1', 
    'default' => '500',

  );
  $updated_settings[] = array(

    'name'     => __( 'Order value foreach free sample', 'wc_seq_order_numbers' ),
   // 'desc_tip' => __( 'Minimum order value for free sample', 'wc_seq_order_numbers' ),
    'id'       => 'woocommerce_maxorder_free_sample',
    'type'     => 'text',
    'css'      => 'min-width:300px;',
    'std'      => '1', 
    'default' => '1000',

  );

 }
   $updated_settings[] = $section;

  }

 return $updated_settings;
}
		function measurement_price_calculator_add_to_cart_validation ($valid, $product_id, $quantity, $measurements){
			global $woocommerce;
			$validation = $valid;
			if (get_post_meta($product_id, 'sample_enamble') && $_REQUEST['sample']){
				$woocommerce->session->set( 'wc_notices', null );
				$validation = true;
			}
			return $validation;
		}

		function add_order_item_meta ($item_id, $values){
			if ($values['sample']){
			//	woocommerce_add_order_item_meta( $item_id, 'product type', 'sample');
			}
		}
		
		// filter for Minimum/Maximum plugin overriding
		function minimum_quantity($minimum_quantity, $checking_id, $cart_item_key, $values){
			if ($values['sample'])
				$minimum_quantity = 1;
			return $minimum_quantity;
		}
      
		function maximum_quantity($maximum_quantity, $checking_id, $cart_item_key, $values){
		
		//  $sample_price_mode = get_post_meta($product_id, 'sample_price_mode', true) ? get_post_meta($product_id, 'sample_price_mode', true) : 'default';
	   // 
			if ($values['sample'])
				$maximum_quantity = 100;
			return $maximum_quantity;
		}

		function group_of_quantity($group_of_quantity, $checking_id, $cart_item_key, $values){
			if ($values['sample'])
				$group_of_quantity = 1;
			return $group_of_quantity;
		}
		// end filter for Mimimum/Maximum plugin overriding

		function enable_free_shipping($is_available){
      		global $woocommerce;
			if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
				$check = true;

				foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item){
					if ($cart_item['sample']){
						$sample_shipping_mode = get_post_meta($cart_item['product_id'], 'sample_shipping_mode', true);
						if ($sample_shipping_mode !== 'free'){
							$check = false;
							break;							
						}else{
							// sample is setted - we go on to check all other items in cart
						}
						
					}else{
						$check = false;
						break;
					}
				}

				if ($check === true){
					return true;
				}else{
					return $is_available;
				}
			}else{
				return $is_available;
			}
      }

      function free_shipping_filter( $available_methods )
      {
			if ( isset( $available_methods['free_shipping'] ) ) :
				// Get Free Shipping array into a new array
				$freeshipping = array();
				$freeshipping = $available_methods['free_shipping'];
		 
				// Empty the $available_methods array
				unset( $available_methods );
		 
				// Add Free Shipping back into $avaialble_methods
				$available_methods = array();
                $available_methods['free_shipping'] = $freeshipping;
			endif;
			return $available_methods;
      }

      function cart_item_quantity ($product_quantity, $cart_item_key){
      	      global $woocommerce;
      	      if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
      	      	      $cart_items = $woocommerce->cart->get_cart();
      	      	      $cart_item =$cart_items[$cart_item_key];
                      $product_id = $cart_item['product_id'];
                      
      	      	      if ($cart_item['sample']){
      	      	          $total = $woocommerce->cart->get_cart_total(); 
   	      	              $product_id = $cart_item['product_id'];
                          $sample_price_mode = get_post_meta($product_id, 'sample_price_mode', true) ? get_post_meta($product_id, 'sample_price_mode', true) : 'default';
                          //$product_quantity = sprintf( '<input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                        //  echo '<pre>'; print_r($sample_price_mode); echo '</pre>'; //exit();
      	      	          if($sample_price_mode === 'custom'){
      	      	             /* if($cart_item['quantity'] <= 1 ){
      	      	      	      $product_quantity = sprintf( '1 <input class="input-text qty text" name="cart[%s][qty]" size="4" value="1" disabled="disabled" type="hidden" />', $cart_item_key );                                     } else {*/
      	      	      	      $quantity = $cart_item['quantity'];
      	      	      	     $product_quantity = sprintf( '<div class="quantity new_quantity"><div class="quantity"><input class="input-text qty text" size="4" type="number" name="cart[%s][qty]" value="'.$quantity.'" step="1" min="0" max="" disabled="disabled"/></div></div>', $cart_item_key );   
   	      	      	     /* } */             
                              }
                          if($sample_price_mode === 'free'){                          
      	      	      	      // $product_quantity = sprintf( '<div class="quantity"><input class="input-text qty text" disabled="disabled" name="cart[%s][qty]" value="1" /></div>', $cart_item_key );
      	      	      	       $product_quantity = sprintf( '<div class="quantity new_quantity"><div class="quantity"><input class="input-text qty text" size="4" type="number" name="cart[%s][qty]" value="1" step="1" min="0" max="" disabled="disabled"/></div></div>', $cart_item_key );          }
      	      	      }
      	      }			
      	      return $product_quantity; 
      }
      
      function cart_title($title, $values, $cart_item_key){
      	      if ($values['sample']){
      	      	      $title .= ' [' . __('Sample','woosample') . '] ';
      	      }
      	      return $title;
      }
	  
      function cart_widget_product_title($title, $cart_item){
			if (is_array($cart_item) && $cart_item['sample']){
				$title .= ' [' . __('Sample','woosample') . '] ';
			}
			return $title;
	  }
      
      
      function get_item_data($item_data, $cart_item){
      	      global $cart_item_key;
              //echo '<pre>'; print_r($cart_item_key); echo '</pre>'; //exit();
       	     // if ($cart_item['sample']){
      	     // 	      error_log('SAMPLE TRUE');
      	     // }else{
      	     // 	      error_log('SAMPLE FALSE');
      	     // }
      	      return $item_data;
      }
      
      function add_sample_to_cart_item_data ($cart_item_data, $product_id, $variation_id){

      	      if (get_post_meta($product_id, 'sample_enamble') && $_REQUEST['sample']){
					$cart_item_data['sample'] = true;
					$cart_item_data['unique_key'] = md5($product_id . 'sample');
      	      }
      	      return $cart_item_data;
      }

	function add_sample_to_cart_item ($cart_item, $cart_item_key){
		if ($cart_item['sample'] === true){
			$cart_item[ 'data' ]->price = 0;
			if(isset($cart_item['variation']['attribute_pa_size'])) {
					$cart_item['variation']['attribute_pa_size'] = 'Free Sample';
			} else {
					$cart_item['variation']['attribute_size'] = 'Free Sample';
			}

			if(isset($cart_item['variation']['attribute_packaging'])) {
				$cart_item['variation']['attribute_packaging'] = null;
			}
			
			$post_title = $cart_item['data']->parent->post->post_title;
			$added_text = 'Free Sample "' .$post_title . '" has been added to your cart.';

			wc_add_notice( apply_filters( 'wc_add_to_cart_message', $added_text, $product_id ) );
		}
		return $cart_item;
	}

	// function add_sample_to_cart_item ($cart_item, $cart_item_key){
	// 	if ($cart_item['sample'] === true){
	// 		$cart_item[ 'data' ]->price = 0;
	// 		if(isset($cart_item['variation']['attribute_pa_size'])) {
	// 				$cart_item['variation']['attribute_pa_size'] = '';
	// 		} else {
	// 				$cart_item['variation']['attribute_size'] = '';
	// 		}
			
	// 		$post_title = $cart_item['data']->parent->post->post_title;
	// 		$added_text = 'Free Sample "' .$post_title . '" has been added to your cart.';

	// 		wc_add_notice( apply_filters( 'wc_add_to_cart_message', $added_text, $product_id ) );
	// 	}
	// 	return $cart_item;
	// }
	
	function sv_change_product_price_cart( $price, $cart_item, $cart_item_key ) {
			if ($cart_item['sample'] === true){
				$price = '<span class="woocommerce-Price-currencySymbol"><span class="WebRupee"> Rs. </span>0</span>';
			}
	return $price;
	}	

	function add_custom_price( $cart_obj/*, $value */) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;
			foreach ( $cart_obj->get_cart() as $key => $value ) {
				if ($value['sample']){
      	      	   $value['data']->set_price(0);
      	      }
			}
	 }

	  
	  function filter_session($cart_content, $value, $key){
      	      if ($value['sample']){
      	      	      $cart_content['sample'] = true;
      	      	      $cart_content['unique_key'] = $value['unique_key'];
      	      	      $cart_content['data']->price = 0;
					  $product_id = $cart_content['product_id'];
					  $sample_price_mode = get_post_meta($product_id, 'sample_price_mode', true) ? get_post_meta($product_id, 'sample_price_mode', true) : 'default';
					  $sample_price = get_post_meta($product_id, 'sample_price', true) ? get_post_meta($product_id, 'sample_price', true) : 0;
					  if ($sample_price_mode === 'custom'){
					  //	$cart_content['data']->price = $sample_price;
                         $cart_content['data']->price = 0;
                         
					  }else if ($sample_price_mode === 'free'){
					  	$cart_content['data']->price = 0;
					  }else{
					  	//default
					  }
      	      }
      	      return $cart_content;
      }
      /**
       * add_to_cart_validation function.
       *
       * @access public
       * @param mixed $pass
       * @param mixed $product_id
       * @param mixed $quantity
       * @return void
       */
      function add_to_cart_validation( $pass, $product_id, $quantity, $variation_id = 0 ) {
	global $woocommerce;
	$mode = get_post_meta($product_id, 'sample_price_mode', true);
	// se ci sono articoli nel carrello eseguiamo i controlli altrimenti se il carrello è vuoto aggiungiamo l'elemento senza controlli ulteriori
    $minorder_free_sample = get_option('woocommerce_minorder_free_sample', 1);
    $maxorder_free_sample = get_option('woocommerce_maxorder_free_sample', 1);
	  
		  $is_sample = empty($_REQUEST['sample']) ? false : true;
	if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
		// eseguiamo una validazione specifica solo se l'articolo aggiunto è un campione
		$cart_items = $woocommerce->cart->get_cart();

		if (!$is_sample) {
			foreach ($cart_items as $id_key => $item) {
				if ($product_id == $item['product_id'] && $item['sample'] == 1) {
					$post_title = $item['data']->parent->post->post_title;
					$message = 'Free sample of "' . $post_title . '" removed from cart';
					wc_add_notice(apply_filters('wc_add_to_cart_message', $message, $product_id));

					//$prod_unique_id = $woocommerce->cart->generate_cart_id($item['product_id']);
					//echo '<pre> '; print_r($prod_unique_id); echo '</pre>'; //exit();
					$woocommerce->cart->remove_cart_item($id_key);
					//	return false;
				}
			}
		}
		if ($is_sample){
			// l'articolo richiesto è un "campione" controlliamo che non sia già stato inserito nel carrello
			//$cart_items = $woocommerce->cart->get_cart();
			$unique_key = md5($product_id . 'sample');
			$total = $woocommerce->cart->get_cart_total();
            $total = preg_replace("/[^0-9]/", '', $total);  
            $sample_price = get_post_meta($product_id, 'sample_price', true) ? get_post_meta($product_id, 'sample_price', true) : 0;
			$quanti = 0;
			foreach ($cart_items as $cart_key => $cart){
				if($cart['data']->price == 0) {
					$quanti = $quanti+$cart['quantity'];
				}
			}
			$quanti = $quanti + 1;
            foreach ($cart_items as $cart_id_key => $cart_item){

				if ($mode == 'free'){
			    	if ($cart_item['unique_key'] == $unique_key){;
				    $post_title = $cart_item['data']->parent->post->post_title; 

                    	$added_text = 'Free Sample "' .$post_title . '" has been added to your cart.';
                        
                      wc_add_notice( apply_filters( 'wc_add_to_cart_message', $added_text, $product_id ) );

					return false;
				   } 
               }
                           
               //echo '<pre> '; print_r($_REQUEST); echo '</pre>'; //exit();
                if($mode == 'custom'){
                    
                  $post_title = $cart_item['data']->parent->post->post_title;      
                  $added_text_now = 'Free Sample "' .$post_title . '" not added to your cart. Minimum order value for free sample rs.'.$minorder_free_sample.'.';          


              // echo '<pre>-- '; print_r($cart_item['sample']); echo '</pre>'; //exit();
				//	echo '<pre>-- '; print_r($cart_item['sample']); echo '</pre>'; //exit();
				//	return false;

                if($_REQUEST['add-to-cart']==$cart_item['product_id']){
					$post_title = $cart_item['data']->parent->post->post_title;
					wc_add_notice( __( 'Product "'.$post_title.'" already in cart.', 'woocommerce' ), 'error' );
 	               return false;
				}
				
				if ($minorder_free_sample > $total){
				      // wc_add_notice( apply_filters( 'wc_add_to_cart_message', $added_text_now, $product_id ) );
					wc_add_notice( __( $added_text_now, 'woocommerce' ), 'error' );
					return false;
				   }
                 if ($total >= $minorder_free_sample && $cart_item['sample']==1){

                    $quantity_total = (int)$quanti * (int)$maxorder_free_sample;
					// echo '<pre>- '; print_r($quanti); echo '</pre>'; //exit();

                    $added_text = 'Free Sample "' .$post_title . '" not added to your cart. Minimum order value for '.$quanti.' free sample rs.'.$quantity_total.'.';     
                     if($total < $quantity_total){
                     //wc_add_notice( apply_filters( 'wc_add_to_cart_message', $added_text, $product_id ) );
						 wc_add_notice( __( $added_text, 'woocommerce' ), 'error' );
 
					return false;
                    }
				   }  
                    
                } 
          
			}
		}
	} else {
	   if($mode == 'custom' && $is_sample){
      $added_text_now = 'Add free sample - not allowed without minimum order value -'.$minorder_free_sample;
     // wc_add_notice( apply_filters( 'wc_add_to_cart_message', $added_text_now, $product_id ) );
      wc_add_notice( __( $added_text_now, 'woocommerce' ), 'error' );
 
	 return false;      

       }
	}
	// passiamo il valore impostato di default;
	return $pass;
      }
      /**
       * creates the tab for the administrator, where administered product sample.
       */
      public function product_write_panel_tab() {
        echo "<li><a class='added_sample' href=\"#sample_tab\">" . __('Sample','woosample') . "</a></li>";
      }

		/**
		 * build the panel for the administrator.
		 */
		public function product_write_panel() {
        	global $post;
			$sample_enable = get_post_meta($post->ID, 'sample_enamble', true) ? get_post_meta($post->ID, 'sample_enamble', true) : false;
			$sample_shipping_mode = get_post_meta($post->ID, 'sample_shipping_mode', true) ? get_post_meta($post->ID, 'sample_shipping_mode', true) : 'default';
			$sample_shipping = get_post_meta($post->ID, 'sample_shiping', true) ? get_post_meta($post->ID, 'sample_shipping', true) : 0;
			$sample_price_mode = get_post_meta($post->ID, 'sample_price_mode', true) ? get_post_meta($post->ID, 'sample_price_mode', true) : 'default';
			$sample_price = get_post_meta($post->ID, 'sample_price', true) ? get_post_meta($post->ID, 'sample_price', true) : 0;
            
         //   $sample_price_mode_order = get_post_meta($post->ID, 'sample_price_mode_order', true) ? get_post_meta($post->ID, 'sample_price_mode_order', true) : false;
        //    $sample_price_order = get_post_meta($post->ID, 'sample_price_order', true) ? get_post_meta($post->ID, 'sample_price_order', true) : 0;
            //echo '<pre>checked '; print_r($sample_price_order); echo '</pre>'; //exit();
			?>
			<div id="sample_tab" class="panel woocommerce_options_panel">
				<p class="form-field sample_enamble_field ">
					<label for="sample_enamble"><?php _e('Enable sample', 'woosample');?></label>
					<input type="checkbox" class="checkbox" name="sample_enamble" id="sample_enamble" value="yes" <?php echo $sample_enable ? 'checked="checked"' : ''; ?>> <span class="description"><?php _e('Enable or disable sample option for this item.', 'woosample'); ?></span>
				</p>
				<legend><?php _e('Sample Shipping', 'woosample'); ?></legend>
				<div class="options_group">
					<input class="radio" id="sample_shipping_default" type="radio" value="default" name="sample_shipping_mode" <?php echo $sample_shipping_mode == 'default' ? 'checked="checked"' : ''; ?>>
					<label class="radio" for="sample_shipping_default"><?php _e('use default product shipping methods', 'woosample'); ?></label>
				</div>
				<!--<div class="options_group">
					<input class="radio" id="sample_shipping_free" type="radio" value="free" name="sample_shipping_mode" <?php echo $sample_shipping_mode == 'free' ? 'checked="checked"' : ''; ?>>
					<label class="radio" for="sample_shipping_free"><?php _e('free shipping for sample', 'woosample'); ?></label>
				</div>-->
				<!--
				<div class="options_group">
					<input class="radio" id="sample_shipping_custom" type="radio" value="custom" name="sample_shipping_mode" <?php echo $sample_shipping_mode == 'custom' ? 'checked="checked"' : ''; ?>>
					<label class="radio" for="sample_shipping_custom"><?php _e('custom fee shipping', 'woosample'); ?></label>
					<p class="form-field sample_shipping_field clear">
						<label for="sample_shipping"><?php _e('set shipping fee', 'woosample'); ?></label>
						<input type="number" class="wc_input_price short" name="sample_shipping" id="sample_shipping" value="<?php echo $sample_shipping; ?>" step="any" min="0">
					</p>
				</div>
				-->
				<legend><?php _e('Sample price', 'woosample'); ?></legend>
				<!--<div class="options_group">
					<input class="radio" id="sample_price_default" type="radio" value="default" name="sample_price_mode" <?php echo $sample_price_mode == 'default' ? 'checked="checked"' : ''; ?>>
					<label class="radio" for="sample_price_default"><?php _e('product default price', 'woosample'); ?></label>
				</div>
				<div class="options_group">
					<input class="radio" id="sample_price_free" type="radio" value="free" name="sample_price_mode" <?php echo $sample_price_mode == 'free' ? 'checked="checked"' : ''; ?>>
					<label class="radio" for="sample_price_free"><?php _e('Free', 'woosample'); ?></label>
				</div>-->
				<div class="options_group">
					<input class="radio" id="sample_price_custom" type="radio" value="custom" name="sample_price_mode" <?php echo $sample_price_mode == 'custom' ? 'checked="checked"' : ''; ?> checked="checked">
					<label class="radio" for="sample_price_custom"><?php _e('Enable free samples by order', 'woosample'); ?></label>

				<!--	<p class="form-field sample_price_field clear">
						<label for="sample_price"><?php //_e('Set order total >', 'woosample'); ?></label>
						<input type="number" class="wc_input_price short" name="sample_price" id="sample_price" value="<?php //echo $sample_price; ?>" step="any" min="0">
					</p>-->
				</div>
           <!--     <div class="options_group">
                	<p class="form-field sample_enamble_field ">
                    <label for="sample_price_mode_order"><?php //_e('Enable free samples by order', 'woosample'); ?></label>
					<input class="checkbox" id="sample_price_mode_order" type="checkbox" value="1" name="sample_price_mode_order" <?php //echo $sample_price_mode_order == '1' ? 'checked="checked"' : ''; ?>>
					
                    </p>
					<p class="form-field sample_price_field clear">
						<label for="sample_price_order"><?php //_e('set order total >', 'woosample'); ?></label>
						<input type="number" class="wc_input_price short" name="sample_price_order" id="sample_price_order" value="<?php //echo $sample_price_order; ?>" step="100" min="100">
					</p>
				</div>-->
			</div>
			<?php
		}

      /*
       * build form to the administrator.
       */


      /**
       * updating the database post.
       */
      public function product_save_data($post_id, $post) {

        $sample_enamble = $_POST['sample_enamble'];
        if (empty($sample_enamble)) {
          delete_post_meta($post_id, 'sample_enamble');
        }else{
          update_post_meta($post_id, 'sample_enamble', true);
        }
		
		$sample_price_mode = $_POST['sample_price_mode'];
        update_post_meta($post_id, 'sample_price_mode', $sample_price_mode);
		$sample_price = $_POST['sample_price'];
        update_post_meta($post_id, 'sample_price', $sample_price);
		$sample_shipping_mode = $_POST['sample_shipping_mode'];
        update_post_meta($post_id, 'sample_shipping_mode', $sample_shipping_mode);
		$sample_shipping = $_POST['sample_shipping'];
        update_post_meta($post_id, 'sample_shipping', $sample_shipping);
        
       // $sample_price_mode_order = $_POST['sample_price_mode_order'];
       // if (empty($sample_price_mode_order)) {
       //   delete_post_meta($post_id, 'sample_price_mode_order');
       // }else{
       //   update_post_meta($post_id, 'sample_price_mode_order', true);
       // }
      //  $sample_price_order = $_POST['sample_price_order'];
       // update_post_meta($post_id, 'sample_price_order', $sample_price_order);
        //$videos = $_POST['_tab_sample'];
        //$length = count($videos);
        //foreach($videos as $key=>$video){
        //  if(!empty($video)) update_post_meta($post_id, 'wo_di_video_product'.$key, stripslashes($video));
        //  else delete_post_meta($post_id, 'wo_di_video_product'.$key);
        //}
        
      }

		public function product_sample_button() {
			global $post, $product;
			$is_sample = get_post_meta($post->ID, 'sample_enamble');
			if ($is_sample){
			?>
				<?php do_action('woocommerce_before_add_sample_to_cart_form'); ?>
				<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart sample" method="post" onsubmit="_gaq.push(['_trackEvent', 'free_sample', 'onsubmit', 'sample', '']);" enctype='multipart/form-data'>
				<?php do_action('woocommerce_before_add_sample_to_cart_button'); ?>
					<div class="single_variation_wrap  variations_button" style="">
					<?php $btnclass = apply_filters('sample_button_class', "single_add_to_cart_button button alt single_add_sample_to_cart_button btn btn-default"); ?>
	      	      	<button type="submit" class="<?php echo $btnclass; ?>"><?php echo  __( 'Add free sample to cart', 'woosample' ); ?></button>
	      	      	<? echo do_shortcode('[widgets_on_pages id="Sample Product Text"]'); ?>
	      	        <input type="hidden" name="sample" id="sample" value="true"/>
	      	        <input type="hidden" name="add-to-cart" id="sample_add_to_cart" value="<?php echo $product->id; ?>">
	      	        </div>
				<?php do_action('woocommerce_after_add_sample_to_cart_button'); ?>
				</form>
				<?php do_action('woocommerce_after_add_sample_to_cart_form'); ?>
			<?php
			}
		}
	  
		function enqueue_scripts() {
			global $pagenow, $wp_scripts;
			$plugin_url = untrailingslashit(plugin_dir_url(__FILE__));
			if ( ! is_admin() ) {
				wp_enqueue_script('woocommerce-sample', $plugin_url . '/js/woocommerce-sample.js', array('jquery'), '1.0', true);
			}
			/*
			if (is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' || 'edit-tags.php')) {
				// for admin enqueue
			}
			*/
		}
	  
      
    }//end of the class  
  }//end of the if, if the class exists

  /*
   * Instantiate plugin class and add it to the set of globals.
   */
  $woocommerce_sample_tab = new WooCommerce_Sample();

  $plugin = plugin_basename( __FILE__ );


 /**
  * Enqueue plugin style-file
  */
  function woosample_add_scripts() {
    // Respects SSL, style-admin.css is relative to the current file
    wp_register_style( 'woosample-styles', plugins_url('css/style-admin.css', __FILE__) );
    wp_register_script( 'woosample-scripts', plugins_url('js/woocommerce-sample.js', __FILE__), array('jquery') );
    wp_enqueue_style( 'woosample-styles' );
    wp_enqueue_script( 'woosample-scripts' );
  }
  add_action( 'admin_enqueue_scripts', 'woosample_add_scripts' );

  /**
  * Set up localization
  */
  function woosample_textdomain() {
    load_plugin_textdomain( 'woosample', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }
  add_action('plugins_loaded', 'woosample_textdomain');

?>