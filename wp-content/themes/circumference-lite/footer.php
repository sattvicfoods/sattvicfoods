<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package Circumference Lite
 * @since 1.0.1
 */
?>



</div><!-- #cir-content-wrapper-->

<?php get_sidebar( 'bottom' ); ?>

<footer id="cir-footer-wrapper" style="color: <?php echo get_theme_mod( 'footertext', '#818181' ); ?>;">
    
    <div id="cir-footer-menu">
    	<?php wp_nav_menu( array( 'theme_location' => 'footer', 'fallback_cb' => false, 'depth' => '1', 'container' => false, 'menu_id' => 'footer-menu' ) ); ?>
    </div>
                
    <div class="copyright">
    <?php if(get_theme_mod( 'copyright' ) == ''){ ?>
    	<span>
            <a href="<?php echo esc_url('https://www.styledthemes.com/themes/circumference-lite/');?>"><?php esc_html_e('Circumference Lite','circumference-lite'); ?></a>
                <?php echo esc_html__( 'Wordpress Theme by','circumference-lite');?> 
            <a href="<?php echo esc_url('https://www.styledthemes.com/');?>">
                <?php echo esc_html__( 'Styled Themes','circumference-lite');?>
            </a>
        </span>
    <?php }else{ ?>	
    	<?php esc_attr_e('Copyright &copy;', 'circumference-lite'); ?> <?php echo date_i18n( date('Y') ); ?> <?php echo get_theme_mod( 'copyright', 'Your Name' ); ?>. <?php esc_attr_e('All rights reserved.', 'circumference-lite'); ?>
    <?php } ?>
    </div>
</footer>

</div><!-- #cir-wrapper -->

	<?php wp_footer(); ?>
</body>
</html>