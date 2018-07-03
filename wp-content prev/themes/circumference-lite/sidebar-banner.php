<?php
/**
 * The Banner Sidebar
 * @package Circumference Lite
 * @since 1.0.5
 */
?>



<div class="container">
    <div class="row">
      <div class="col-md-12">
      
        <?php dynamic_sidebar( 'banner-short' ); ?>
                
      </div>
    </div>
</div>



<?php if ( ! dynamic_sidebar( 'banner-wide' ) ) : ?>

<?php if( get_theme_mod( 'hide_demobanner' ) == '') { ?>
	<img src="<?php echo get_template_directory_uri(); ?>/images/demo-banner.jpg" alt="<?php bloginfo( 'name' ); ?>" />
<?php } ?>

<?php endif; ?>