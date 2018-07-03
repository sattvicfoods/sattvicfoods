<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>
	<div style="clear:both;"></div>
	<p id="tentative_date">Tentative in stock date: <span><?php echo get_post_meta( $post->ID, '_tentative_date', true ); ?></span></p>
	<div id="important_info">
		<ul class="left">
			<li class="noHover"><i class="fa fa-check" aria-hidden="true"></i>No cash on delivery</li>
			<li class="noHover"><i class="fa fa-check" aria-hidden="true"></i>Accepting international cards</li>
		</ul>
		<ul class="right">
			<li class="noHover"><i class="fa fa-check" aria-hidden="true"></i>Free shipping all India</li>
			<li class="noHover"><i class="fa fa-check" aria-hidden="true"></i>International shipping</li>
		</ul>
	</div>
	</div><!-- .summary -->
	<div style="clear:both;"></div>
        <div class="after-summary">
	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
        </div>
	<meta itemprop="url" content="<?php the_permalink(); ?>" />

	<?php 
		// var_dump ( sfoo_get_recipes_id_by_product_name( get_the_title($post->ID) ) );
		$recipes_id	= sfoo_get_recipes_id_by_product_name( get_the_title($post->ID) );
		if ($recipes_id) {
			$i = 0;
	?>
		<div class="sfoo_single_recipe_hero">
			<h3 class="sfoo_single_recipe_hero_title"><span>Popular recipes</span> with <?=get_the_title($post->ID)?></h3>
			<div class="woocommerce columns-4">
				<ul class="products">	
			<?php foreach ($recipes_id as $recipe_id) {?>
				<?php $recipe = get_post($recipe_id); ?>	
				<li class="product">
					
					<a class="sfoo_single_recipe_hero_link" href="<?=$recipe->guid?>">
						
						<?=get_the_post_thumbnail( $recipe_id, [300, 267] );?>
						<p class="woocommerce-loop-product__title">
							<?=$recipe->post_title?>
						</p>

					</a>
				
				</li>
				<?php 
					if ($i++ == 3) break; 
				?>

			<?php } ?>	
				</ul>
			</div>	
			<div class="sfoo_single_recipe_hero_more_recipes">
				<a href="/recipes/" class="sfoo_button" target="_blank">See all recipes</a>
			</div>
		</div>
	<?php
		}
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<script>
	(function(){

		var is_user_logged = document.body.classList.contains('logged-in');
		if ( !is_user_logged ) {
			
			var wl_btn = document.querySelector('.wl-button-wrap .wl-add-but');
			wl_btn.addEventListener('click', function(event){
				
				if (!document.getElementsByClassName('sfoo_wishlist_error_message').length) {

					var notice = document.createElement('p');
					notice.className = 'sfoo_wishlist_error_message';
					notice.innerHTML = 'Login/Registration required to Add to Wishlist';
					this.closest('.wl-button-wrap').appendChild(notice);

				} 
				
				event.preventDefault();
				event.stopImmediatePropagation();
			});

		}

	})();

</script>

<?php do_action( 'woocommerce_after_single_product' ); ?>