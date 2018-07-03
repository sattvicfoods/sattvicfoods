<?php

/**
 * Social bar group
 * @package Circumference Lite
 * @since 1.0.4
 */
?>

<?php if( get_theme_mod( 'hide_social' ) == '') { ?>
	<div id="socialbar">
		<?php $options = get_theme_mods();								
		echo '<div id="social-icons">';										
		if (!empty($options['twitter_uid'])) echo '<a title="Twitter" href="' . $options['twitter_uid'] . '" target="_blank"><div id="twitter" class="icomoon icon-twitter"></div></a>';
		if (!empty($options['facebook_uid'])) echo '<a title="Facebook" href="' . $options['facebook_uid'] . '" target="_blank"><div id="facebook" class="icomoon icon-facebook3"></div></a>';
		if (!empty($options['google_uid'])) echo '<a title="Google+" href="' . $options['google_uid'] . '" target="_blank"><div id="google" class="icomoon icon-google-plus2"></div></a>';			
		if (!empty($options['linkedin_uid'])) echo '<a title="Linkedin" href="' . $options['linkedin_uid'] . '" target="_blank"><div id="linkedin" class="icomoon icon-linkedin"></div></a>';
		if (!empty($options['pinterest_uid'])) echo '<a title="Pinterest" href="' . $options['pinterest_uid'] . '" target="_blank"><div id="pinterest" class="icomoon icon-pinterest"></div></a>';
		if (!empty($options['flickr_uid'])) echo '<a title="Flickr" href="' . $options['flickr_uid'] . '" target="_blank"><div id="flickr" class="icomoon icon-flickr"></div></a>';
		if (!empty($options['youtube_uid'])) echo '<a title="Youtube" href="' . $options['youtube_uid'] . '" target="_blank"><div id="youtube" class="icomoon icon-youtube"></div></a>';
		if (!empty($options['vimeo_uid'])) echo '<a title="Vimeo" href="' . $options['vimeo_uid'] . '" target="_blank"><div id="vimeo" class="icomoon icon-vimeo2"></div></a>';		
		if (!empty($options['instagram_uid'])) echo '<a title="Instagram" href="' . $options['instagram_uid'] . '" target="_blank"><div id="instagram" class="icomoon icon-instagram"></div></a>';		
		if (!empty($options['reddit_uid'])) echo '<a title="Reddit" href="' . $options['reddit_uid'] . '" target="_blank"><div id="reddit" class="icomoon icon-reddit"></div></a>';
		if (!empty($options['picassa_uid'])) echo '<a title="picassa" href="' . $options['picassa_uid'] . '" target="_blank"><div id="picassa" class="icomoon icon-picassa2"></div></a>';
                if (!empty($options['cart_uid'])) echo '<a title="picassa" href="' . $options['cart_uid'] . '" target="_blank"><div id="Cart" class="icomoon icon-cart"></div></a>';
		if (!empty($options['stumbleupon_uid'])) echo '<a title="stumbleupon" href="' . $options['stumbleupon_uid'] . '" target="_blank"><div id="stumbleupon" class="icomoon icon-stumbleupon2"></div></a>';	
		if (!empty($options['wp_uid'])) echo '<a title="WordPress" href="' . $options['wp_uid'] . '" target="_blank"><div id="wordpress" class="icomoon icon-wordpress2"></div></a>';	
		if (!empty($options['rss_uid'])) echo '<a title="rss" href="' . $options['rss_uid'] . '" target="_blank"><div id="rss" class="icomoon icon-feed2"></div></a>';		 
		echo '</div>'		
		?>	
	</div>
<?php } ?>