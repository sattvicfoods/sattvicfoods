<?php
/**
 * Sidebar Template
 *
 * If a `primary` widget area is active and has widgets, display the sidebar.
 *
 * @package WooFramework
 * @subpackage Template
 */

global $post, $wp_query, $woo_options;

$settings = array(
				'portfolio_layout' => 'one-col'
				);

$settings = woo_get_dynamic_values( $settings );

// Reset Main Query
wp_reset_query();

$layout = woo_get_layout();

// Cater for custom portfolio gallery layout option.
if ( is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) ) {
	if ( '' != $settings['portfolio_layout'] ) { $layout = $settings['portfolio_layout']; }
}

if ( 'one-col' != $layout ) {
	if ( woo_active_sidebar( 'primary' ) ) {
		woo_sidebar_before();

?>

<aside id="sidebar">
<?php 
if ( is_product() ) {  ?>
    <?php echo  get_product_search_form(); ?>
	<p class="sidebar-title">Related Products</p>
	<?php echo do_shortcode('[widgets_on_pages id="Single Product Page"]');  ?>
<?php } elseif (is_cart()) { ?>
    <?php echo  get_product_search_form(); ?>
	<p class="sidebar-title">YOU MAY ALSO LIKE</p>
	<?php echo do_shortcode('[widgets_on_pages id="Cart Page"]');  ?>
<?php } elseif (is_checkout()) { ?>
	<?php echo  get_product_search_form(); ?>
	<p class="sidebar-title">IMPORTANT to know</p>
	<?php echo do_shortcode('[widgets_on_pages id="Checkout Page"]');  ?>
<?php } else { ?>
<?php echo  get_product_search_form(); ?>
<h1 class="page-title sidebar-title">need to know</h1>
<?php
	woo_sidebar_inside_before();
	woo_sidebar( 'primary' );
	woo_sidebar_inside_after();
?>
<?php } ?>
</aside><!-- /#sidebar -->
<?php
		woo_sidebar_after();
	}
}
?>