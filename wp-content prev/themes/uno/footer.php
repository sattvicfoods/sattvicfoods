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
	  <div class="container">

		<?php woo_footer_inside(); ?>
		<div class="col-2 left"><?php echo do_shortcode('[widgets_on_pages id="Footer bottom Left Side"]'); ?></div>
		<div class="col-2 right"><?php echo do_shortcode('[widgets_on_pages id="Footer bottom Right Side"]'); ?></div>		
		
	  </div>	

	</footer>

	<?php woo_footer_after(); ?>

	</div><!-- /#inner-wrapper -->

</div><!-- /#wrapper -->

<div class="fix"></div><!--/.fix-->
<script>
	(function($){

		var link = $('#woocommercemyaccountwidget-4 .woo-ma-register-link:contains("Register")');
		if ( link.length ) { link[0].href = link[0].href.slice(0, -1) + '?register=true'; }

	})(jQuery);
</script>
<?php wp_footer(); ?>
<?php woo_foot(); ?>
<script type="text/javascript">

	var url = window.location.href;
	var arr = url.split('?');
	if(arr[1]=='login=failed' && jQuery('.logout').length){
		jQuery('#my_account_header').addClass('hover');
	}
//	console.log(arr);

	jQuery("#woocommercemyaccountwidget-4").on('mouseover', function(){
		jQuery('#my_account_header').addClass('hover');
	});

	jQuery("#woocommercemyaccountwidget-4").on('mouseout', function(e){
		
		if(e.target.id != 'user_login' ) {
			jQuery('#my_account_header').removeClass('hover');
		}
	});

	jQuery('#billing_phone').attr('min','5');
	jQuery('#billing_phone').on('keyup', function(){
		jQuery(this).val(jQuery(this).val().replace (/[^0-9+]/, ''));
	});
</script>
</body>
</html>