<?php
/**
 * The Call to Action Sidebar
 * @package Circumference Lite
 * @since 1.0.4
 */

if ( ! is_active_sidebar( 'cta' ) ) {
	return;
}
?>

<aside id="cir-cta" style="background-color: <?php echo get_theme_mod( 'cta_bg', '#e08b8b' ); ?>; color: <?php echo get_theme_mod( 'cta_text', '#fff' ); ?>;" role="complementary">
    <div class="container">
        <div class="row">
           	<div class="col-md-12">
           		<?php dynamic_sidebar( 'cta' ); ?>
        	</div>
        </div>
    </div>
</aside>