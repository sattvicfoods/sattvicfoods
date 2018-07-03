<?php
/**
 * Template Name: Homepage
 *
 * Here we setup all logic and HTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
get_header(); ?>

<div class="slider">
  <div class="slide1_1 slide">
  	<div class="text_slide">
	  	<h3>GLUTEN-FREE SNACKS</h3>
	  	<a href="/product-category/more/gf_snacks/" target="_parent" class="shop_now"><button class=" animated zoomIn delay2 duration3 eds-on-scroll ">Shop Now</button></a>
  	</div>
  </div>
  <div class="slide1_2 slide">
  	<div class="text_slide">
	  	<h3>MADE TO ORDER</h3>
	  	<a href="/product-category/cosmetics/madetoorder/" target="_parent" class="shop_now"><button class=" animated zoomIn delay2 duration3 eds-on-scroll ">Shop Now</button></a>
  	</div>
  </div>
  <div class="slide1 slide">
  	<div class="text_slide">
	  	<h3>Free shipping</h3>
	  	<p class=" animated slideInUp duration3 eds-on-scroll">to all pincodes in India</p>
	  	<a href="/shop" target="_parent" class="shop_now" onclick="_gaq.push(['_trackEvent', 'Shop Now', 'onclick', 'shop_now', '']);"><button class=" animated zoomIn delay2 duration3 eds-on-scroll ">Shop Now</button></a>
  	</div>
  </div>
  <div class="slide2 slide">
  	<div class="text_slide">
	  	<h3>Bio-degradable packaging</h3>
		<p class=" animated slideInUp duration6 eds-on-scroll">*Available in limited sizes</p>
	  	<a href="/shop" target="_parent" class="shop_now" onclick="_gaq.push(['_trackEvent', 'Shop Now', 'onclick', 'shop_now', '']);"><button>Shop Now</button></a>
  	</div>
  </div>
  <div class="slide3 slide">
  	<div class="text_slide">
	  	<h3>We Ship to all<br>Pincodes in India</h3>
	  	<a href="/shop" target="_parent" class="shop_now" onclick="_gaq.push(['_trackEvent', 'Shop Now', 'onclick', 'shop_now', '']);"><button>Shop Now</button></a>
  	</div>
  </div>
  <div class="slide4 slide">
  	<div class="text_slide">
	  	<h3>Get FREE Samples!</h3>
        <p class=" animated slideInUp duration3 eds-on-scroll">* Simply click Add free sample<br>to cart on applicable products</p>
	  	<a href="/shop" target="_parent" class="shop_now" onclick="_gaq.push(['_trackEvent', 'Shop Now', 'onclick', 'shop_now', '']);"><button>Shop Now</button></a>
  	</div>
  </div>
  <div class="slide5 slide">
  	<div class="text_slide">
	  	<h3>Now shipping Worldwide</h3>
        <p class=" animated slideInUp duration3 eds-on-scroll">* DHL upgrade offered at additional cost</p>
	  	<a href="/shop" target="_parent" class="shop_now" onclick="_gaq.push(['_trackEvent', 'Shop Now', 'onclick', 'shop_now', '']);"><button>Shop Now</button></a>
  	</div>
  </div>
</div>

    <div id="content">

    	<?php woo_main_before(); ?>



		<?php do_action( 'homepage' ); ?>
		<?php woo_main_after(); ?>
<div class="custom_recipes">
<h1 class="section-title"><a href="/recipes" title="Recipes">Recipes</a></h1>
<?php

	$sfoo_recipes = get_posts(['numberposts' => 2, 'post_type' => 'recipe']);
		
	foreach ($sfoo_recipes as $sfoo_recipe) {
		$author_id = $sfoo_recipe->post_author;
		$sfoo_content = trim($sfoo_recipe->post_content);
		if (strlen($sfoo_content) > 126) {
			$sfoo_content = substr($sfoo_content, 0, 126) . '...';
		}

		?>
		
		<div class="post">
			<h1 class="recipe-archive-title entry-title post-title" itemprop="name">
				<a href="<?=$sfoo_recipe->guid?>" title="<?=$sfoo_recipe->post_title?>" rel="bookmark"><?=$sfoo_recipe->post_title?></a>
			</h1>
			<div class="recipe-archive-meta">
				<div class="author">
					<span class="dashicons dashicons-admin-users"></span> 
					<a href="<?=get_author_posts_url( $author_id )?>" title="Posts by <?=get_the_author_meta( 'display_name', $author_id )?>" rel="author"><?=get_the_author_meta( 'display_name', $author_id )?></a>	
				</div>
			</div>
			<p><?=$sfoo_content?></p>
			<span class="read-more"><a class="button" href="<?=$sfoo_recipe->guid?>" rel="bookmark" title="<?=$sfoo_recipe->post_title?>">Read More</a></span>
		</div>

		<?php	
	}

?>

</div>

    </div><!-- /.content -->
<?php get_footer(); ?>