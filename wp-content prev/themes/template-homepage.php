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
    <div id="content">
    	<?php woo_main_before(); ?>
<?php 
    echo do_shortcode("[metaslider id=650]"); 
?>

 <form role="search" method="get" id="searchform_home" action="<?php echo home_url( '/' ) ?>" >	<center><label class="screen-reader-text" for="s"></label>	<input type="text" value="" name="s" id="s" placeholder="Search">	<button type="submit" id="searchsubmit" class="fa fa-search submit" name="submit" value="Search"></button></center></form>

<p style="text-align:center; padding:10px 0 0 0; font-weight:300;font-size:1.414em;color:#000000; font-family:400 1em/1.45 "Open Sans",sans-serif;">Sattvic Foods was born out of a Promise for Quality and Customer Service<BR>
We offer the largest selection of Gluten-free, Vegan and Raw foods for every need<BR> 
We only sell the most premium quality available!<BR>
Be assured that your complete satisfaction is our only priority</p>
<div class="shop_now"><a href=" http://sattvicfoods.in/shop" target="_parent"><button>Shop Now</button></a></div>





		<?php do_action( 'homepage' ); ?>
		<?php woo_main_after(); ?>
<div class="custom_recipes">
<h1 class="section-title"><a href="https://sattvicfoods.in/recipes" title="Recipes">Recipes</a></h1>
	<div class="post">
		<h1 class="recipe-archive-title entry-title post-title" itemprop="name">
			<a href="https://sattvicfoods.in/recipe/banana-chocolate-chip-oat-flour-pancakes/" title="Banana Chocolate Chip Oat Flour Pancakes" rel="bookmark">Banana Chocolate Chip Oat Flour Pancakes</a>
		</h1>
		<div class="recipe-archive-meta">
			<div class="author">
				<span class="dashicons dashicons-admin-users"></span> 
				<a href="https://sattvicfoods.in/author/lorraine-daly/" title="Posts by Lorraine Daly" rel="author">Lorraine Daly</a>	
			</div>
		</div>
		<p>Gluten free oat flour pancakes are simple, quick, easy to digest and are bursting with flavour. These pancakes are filled with...</p>
		<span class="read-more"><a class="button" href="https://sattvicfoods.in/recipe/banana-chocolate-chip-oat-flour-pancakes/" rel="bookmark" title="Banana Chocolate Chip Oat Flour Pancakes">Read More</a></span>
	</div>
	<div class="post">
		<h1 class="recipe-archive-title entry-title post-title" itemprop="name">
			<a href="https://sattvicfoods.in/recipe/apple-bircher-museli/" title="Overnight Apple Bircher Museli" rel="bookmark">Overnight Apple Bircher Museli</a>
		</h1>
		<div class="recipe-archive-meta">
			<div class="author">
				<span class="dashicons dashicons-admin-users"></span>
				<a href="https://sattvicfoods.in/author/lorraine-daly/" title="Posts by Lorraine Daly" rel="author">Lorraine Daly</a>
			</div>
		</div>
		<p>This is a pre-prepared breakfast recipe that saves you time and effort in the mornings. It is made up of nuts, seeds, oats...</p>
		<span class="read-more"><a class="button" href="https://sattvicfoods.in/recipe/apple-bircher-museli/" rel="bookmark" title="Banana Chocolate Chip Oat Flour Pancakes">Read More</a></span>
	</div>
</div>

    </div><!-- /.content -->
<?php get_footer(); ?>