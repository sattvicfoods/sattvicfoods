<?php
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options;

 woo_footer_top();
 	woo_footer_before();
?>
	<footer id="footer" class="col-full">
	  <div class="footer-content">

		<?php woo_footer_inside(); ?>

		<!--<div id="copyright" class="col-left">
			
			<?php woo_footer_left(); ?>
		</div>-->
		<?php echo do_shortcode('[widgets_on_pages id="custom_menu"]'); ?>
		<?php echo do_shortcode('[widgets_on_pages id="pay"]'); ?>
		<?php echo do_shortcode('[widgets_on_pages id="share"]'); ?>
		<div id="credit" class="col-right">
			<?php woo_footer_right(); ?>
		</div>
	  </div>	

	</footer>

	<?php woo_footer_after(); ?>

	</div><!-- /#inner-wrapper -->

</div><!-- /#wrapper -->

<div class="fix"></div><!--/.fix-->

<?php wp_footer(); ?>
<?php woo_foot(); ?>
</body>
</html>