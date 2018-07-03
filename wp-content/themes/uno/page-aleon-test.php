<?php
/**
 * Page Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a page ('page' post_type) unless another page template overrules this one.
 * @link http://codex.wordpress.org/Pages
 *
 * @package WooFramework
 * @subpackage Template
 */

get_header();
?>
       
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
    	<div id="main-sidebar-container">    

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">  

<?php
	woo_loop_before();
	
	if (have_posts()) { $count = 0;
		while (have_posts()) { the_post(); $count++;
			woo_get_template_part( 'content', 'page' ); // Get the page content template file, contextually.
		}
	}
	
	woo_loop_after();
?>     
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>

	<h2>RECIPE HERO</h2>
	<?php

	// var_dump( get_post_types(  ) );
	/*

		array(28) { ["post"]=> string(4) "post" ["page"]=> string(4) "page" ["attachment"]=> string(10) "attachment" ["revision"]=> string(8) "revision" ["nav_menu_item"]=> string(13) "nav_menu_item" ["custom_css"]=> string(10) "custom_css" ["customize_changeset"]=> string(19) "customize_changeset" ["oembed_cache"]=> string(12) "oembed_cache" ["scheduled-action"]=> string(16) "scheduled-action" ["paypal_transaction"]=> string(18) "paypal_transaction" ["product"]=> string(7) "product" ["product_variation"]=> string(17) "product_variation" ["shop_order"]=> string(10) "shop_order" ["shop_order_refund"]=> string(17) "shop_order_refund" ["shop_coupon"]=> string(11) "shop_coupon" ["shop_webhook"]=> string(12) "shop_webhook" ["recipe"]=> string(6) "recipe" ["wishlist"]=> string(8) "wishlist" ["postman_sent_mail"]=> string(17) "postman_sent_mail" ["turbo-sidebar-cpt"]=> string(17) "turbo-sidebar-cpt" ["carts"]=> string(5) "carts" ["mt_pp"]=> string(5) "mt_pp" ["ml-slider"]=> string(9) "ml-slider" ["ml-slide"]=> string(8) "ml-slide" ["wc_order_status"]=> string(15) "wc_order_status" ["wc_order_email"]=> string(14) "wc_order_email" ["slide"]=> string(5) "slide" ["follow_up_email"]=> string(15) "follow_up_email"}

	*/

	// var_dump(get_posts(['post_type' => 'recipe'])[0]);
	$sfoo_recipes = get_posts(['numberposts' => -1, 'post_type' => 'recipe']);

	// var_dump(get_taxonomies(['object_type' => ['recipe']]));
	// $equipment = get_post_meta ( $sfoo_recipes[0]->ID, '_recipe_hero_detail_equipment', false );
	// var_dump($equipment);

	// $ingredients = get_post_meta( $sfoo_recipes[0]->ID, '_recipe_hero_ingredients_group', true );
	// $ingredients = array_filter( $ingredients );
	// var_dump($ingredients);

	$meta = get_post_meta( $sfoo_recipes[0]->ID );
	// var_dump($meta);
	// var_dump($sfoo_recipes[0]);
	var_dump( get_the_terms( $sfoo_recipes[0]->ID, 'recipe_products' )[0]->name );
	echo '<br>';
	echo $sfoo_recipes[0]->post_title;
	echo '<br>';
	echo $sfoo_recipes[0]->guid;
	echo '<br>';
	echo get_the_post_thumbnail( $sfoo_recipes[0]->ID, [300, 267] );

	echo '<hr><hr><hr>';

	foreach ($sfoo_recipes as $recipe) {
		echo $recipe->ID . ': ';
		$ingredients = get_post_meta( $recipe->ID, '_recipe_hero_ingredients_group', true );
		foreach ($ingredients as $ingredient) {
			echo $ingredient['name'] . ', ';
		}
		echo '<br>';
	}
	echo '<hr><hr><hr>';

	var_dump( sfoo_get_recipes_id_by_product_name('Coconut Sugar') );

echo '<hr><hr><hr>';

$str = '{
"reference_no":"107347157558",
"order_no":"108055_180322",
"order_currncy":"INR",
"order_amt":1550.0,
"order_date_time":"2018-03-22 17:25:10.083",
"order_bill_name":"Yogita Pranav",
"order_bill_address":"M1, Townsend ( Near Presidency School)",
"order_bill_zip":"560064",
"order_bill_tel":"9845055886",
"order_bill_email":"yogitajoshi27@gmail.com",
"order_bill_country":"India",
"order_ship_name":"Yogita Pranav",
"order_ship_address":"M1, Townsend ( Near Presidency School)",
"order_ship_country":"IN",
"order_ship_tel":"",
"order_bill_city":"Bengaluru",
"order_bill_state":"KA",
"order_ship_city":"Bengaluru",
"order_ship_state":"KA",
"order_ship_zip":"560064",
"order_ship_email":"",
"order_notes":"",
"order_ip":"117.194.59.48",
"order_status":"Successful",
"order_fraud_status":"NR",
"order_status_date_time":"2018-03-22 17:27:31.087",
"order_capt_amt":0.0,"order_card_name":"Visa",
"order_delivery_details":"",
"order_fee_perc":1.99,
"order_fee_perc_value":30.85,
"order_fee_flat":3.0,
"order_gross_amt":1550.0,
"order_discount":0.0,
"order_tax":0.0,
"order_bank_ref_no":"076675",
"order_gtw_id":"UNI",
"order_bank_response":"SUCCESS",
"order_option_type":"OPTCRDC",
"order_TDS":0.0,
"order_device_type":"PC",
"param_value1":"",
"param_value2":"",
"param_value3":"",
"param_value4":"",
"param_value5":"",
"error_desc":"",
"status":0,
"error_code":""}';

var_dump(json_decode($str));


	?>


    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>