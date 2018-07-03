<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Theme Actions
 *
 * This is where theme functions are hooked into the appropriate hooks / filters.
 *
 * @since 	1.0.0
 * @author 	WooThemes
 */
 
// Run Theme Setup Functions
include_once( get_stylesheet_directory() . '/inc/admin-func.php');
include_once( get_stylesheet_directory() . '/inc/rest-admin.php');
include_once( get_stylesheet_directory() . '/inc/woo-func.php');
 
// Load specific admin js
add_action( 'admin_enqueue_scripts', 'orders_report_js' );
function orders_report_js()
{
    wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/js/admin.js', array( 'jquery' ) );
}

// Load language file
add_action( 'after_setup_theme', 'woo_child_theme_textdomain' );

// Enqueue Styles
add_action( 'wp_enqueue_scripts', 'woo_child_enqueue', 30 );

// Move things around
add_action( 'woo_main_before', 'woo_move_things_around', 10 );

// Homepage
add_action( 'homepage', 'woo_display_hero', 10 );
add_action( 'homepage', 'woo_display_featured_products', 20 );
add_action( 'homepage', 'woo_display_recent_products', 30 );
add_action( 'homepage', 'woo_display_recent_posts', 40 );

// Add Roboto Condensed to Google Fonts array
add_action( 'init', 'woo_add_googlefonts', 20 );

// Output Custom Fonts
add_action( 'wp_head', 'woo_custom_fonts_output', 10 );

// Setting overrides
add_filter( 'option_woo_options', 'woo_custom_theme_overrides' );

/**
 * Setup My Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 *
 */
function woo_child_theme_textdomain() {
	load_child_theme_textdomain( 'uno',  get_stylesheet_directory() . '/lang' );
}

/**
 * Child Theme Enqueues
 *
 * Enqueues Custom Fonts and Stylesheet files.
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_child_enqueue() {
	// Load Theme Stylesheet
	wp_enqueue_style( 'uno', get_stylesheet_directory_uri() . '/css/uno.css' );
	wp_enqueue_style( 'wbh-aditional-styles', get_stylesheet_directory_uri() . '/css/aditional.css' ); /*Don't remove!!*/
	wp_enqueue_script( 'uno-general', get_stylesheet_directory_uri() . '/js/general.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery.matchHeight', get_stylesheet_directory_uri() . '/js/jquery.matchHeight.js', array( 'jquery' ) );
	wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ) );

}

/**
 * Move things around
 *
 * Moves elements from their default location
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_move_things_around() {
	// Remove Sidebar from Homepage template
	if ( is_page_template( 'template-homepage.php' ) ) {
		remove_action( 'woo_main_after', 'woocommerce_get_sidebar', 10 );
	}
}

/**
 * Custom Fonts
 *
 * Add a font to the $google_fonts variable
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_add_googlefonts () {
    global $google_fonts;
    $google_fonts[] = array( 'name' => 'Roboto Condensed', 'variant' => ':l,r,b,i,bi');
}

/**
 * Theme Overrides
 *
 * Updates Theme Options dynamically to match the styling of the Child Theme.
 *
 * @since  	1.0.0
 * @return 	array
 * @author 	WooThemes
 */
function woo_custom_theme_overrides( $options ) {
	$roboto = 'Roboto Condensed';
	$open_sans = 'Open Sans';

	if ( !isset( $options['woo_child_theme_overrides'] ) ) {
		$options['woo_child_theme_overrides'] = 'true';
	}

	if ( 'false' != $options['woo_child_theme_overrides'] ) {

		// Enable Custom Styling
		$options['woo_style_disable'] = 'true';

		// Misc
		$options['woo_font_text'] = array( 'size' => '1', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#555555' );
		$options['woo_font_h1'] = array( 'size' => '4', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );
		$options['woo_font_h2'] = array( 'size' => '2.8', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );
		$options['woo_font_h3'] = array( 'size' => '2', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );
		$options['woo_font_h4'] = array( 'size' => '1.4', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );
		$options['woo_font_h5'] = array( 'size' => '1', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );
		$options['woo_font_h6'] = array( 'size' => '1', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );

		// Body
		$options['woo_style_bg'] = '#F9F9F9';

		// Top Navigation
		$options['woo_top_nav_font'] = array( 'size' => '.9', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#999' );

		// Header
		$options['woo_font_logo'] = array( 'size' => '1.4', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#000000' );
		$options['woo_font_desc'] = array( 'size' => '0.9', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#999999' );

		// Primary Navigation
		$options['woo_nav_font'] = array( 'size' => '1', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#ffffff' );
		$options['woo_nav_bg'] = '#6ec095';
		$options['woo_nav_hover_bg'] = '#4C5567';

		// Posts / Pages
		$options['woo_font_post_title'] = array( 'size' => '2', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );
		$options['woo_font_post_meta'] = array( 'size' => '.9', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_font_post_text'] = array( 'size' => '1', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#555555' );
		$options['woo_font_post_more'] = array( 'size' => '1', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#999' );
		$options['woo_pagenav_font'] = array( 'size' => '1', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#555555' );

		// Post Author
		$options['woo_post_author_border_top'] = array( 'width' => '0', 'style' => 'solid', 'color' => '' );
		$options['woo_post_author_border_bottom'] = array( 'width' => '0', 'style' => 'solid', 'color' => '' );
		$options['woo_post_author_border_lr'] = array( 'width' => '0', 'style' => 'solid', 'color' => '' );
		$options['woo_post_author_border_radius'] = '0px';
		$options['woo_post_author_bg'] = '#ffffff';

		// Archives
		$options['woo_archive_header_font'] = array( 'size' => '1.5', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );

		// Widgets
		$options['woo_widget_font_title'] = array( 'size' => '1.4', 'unit' => 'em', 'face' => $roboto, 'style' => '300', 'color' => '#222222' );
		$options['woo_widget_font_text'] = array( 'size' => '1', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#555555' );
		$options['woo_widget_title_border'] = 0;

		// Footer
		$options['woo_footer_font'] = array( 'size' => '1', 'unit' => 'em', 'face' => $open_sans, 'style' => 'normal', 'color' => '#dddddd' );
		$options['woo_footer_bg'] = '#4C5567';

		// Full Width
		$options['woo_header_full_width'] = 'true';
		$options['woo_footer_full_width'] = 'true';
		$options['woo_foot_full_width_widget_bg'] = '#4C5567';
		$options['woo_footer_full_width_bg'] = '#4C5567';
		$options['woo_footer_border_top'] = array( 'width' => 0, 'style' => 'solid', 'color' => '#00000' );
	}

	return $options;
}

/**
 * Add Custom Options
 *
 * Add custom options for this Child Theme.
 *
 * @since  	1.0.0
 * @return 	array
 * @author 	WooThemes
 */
function woo_options_add( $options ) {

	$shortname = 'woo';

	$options[] = array( 'name' => __( 'Uno', 'uno' ),
						'icon' => 'misc',
					    'type' => 'heading');

	$options[] = array( "name" => __( 'Use Child Theme Custom Overrides', 'uno' ),
						"desc" => __( 'Disable this option if you\'d like to setup your own typography and layout settings.', 'uno' ),
						"id" => $shortname."_child_theme_overrides",
						"std" => "true",
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Hero - Custom Background', 'uno' ),
						"desc" => __( 'Upload a background image for your hero section, or specify an image URL directly.', 'uno' ),
						"id" => $shortname."_hero_bg",
						"std" => "",
						"type" => "upload");

	$options[] = array( "name" => __( 'Hero - Title', 'uno' ),
						"desc" => __( 'Enter the Hero title.', 'uno' ),
						"id" => $shortname."_hero_title",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __( 'Hero - Title Font Style', 'uno' ),
						"desc" => __( 'Select typography for the hero title.', 'uno' ),
						"id" => $shortname."_hero_title_font",
						"std" => array('size' => '2.4','unit' => 'em', 'face' => 'Oswald', 'style' => 'normal', 'color' => '#fff'),
						"type" => "typography");

	$options[] = array( "name" => __( 'Hero - Message', 'uno' ),
						"desc" => __( 'Enter the Hero message.', 'uno' ),
						"id" => $shortname."_hero_message",
						"std" => "",
						"type" => "textarea");

	$options[] = array( "name" => __( 'Hero - Message Font Style', 'uno' ),
						"desc" => __( 'Select typography for the hero message.', 'uno' ),
						"id" => $shortname."_hero_message_font",
						"std" => array('size' => '1.2','unit' => 'em', 'face' => 'Open Sans','style' => 'thin','color' => '#fff'),
						"type" => "typography");

	$options[] = array( "name" => __( 'Hero - Button', 'uno' ),
						"desc" => __( 'Enter the Hero button text.', 'uno' ),
						"id" => $shortname."_hero_button",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __( 'Hero - Button Link', 'uno' ),
						"desc" => __( 'Enter the Hero button text.', 'uno' ),
						"id" => $shortname."_hero_button_link",
						"std" => "",
						"type" => "text");

	return $options;
}

function woo_custom_fonts_output() {
	global $woo_options;
	$output = '';

	if ( isset( $woo_options['woo_hero_bg'] ) && '' != $woo_options['woo_hero_bg'] ) {
		$output .= '.hero { background-image: url(' . esc_url( $woo_options['woo_hero_bg'] ) . '); }' . "\n";
	}

	if ( isset( $woo_options['woo_hero_title_font'] ) ) {
		$output .= '.hero .section-title { ' . woo_generate_font_css( $woo_options['woo_hero_title_font'], 1.2 ) . ' }' . "\n";
	}

	if ( isset( $woo_options['woo_hero_message_font'] ) ) {
		$output .= '.hero p { ' . woo_generate_font_css( $woo_options['woo_hero_message_font'], 1.45 ) . ' }' . "\n";
	}

	if ( isset( $output ) && '' != $output ) {
		$output = "\n<!-- Woo Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n<!-- /Woo Custom Styling -->\n\n";
		echo $output;
	}
}

/**
 * Display Hero.
 *
 * Displays products which have been set as “featured” using the WooCommerce featured_products shortcode.
 *
 * @since  	1.0.0
 * @return 	void
 * @uses  	do_shortcode()
 * @link 	//www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_hero() {

/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */

 	$settings = array(
					'hero_title'		=> '',
					'hero_message' 		=> '',
					'hero_button' 		=> '',
					'hero_button_link' 	=> ''
				);

	$settings = woo_get_dynamic_values( $settings );

	if ( $settings['hero_title'] != '' || $settings['hero_message'] != '' || $settings['hero_button'] != '' || $settings['hero_button_link'] != '' ) {
	?>
<div class="only_mobile" id="shopallwrap">
<a href="/shop/" id="shop_all" title="SHOP ALL">SHOP ALL</a>
</div>

		<section class="hero home-section">
			<div class="col-full">
				<div class="hero-container">
					<?php if ( isset( $settings['hero_title'] ) && '' != $settings['hero_title'] ): ?>
						<h1 class="section-title"><span><?php echo stripslashes( esc_attr( $settings['hero_title'] ) ); ?></span></h1>
					<?php endif; ?>

					<?php if ( isset( $settings['hero_message'] ) && '' != $settings['hero_message'] ): ?>
						<?php echo wpautop( stripslashes( esc_attr( $settings['hero_message'] ) ) ); ?>
					<?php endif; ?>

					<?php if ( isset( $settings['hero_button'] ) && '' != $settings['hero_button'] ): ?>
						<div class="cta">
							<a class="button" href="<?php echo esc_url( $settings['hero_button_link'] ); ?>"><?php echo stripslashes( esc_attr( $settings['hero_button'] ) ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php
	}
}

/**
 * Display Featured Products.
 *
 * Displays products which have been set as “featured” using the WooCommerce featured_products shortcode.
 *
 * @since  	1.0.0
 * @return 	void
 * @uses  	do_shortcode()
 * @link 	//www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_featured_products() {
?>
	<?php if ( is_woocommerce_activated() ): ?>

	<section class="featured-products home-section">
		<div class="col-full">
			<h1 class="section-title"><?php _e( 'Featured Products', 'uno' ); ?></h1>
			<?php
				$featured_products_limit 		= apply_filters( 'woo_template_featured_products_limit', $limit = 8 );
				$featured_products_columns 		= apply_filters( 'woo_template_featured_products_columns', $columns = 4 );
				echo do_shortcode( '[featured_products per_page="' . $featured_products_limit . '" columns="' . $featured_products_columns . '"]' );
			?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

/**
 * Display Recent Products.
 *
 * Displays recent products using the WooCommerce recent_products shortcode.
 *
 * @since  	1.0.0
 * @return 	void
 * @uses  	do_shortcode()
 * @link 	//www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_recent_products() {
?>
	<?php if ( is_woocommerce_activated() ): ?>
	<section class="recent-products home-section">
		<div class="col-full">
			<h1 class="section-title"><?php _e( 'Recent Products', 'uno' ); ?></h1>
			<?php
				$recent_products_limit 		= apply_filters( 'woo_template_recent_products_limit', $limit = 8 );
				$recent_products_columns 	= apply_filters( 'woo_template_recent_products_columns', $columns = 4 );
				echo do_shortcode( '[recent_products per_page="' . $recent_products_limit . '" columns="' . $recent_products_columns . '"]' );
			?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

/**
 * Display Recent Posts.
 *
 * Displays recent blog posts.
 *
 * @since  	1.0.0
 * @return 	void
 * @uses  	WP_Query()
 * @link 	//www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_recent_posts() {

/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */

 	$settings = array(
					'thumb_w' => 700,
					'thumb_h' => 700,
					'thumb_align' => 'alignleft'
					);

	$settings = woo_get_dynamic_values( $settings );

?>
	<section class="home-section">
<div class="only_mobile" id="shopallwrap">
<a href="/shop/" id="shop_all" title="SHOP ALL">SHOP ALL</a>	
</div>
		<div class="col-full recent_posts_custom">

			<section class="recent-posts col-left">

				<h1 class="section-title"><?php _e( 'Recent Posts', 'uno' ); ?></h1>
				<?php
					$args = array(
								'posts_per_page' => 2,
								'ignore_sticky_posts' => 1
							);

					$recent_posts = new WP_Query( $args );
				?>

				<?php if ( $recent_posts->have_posts() ) : ?>
					<?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
						<article <?php post_class(); ?>>

							<header class="post-header">
								<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
								<p class="meta">
									<span class="post-date"><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . __( ' ago', 'uno' ); ?></span>
									<span class="categories"><?php the_category( ', ' ); ?></span>
								</p>
							</header>

							<p><?php echo woo_text_trim( get_the_excerpt(), 20 ); ?></p>

							<footer class="post-more">
								<span class="read-more"><a class="button" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php _e( 'Read More', 'uno' ); ?></a></span>
							</footer>

						</article><!-- /.post -->
					<?php endwhile; ?>
				<?php endif; ?>

			</section>

			<section class="recent-comments col-right">

				<h1 class="section-title"><?php _e( 'Recent Comments', 'uno' ); ?></h1>

				<ul>
					<?php
							$comments = get_comments( array( 'number' => 3, 'status' => 'approve', 'post_status' => 'publish' ) );
						if ( $comments ) {
							foreach ( (array) $comments as $comment) {
							$post = get_post( $comment->comment_post_ID );
							?>
								<li class="recentcomments">
									<?php echo get_avatar( $comment, 55 ); ?>
									<a href="<?php echo get_comment_link( $comment->comment_ID ); ?>" title="<?php echo wp_filter_nohtml_kses( $comment->comment_author ); ?> <?php echo esc_attr_x( 'on', 'comment topic', 'uno' ); ?> <?php echo esc_attr( $post->post_title ); ?>"><h3><?php echo wp_filter_nohtml_kses($comment->comment_author); ?></h3> <?php echo stripslashes( substr( wp_filter_nohtml_kses( $comment->comment_content ), 0, 50 ) ); ?>...</a>
									<div class="fix"></div>
								</li>
							<?php
							}
				 		}
 					?>
				</ul>

			</section>

		</div>

	</section>
<?php
}
//Woocommerce Shipment tracking changes by G2
/*add_filter( 'woocommerce_shipment_tracking_default_provider', 'custom_woocommerce_shipment_tracking_default_provider' );

function custom_woocommerce_shipment_tracking_default_provider( $provider ) {
	$provider = 'Shree Tirupati Courier'; // Replace this with the name of the provider. See line 42 in the plugin for the full list.

	return $provider;  
}
	//G2 Paypal INR to USD + Openexchangerates
	function convert_inr_to_usd($paypal_args){

    if ( $paypal_args['currency_code'] == 'INR'){
        $convert_rate = get_exchange_rate(); 
        //Set converting rate getting call back function
        $count = 1;

        while( isset($paypal_args['amount_' . $count]) ){
            $paypal_args['amount_' . $count] = round( $paypal_args['amount_' . $count] / $convert_rate, 2);
            $count++;
        }
    }
    return $paypal_args;
	}
	add_filter('woocommerce_paypal_args', 'convert_inr_to_usd');
	

	function get_exchange_rate() {
    $file = 'latest.json';
	$appId = '4c357158754d4c64a7890e1481683353';

	// Open CURL session:
	$ch = curl_init("http://openexchangerates.org/api/$file?app_id=$appId");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Get the data:
	$json = curl_exec($ch);
	curl_close($ch);

	// Decode JSON response:
	$exchangeRates = json_decode($json);

	// Returning Value:
	return $exchangeRates->rates->INR;
	}
*/
?>
<?php

add_action( 'wc_shipment_tracking_get_providers' , 'wc_shipment_tracking_add_custom_provider' );

/**
 * wc_shipment_tracking_add_custom_provider
 *
 * Adds custom provider to shipment tracking
 * Change the country name, the provider name, and the URL (it must include the %1$s)
 * Add one provider per line
*/
function wc_shipment_tracking_add_custom_provider( $providers ) 
{
	
	$providers['India']['Shree Tirupati Courier'] = 'http://www.shreetirupaticourier.net/Frm_DocTrack.aspx?docno=%1$s';
	$providers['India']['DTDC'] = 'http://dtdc.com';
  	$providers['India']['India Post'] = 'https://www.indiapost.gov.in/VAS/Pages/trackconsignment.aspx';
$providers['India']['FirstFlight'] = 'http://firstflight.net/trackingrequest.php?consRadio=C&trackingtextbox=%1$s';
$providers['India']['BlueDart'] = 'http://www.bluedart.com/servlet/RoutingServlet?action=awbquery&awb=awb&handler=tnt&numbers=%1$s';
$providers['India']['Spoton Logistics'] = 'http://spoton.co.in/ExternalTracking.aspx?ConStr=%1$s';
$providers['India']['Professional Courier'] = 'http://www.tpcindia.com/Tracking2014.aspx?id=%1$s&type=0&service=0';
$providers['India']['Fedex'] = 'https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=%1$s&cntry_code=in';
$providers['India']['UPS'] = 'https://www.ups.com/WebTracking/track?loc=en_IN';
$providers['India']['Delhivery'] = 'https://www.delhivery.com/track/package/%1$s';
$providers['India']['Skynet'] = 'https://international.delhivery.com/#!/trackdetail/%1$s';
	// etc...
	
	return $providers;
	
}
?>
<?php
function woocommerce_custom_get_availability( $availability_info, $wc_product ){
    if( $availability_info['availability'] == 'Available on backorder' ){
        $availability_info['availability'] = "Available on <span class='back'>backorder</span><span class='text'>This means that the item is not currently in stock. Your order will ship as the item becomes available.</span>";
    }
    return $availability_info;
}
add_filter( 'woocommerce_get_availability', 'woocommerce_custom_get_availability', 10, 2);
?>
<?php 
/**
* filter translations, to replace some WooCommerce text with our own
* @param string $translation the translated text
* @param string $text the text before translation
* @param string $domain the gettext domain for translation
* @return string
*/
function wpse_77783_woo_bacs_ibn($translation, $text, $domain) {
    if ($domain == 'woocommerce') {
        switch ($text) {
            case 'IBAN':
                $translation = 'Account Type';
                break;

            case 'BIC':
                $translation = 'Branch Name';
                break;
        }
    }

    return $translation;
}

add_filter('gettext', 'wpse_77783_woo_bacs_ibn', 10, 3);
?>
<?php 
function mh_load_my_script() {
    wp_enqueue_script( 'jquery' );
}

add_action( 'wp_enqueue_scripts', 'mh_load_my_script' );
?>
<?php
// display the extra data in the order admin panel
function kia_display_order_data_in_admin( $order ) {
     echo '<div class="address"><strong>' . __( 'Shipping Phone' ) . ':</strong>' . get_post_meta( $order->id, '_shipping_phone', true ) . '</div>'; ?>
     <div class="edit_address"> 
     <?php woocommerce_wp_text_input( array( 'id' => '_shipping_phone', 'label' => __( 'Shipping Phone' ), 'wrapper_class' => '_shipping_company_field' ) ); ?>
     </div> <?php
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'kia_display_order_data_in_admin' );

function kia_save_extra_details( $post_id, $post ){
    update_post_meta( $post_id, '_shipping_phone', wc_clean( $_POST[ '_shipping_phone' ] ) );
    update_post_meta( $post_id, '_billing_phone', wc_clean( $_POST[ '_billing_phone' ] ) );
}
add_action( 'woocommerce_process_shop_order_meta', 'kia_save_extra_details', 45, 2 );

// display the extra data on order recieved page and my-account order review
function kia_display_order_data( $order_id ){ 
	$phone = get_post_meta( $order_id, '_shipping_phone', true ) ? get_post_meta( $order_id, '_shipping_phone', true ) : get_post_meta( $order_id, '_billing_phone', true );
    echo '<p><strong>' . __( 'Shipping phone' ) . ':</strong> ' . $phone . '</p>';
}
add_action( 'woocommerce_thankyou', 'kia_display_order_data', 20 );
add_action( 'woocommerce_view_order', 'kia_display_order_data', 20 );

// display the extra data on email
// function kia_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
//     $fields['shipping_phone'] = array(
//                 'label' => __( 'Shipping phone' ),
//                 'value' => get_post_meta( $order->id, '_shipping_phone', true ),
//             );
//     return $fields;
// }
// add_filter('woocommerce_email_order_meta_fields', 'kia_email_order_meta_fields', 10, 3 );


function replace_content($text)
{
$alt = get_the_author_meta( 'display_name' );
$text = str_replace('alt=\'\'', 'alt=\'Avatar for '.$alt.'\' title=\'Gravatar for '.$alt.'\'',$text);
return $text;
}
add_filter('get_avatar','replace_content');
?>


<?php
/**
* Change Proceed To Checkout Text in WooCommerce
**/
function woocommerce_button_proceed_to_checkout() {
       $checkout_url = WC()->cart->get_checkout_url();
       ?>
       <a href="<?php echo $checkout_url; ?>" class="checkout-button button alt wc-forward"><?php _e( 'Checkout Now', 'woocommerce' ); ?></a>
       <?php
     }?>
     
     
<?php
function patricks_wc_terms( $terms_is_checked ) {
	return true;
}
add_filter( 'woocommerce_terms_is_checked', 'patricks_wc_terms', 10 );
add_filter( 'woocommerce_terms_is_checked_default', 'patricks_wc_terms', 10 ); ?>
<?php
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );  
function woo_custom_cart_button_text() {
        return __( 'Add to Cart', 'woocommerce' );
} ?>
<?php
add_action('woocommerce_checkout_process', 'phoneValidateCheckoutFields');
function phoneValidateCheckoutFields() {
	$billing_phone = filter_input(INPUT_POST, 'billing_phone');

	if (strlen( $billing_phone) < 5) {
		wc_add_notice(__('<strong>Billing Phone Number</strong> must contain at least 5 characters. Try, please, again.'), 'error');
	}
}
?>
<?php
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 0 );

?>
<?php
// PayPal hook for India
add_filter( 'woocommerce_paypal_supported_currencies', 'add_paypal_valid_currency' ); 
 
function add_paypal_valid_currency( $currencies ) { 
     array_push ( $currencies , 'INR' );
     return $currencies; 
}
?>
<?php
// Set a minimum per order for sample product
add_action( 'woocommerce_check_cart_items', 'spyr_set_min_total' );
function spyr_set_min_total() {
	if( is_cart() || is_checkout() ) {
		global $woocommerce;

		$minimum_cart_total = 1;

		$total = WC()->cart->subtotal;
		
		if( $total <= $minimum_cart_total  ) {
			wc_add_notice( sprintf( '<strong>A Minimum of 1 product(not Sample) is required before checking out order with Sample product.</strong>'
				.'<br />Please, <a href="/shop/">continue shopping</a>',
				$minimum_cart_total,
				get_option( 'woocommerce_currency'),
				$total,
				get_option( 'woocommerce_currency') ),
			'error' );
		}
	}
}

// Remove password strength
function wc_ninja_remove_password_strength() {
	if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
		wp_dequeue_script( 'wc-password-strength-meter' );
	}
}
add_action( 'wp_print_scripts', 'wc_ninja_remove_password_strength', 100 );

// Set ship to different address default unchecked 
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );


// Redirect custom thank you
/* 
add_action( 'woocommerce_thankyou', 'bbloomer_redirectcustom');
 
function bbloomer_redirectcustom( $order_id ){
    $order = new WC_Order( $order_id );
 
    $url = './my-account/orders/';
 
    if ( $order->status != 'failed' || $order->status = 'failed' ) {
        echo "<script type='text/javascript'>window.location = '" . $url . "'</script>";
    }
}
*/
// Add filter for variations on single product pages
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'mmx_remove_select_text');
function mmx_remove_select_text( $args ){
    $args['show_option_none'] = '';
    return $args;
}

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 50 );

// Add Indian Rupee Symbol 
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
switch( $currency ) {
case 'INR': $currency_symbol = 'Rs. '; break;
}
return $currency_symbol;
}

wp_enqueue_style( 'font', get_stylesheet_directory_uri().'/css/font.css',false,'1.1','all');


// FIXING PAYPAL exchange
add_filter('woocommerce_paypal_args', 'convert_aed_to_usd', 11 );  
// function get_currency($from_Currency='USD', $to_Currency='INR') {
// 	$url = "http://www.google.com/finance/converter?a=1&from=$from_Currency&to=$to_Currency";
// 	$ch = curl_init();
// 	$timeout = 0;
// 	curl_setopt ($ch, CURLOPT_URL, $url);
// 	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
// 	curl_setopt ($ch, CURLOPT_USERAGENT,
// 	"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
// 	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
// 	$rawdata = curl_exec($ch);
// 	curl_close($ch);
// 	$data = explode('bld>', $rawdata);
// 	$data = explode($to_Currency, $data[1]);
// 	return round($data[0], 2);
// }
function get_currency() {
    $file = 'latest.json';
	$appId = '4c357158754d4c64a7890e1481683353';

	// Open CURL session:
	$ch = curl_init("http://openexchangerates.org/api/$file?app_id=$appId");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Get the data:
	$json = curl_exec($ch);
	curl_close($ch);

	// Decode JSON response:
	$exchangeRates = json_decode($json);

	// Returning Value:
	return $exchangeRates->rates->INR;
}
function convert_aed_to_usd($paypal_args){
	if ( $paypal_args['currency_code'] == 'INR'){  
		$convert_rate = get_currency(); //Set converting rate
		$paypal_args['currency_code'] = 'USD'; //change INR to USD  
		$i = 1;  
		while (isset($paypal_args['amount_' . $i])) {  
		$paypal_args['amount_' . $i] = round( $paypal_args['amount_' . $i] / $convert_rate, 2);
		++$i;  
	}  
	if ( $paypal_args['shipping_1'] > 0 ) {
	$paypal_args['shipping_1'] = round( $paypal_args['shipping_1'] / $convert_rate, 2);
	}

}
return $paypal_args;  
}

// Google Analytics to menu
/*add_filter( 'nav_menu_link_attributes', 'themeprefix_menu_attribute_add', 10, 3 );
function themeprefix_menu_attribute_add( $atts, $item, $args )
{
  // Set the menu ID
  $menu_link = 786;
  $menu_link1 = 347;
  $menu_link2 = 348;
  $menu_link3 = 16761;
  $menu_link4 = 2344;
  // Conditionally match the ID and add the attribute and value
  if ($item->ID == $menu_link || $item->ID == $menu_link1 || $item->ID == $menu_link2 || $item->ID == $menu_link3 || $item->ID == $menu_link4) {
    $atts['onclick'] = '_gaq.push([\'_trackEvent\', \'menu\', \'click\', \'menu_click\'])';
  }
  //Return the new attribute
  return $atts;
}*/

//enqueues external font awesome stylesheet
function enqueue_our_required_stylesheets(){
	wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'); 
}
add_action('wp_enqueue_scripts','enqueue_our_required_stylesheets');

//minify html
add_action('get_header', 'gkp_html_minify_start');
function gkp_html_minify_start()  {
    ob_start( 'gkp_html_minyfy_finish' );
}

function gkp_html_minyfy_finish( $html )  {
   $html = preg_replace('/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html);
   $html = str_replace(array("\r\n", "\r", "\n", "\t"), '', $html);
   while ( stristr($html, '  ')) 
       $html = str_replace('  ', ' ', $html);
   return $html;
}

add_filter( 'woocommerce_order_formatted_billing_address' , 'woo_reorder_billing_fields', 10, 2 );
function woo_reorder_billing_fields( $address, $wc_order ) {
    $address = array(
        'first_name'    => $wc_order->billing_first_name,
        'last_name'     => $wc_order->billing_last_name,
        'company'       => $wc_order->billing_company,
        'address_1'     => $wc_order->billing_address_1,
        'address_2'     => $wc_order->billing_address_2,
        'city'          => $wc_order->billing_city,
        'state'         => $wc_order->billing_state,
        'postcode'      => $wc_order->billing_postcode,
        'country'       => $wc_order->billing_country
        );
    return $address;
}

add_filter( 'woocommerce_email_classes', 'failed_order_email' );
function failed_order_email( $classes ) {
$classes['WC_Email_Customer_Fail_Order'] = include( get_stylesheet_directory() . '/email.php' );
return $classes;
}

//search order by tracking number
add_filter( 'woocommerce_shop_order_search_fields', 'woocommerce_shop_order_search_ship_tracking_code' );
function woocommerce_shop_order_search_ship_tracking_code( $search_fields ) {
  $search_fields[] = 'ship_tracking_code';
  return $search_fields;
}

//Tracking link
function sv_add_my_account_order_actions( $actions, $order) {
        global $woocommerce;
        $order_id = $order->id;
        $tracking_provider =  get_post_meta($order_id,'_tracking_provider',true); 
        $tracking_number =  get_post_meta($order_id,'_tracking_number',true); 
        if ($tracking_provider == 'australia-post') {
           $tracking_link = 'http://auspost.com.au/track/track.html?id=' .$tracking_number;
        } elseif ($tracking_provider == 'post-at')  {
           $tracking_link = 'http://www.post.at/sendungsverfolgung.php?pnum1=' .$tracking_number;
        } elseif ($tracking_provider == 'dhl-at')  {
           $tracking_link = 'http://www.dhl.at/content/at/de/express/sendungsverfolgung.html?brand=DHL&AWB=' .$tracking_number;
        } elseif ($tracking_provider == 'dpd-at')  {
           $tracking_link = 'https://tracking.dpd.de/parcelstatus?locale=de_AT&query=' .$tracking_number;
        } elseif ($tracking_provider == 'correios')  {
           $tracking_link = 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=' .$tracking_number;
        } elseif ($tracking_provider == 'canada-post')  {
           $tracking_link = 'http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=' .$tracking_number;
        } elseif ($tracking_provider == 'ppl-cz')  {
           $tracking_link = 'http://www.ppl.cz/main2.aspx?cls=Package&idSearch=' .$tracking_number;
        } elseif ($tracking_provider == 'dtdc')  {
           $tracking_link = 'http://www.dtdc.in/dtdcTrack/Tracking/consignInfo.asp?strCnno=' .$tracking_number;
        } elseif ($tracking_provider == 'fedex')  {
           $tracking_link = 'https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=' .$tracking_number. '&cntry_code=in';
        } elseif ($tracking_provider == 'fedex-sameday')  {
           $tracking_link = 'https://www.fedexsameday.com/fdx_dotracking_ua.aspx?tracknum=' .$tracking_number;
        } elseif ($tracking_provider == 'ontrac')  {
           $tracking_link = 'http://www.ontrac.com/trackingdetail.asp?tracking=' .$tracking_number;
        } elseif ($tracking_provider == 'ups')  {
           $tracking_link = 'https://www.ups.com/WebTracking/track?loc=en_IN';
        } elseif ($tracking_provider == 'usps')  {
           $tracking_link = 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=' .$tracking_number;
        } elseif ($tracking_provider == 'india-post')  {
           $tracking_link = 'https://www.indiapost.gov.in/VAS/Pages/trackconsignment.aspx';
        } elseif ($tracking_provider == 'shree-tirupati-courier')  {
           $tracking_link = 'http://www.shreetirupaticourier.net/Frm_DocTrack.aspx?docno=' .$tracking_number;
        } elseif ($tracking_provider == 'bluedart')  {
           $tracking_link = 'http://www.bluedart.com';
        } elseif ($tracking_provider == 'spoton-logistics')  {
           $tracking_link = 'http://spoton.co.in/ExternalTracking.aspx?ConStr=' .$tracking_number;
        } elseif ($tracking_provider == 'professional-courier')  {
           $tracking_link = 'http://www.tpcindia.com/Tracking2014.aspx?id=' .$tracking_number. '&type=0&service=0';
        } elseif ($tracking_provider == 'firstflight')  {
           $tracking_link = 'http://firstflight.net/trackingrequest.php?consRadio=C&trackingtextbox=' .$tracking_number;
        } else {
           $tracking_link = $tracking_provider;
        }
        if( !empty( $tracking_link) ) :
		$actions['track'] = array(
		   'url'  => $tracking_link,
		   'name' => 'Track',
		);
		endif; 
		return $actions;
}
add_filter( 'woocommerce_my_account_my_orders_actions', 'sv_add_my_account_order_actions', 10, 2 );



add_filter( 'manage_edit-shop_order_columns', 'view_orders_credit_custom_colomns' );
function view_orders_credit_custom_colomns($columns){
    $new_columns = (is_array($columns)) ? $columns : array();
 	unset( $new_columns['customer_message'] );
 	unset( $new_columns['order_notes'] );
 	unset( $new_columns['pip_print_packing-list'] );
 	unset( $new_columns['pip_print_invoice'] );
 	return $new_columns;
}



add_filter( 'upload_size_limit', 'b5f_increase_upload' );
function b5f_increase_upload( $bytes )
{
    return 33554432;
}

function slick_slider_styles(){
        wp_enqueue_style( 'slick_css', '//cdn.jsdelivr.net/jquery.slick/1.5.0/slick.css' );
    }
    add_action( 'wp_enqueue_scripts', 'slick_slider_styles' );

    //Scripts
    function slick_slider_js(){
        wp_enqueue_script( 'slick_js', '//cdn.jsdelivr.net/jquery.slick/1.5.0/slick.min.js', array('jquery'), '', true );
    }
    add_action( 'wp_enqueue_scripts', 'slick_slider_js' );
	
/*-----------------------------------------------------------------------------------*/
/* Custom field date to Waitlist Tab */
/*-----------------------------------------------------------------------------------*/
	function woo_add_custom_general_fields() {
	  global $woocommerce, $post;
	  echo '<div class="options_group">';
		 woocommerce_wp_text_input( 
			array( 
				'id'          => '_tentative_date', 
				'label'       => __( 'Tentative in stock date', 'woocommerce' ), 
				'placeholder' => 'June, 30',
				'desc_tip'    => 'true',
				'description' => __( 'Enter the date here', 'woocommerce' ) 
			)
		);
	  echo '</div>';	
	}
	add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );

	function woo_add_custom_general_fields_save( $post_id ){
		
		// Text Field
		$woocommerce_text_field = $_POST['_tentative_date'];
		if( !empty( $woocommerce_text_field ) )
			update_post_meta( $post_id, '_tentative_date', esc_attr( $woocommerce_text_field ) );
	}
	add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );
	
/*-----------------------------------------------------------------------------------*/
/* Optionally load the mobile navigation toggle. */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_nav_toggle' ) ) {
function woo_nav_toggle () {
?>
<h3 class="nav-toggle icon"><a href="#navigation" style="border:none;">Sattvic Foods</a></h3>
	<div id="headertools">

	<a id= "cart-content-header" class="cart-contents" href="/cart/" title="View your shopping cart"></a>
	<a class="icon_search_mobile" href="#">
	<i class="fa fa-search" aria-hidden="true">
	</i>	
	</a>
	<form class="search-header" id="search_mobile" role="search" action="/" method="get">
	 <p id="hidden" style="display: none;"><input name="s" id="s" placeholder="Type here..." value="" type="text">
	<button name="submit" class="fa fa-search submit" id="searchsubmit" type="submit" value="Search"></button><input name="post_type" value="product" type="hidden"></p>
	</form>
	
	</div>
<!-- <form id="search_mobile" role="search" action="/" method="get">
				<label class="fa fa-search submit"></label>
		        <p id="hidden">
					<input name="s" id="s" type="text" placeholder="Type here..." value="">
					<button name="submit" class="fa fa-search submit" id="searchsubmit" type="submit" value="Search"></button>
			<input name="post_type" type="hidden" value="product">	
</p>
</form>
-->
<?php
} // End woo_nav_toggle()
}
add_action( 'woo_header_before', 'woo_nav_toggle', 20 );
	
/*-----------------------------------------------------------------------------------*/
/*                                   Primary menu                                    */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_nav_primary' ) ) {
function woo_nav_primary() {
?>
	<!-- <a href="<?php echo home_url(); ?>" class="nav-home"><span><?php _e( 'Home', 'woothemes' ); ?></span></a> -->
	<div id="before_nav">
		<a href="/my-account/" title="My Account" class="icon_login my_account_mobile"><i class="fa fa-user-circle-o" aria-hidden="true"></i></a>
		<?php /* if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )  {  */
					    $count = WC()->cart->cart_contents_count;
					    ?>
					    <?php 
					    if ( $count > 0 ) {
					        ?>
					        <a class="cart-contents full" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">Cart<span class="cart-contents-count"><?php echo esc_html( $count ); ?></span></a>   
					    <?php } else { ?>
					    	<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">Cart</a>
					    <?php } ?>
					 
		<?php /* } */ ?>
		<a href="#" class="icon_search_mobile"><i class="fa fa-search" aria-hidden="true"></i></a>
	</div>
	<form id="search_mobile" role="search" action="/" method="get">
		<!-- <label class="fa fa-search submit"></label> -->
        <p id="hidden">
			<input name="s" id="s" type="text" placeholder="Type here..." value="">
			<button name="submit" class="fa fa-search submit" id="searchsubmit" type="submit" value="Search"></button>
			<input name="post_type" type="hidden" value="product">
		</p>
	</form>
	<?php
	if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
		// echo '<h3>' . woo_get_menu_name( 'primary-menu' ) . '</h3>';
		wp_nav_menu( array( 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fl', 'theme_location' => 'primary-menu' ) );
	} else {
	?>
		<ul id="main-nav" class="nav fl">
			<?php
			if ( get_option( 'woo_custom_nav_menu' ) == 'true' ) {
				if ( function_exists( 'woo_custom_navigation_output' ) ) { woo_custom_navigation_output( 'name=Woo Menu 1' ); }
			} else { ?>

				<?php if ( is_page() ) { $highlight = 'page_item'; } else { $highlight = 'page_item current_page_item'; } ?>
				<li class="<?php echo esc_attr( $highlight ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
				<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
			<?php } ?>
		</ul><!-- /#nav -->
	<?php } ?>
	
	<a href="/shop/" id="shop_all" title="SHOP ALL">SHOP ALL</a>
	<a href="/recipes/" class="usefull_links" title="View our Recipes"><span><i class="fa fa-cutlery" aria-hidden="true"></i> View our</span> Recipes</a>
	<a href="/where-to-buy/" class="usefull_links" title="Where To Buy"><span><i class="fa fa-globe" aria-hidden="true"></i> Where To</span> Buy</a>
	<a href="/payment-and-shipping-policy/" class="usefull_links" title="Cash On Delivery?"><span><i class="fa fa-money" aria-hidden="true"></i> "Cash On Delivery?"</span></a>
<?php 
} // End woo_nav_primary()
}
add_theme_support( 'wc-product-gallery-slider' );
add_theme_support( 'wc-product-gallery-lightbox' );


/***********************************************************/
// WooCommerce Checkout Fields Hook
/***********************************************************/
add_filter( 'woocommerce_checkout_fields', 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
$fields['billing']['billing_postcode']['description'] = 'We are not responsible for delays in shipping if inaccurate Pincode is provided.';
$fields['shipping']['shipping_postcode']['description'] = 'We are not responsible for delays in shipping if inaccurate Pincode is provided.';
$fields['billing']['billing_company']['placeholder'] = 'Only if applicable';
$fields['shipping']['shipping_company']['placeholder'] = 'Only if applicable';
$fields['billing']['billing_address_1']['label'] = 'Street Address 1';
$fields['shipping']['shipping_address_1']['label'] = 'Street Address 1';
$fields['billing']['billing_address_2']['label'] = 'Landmark';
$fields['shipping']['shipping_address_2']['label'] = 'Landmark';
$fields['billing']['billing_address_2']['placeholder'] = '';
$fields['shipping']['shipping_address_2']['placeholder'] = '';
$fields['shipping']['shipping_phone'] = array(
        'label'     => __('Shipping Phone', 'woocommerce'),
    'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
    'required'  => false,
    'class'     => array('form-row-wide'),
    'clear'     => true,
    'type'		=> 'tel'
     );

$fields['billing']['billing_phone']['label'] = 'Phone';
$fields['billing']['billing_phone']['maxlength'] = 30; 

$fields['billing']['billing_city']['class'] = array('form-row-first');
$fields['billing']['billing_state']['class'] = array('form-row-last');
$fields['billing']['billing_postcode']['class'] = array('form-row-first');
$fields['billing']['billing_country']['class'] = array('form-row-last');

$fields['billing']['billing_address_1']['class'] = array('form-row-wide');
$fields['billing']['billing_address_2']['class'] = array('form-row-wide');

$fields['billing']['billing_first_name']['priority'] = 10;
$fields['billing']['billing_last_name']['priority'] = 15;
$fields['billing']['billing_company']['priority'] = 20;
$fields['billing']['billing_email']['priority'] = 25;
$fields['billing']['billing_phone']['priority'] = 30;
$fields['billing']['billing_address_1']['priority'] = 35;
$fields['billing']['billing_address_2']['priority'] = 40;
$fields['billing']['billing_city']['priority'] = 45;
$fields['billing']['billing_state']['priority'] = 50;
$fields['billing']['billing_postcode']['priority'] = 55;
$fields['billing']['billing_country']['priority'] = 60;

$fields['shipping']['shipping_first_name']['priority'] = 10;
$fields['shipping']['shipping_last_name']['priority'] = 15;
$fields['shipping']['shipping_company']['priority'] = 20;
$fields['shipping']['shipping_phone']['priority'] = 25;
$fields['shipping']['shipping_address_1']['priority'] = 30;
$fields['shipping']['shipping_address_2']['priority'] = 35;
$fields['shipping']['shipping_city']['priority'] = 40;
$fields['shipping']['shipping_state']['priority'] = 45;
$fields['shipping']['shipping_postcode']['priority'] = 50;
$fields['shipping']['shipping_country']['priority'] = 55;


$order_billing = array(
	"billing_first_name", 
	"billing_last_name", 
	"billing_company",
	"billing_email",
	"billing_phone",
	"billing_address_1", 
	"billing_address_2", 
	"billing_city",
	"billing_state",
	"billing_postcode",
	"billing_country"
);

foreach($order_billing as $field){
	$ordered_billing_fields[$field] = $fields["billing"][$field];
}

$fields["billing"] = $ordered_billing_fields;

$order_shipping = array(
	"shipping_first_name", 
	"shipping_last_name", 
	"shipping_company",
	"shipping_phone",
	"shipping_address_1", 
	"shipping_address_2",  
	"shipping_city",
	"shipping_state",
	"shipping_postcode",
	"shipping_country"
	
);

foreach($order_shipping as $field){
	$ordered_shipping_fields[$field] = $fields["shipping"][$field];
}

$fields["shipping"] = $ordered_shipping_fields;

return $fields;

}


require_once ( get_stylesheet_directory() . '/inc/sfoo_fields_validation.php' );


/* Sample products weight must be 0 */
function sfoo_sample_product_change_weight( $cart_object ) {

	if ( (is_admin() && ! defined( 'DOIMG_AJAX' ) ) || $cart_object->is_empty() ) return;

	foreach ( $cart_object->get_cart() as $cart_item ) {
		if ( $cart_item['sample'] === true ) {
			$cart_item['data']->set_weight( 0 );
		}
	}

}
add_action( 'woocommerce_before_calculate_totals', 'sfoo_sample_product_change_weight', 10, 1 );

/* Single product SKU before gallery */
function sfoo_single_sku_before_gallery() {
	global $product;

	if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<div class="sku_wrapper sfoo_single_page_sku"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span></div>

	<?php endif; 
}
add_action( 'woocommerce_before_single_product_summary', 'sfoo_single_sku_before_gallery', 5 );

/* Recipe hero products taxonomy */
add_action('init', 'create_taxonomy');
function create_taxonomy(){

	register_taxonomy('recipe_products', array('recipe'), array(
		'labels'                => array(
			'name'              => 'Recipe Products',
			'singular_name'     => 'Recipe Product',
			'search_items'      => 'Search Recipe Product',
			'all_items'         => 'All Recipe Product',
			'parent_item'       => 'Parent Recipe Product',
			'parent_item_colon' => 'Parent Recipe Product:',
			'edit_item'         => 'Edit Recipe Product',
			'update_item'       => 'Update Recipe Product',
			'add_new_item'      => 'Add New Recipe Product',
			'new_item_name'     => 'New Recipe Product Name',
			'menu_name'         => 'Recipe Product',
		),
		'public'                => true,
		'show_in_menu'			=> false,
		'show_in_nav_menus'		=> false,
		'show_tagcloud'			=> false,
		'show_in_rest'          => true, 
		'hierarchical'          => false,
		'rewrite'               => array( 'slug' => 'recipe_products' ),
		'meta_box_cb'           => 'post_tags_meta_box', 
		'show_admin_column'     => false,
		'show_in_quick_edit'    => null,
	) );
	
}

/* Recipe hero recipes id by product name */
function sfoo_get_recipes_id_by_product_name($product_name){
	global $post;
	$result = [];
	$sfoo_recipes = get_posts(['numberposts' => -1, 'post_type' => 'recipe']);

	$ingredients = get_post_meta( $sfoo_recipes[0]->ID, '_recipe_hero_ingredients_group', true );
	$ingredients = array_filter( $ingredients );

	foreach ($sfoo_recipes as $recipe) {
		// echo $recipe->ID . ': ';
		$ingredients = get_post_meta( $recipe->ID, '_recipe_hero_ingredients_group', true );
		$recipe_products_tags = get_the_terms( $recipe->ID, 'recipe_products' );

		$is_pushed = false;

		foreach ($ingredients as $ingredient) {
			// echo $ingredient['name'] . ', ';
			if ( strtolower($ingredient['name']) == strtolower($product_name) ) {
				array_push($result, $recipe->ID);
				$is_pushed = true;
				break;
			}
		}

		if( !$is_pushed ) {
			foreach ($recipe_products_tags as $rp_tag) {
				if ( strtolower($rp_tag->name) == strtolower($product_name) ) {
					array_push($result, $recipe->ID);
					break;
				}
			}
		}
		// echo '<br>';
	}

	return $result[0] ? $result : false;
}

/* Display cart-widget on checkout and cart pages */
add_filter('woocommerce_widget_cart_is_hidden', 'show_cart_on_checkout');
function show_cart_on_checkout() {
    return;
}

/* Displayed at author page in woo_loop_before() action */
function woo_author_box () {
	global $post;
	
	if( $post ) {
		$author_id=$post->post_author;
	} else {
		$author_id=get_queried_object()->ID;
	}
	
	// Adjust the arrow, if is_rtl().
	$arrow = '&rarr;';
	if ( is_rtl() ) $arrow = '&larr;';
?>
	<aside id="post-author">
		<div class="profile-image"><?php echo get_avatar( $author_id, '80' ); ?></div>
		<div class="profile-content">
			<h4><?php printf( esc_attr__( 'About %s', 'woothemes' ), get_the_author_meta( 'display_name', $author_id ) ); ?></h4>
			<?php echo get_the_author_meta( 'description', $author_id ); ?>
			<?php if ( is_singular() ) { ?>
			<div class="profile-link">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ); ?>">
					<?php printf( __( 'View all posts by %s %s', 'woothemes' ), get_the_author_meta( 'display_name', $author_id ), '<span class="meta-nav">' . $arrow . '</span>' ); ?>
				</a>
			</div><!--#profile-link-->
			<?php } ?>
		</div>
		<div class="fix"></div>
	</aside>
<?php
} // End woo_author_box()

/* Disable COD pincode checker on product page */
remove_action( 'woocommerce_after_add_to_cart_button','cod_single_product_page' );

/* Scripts conflict in admin pages (Recipe hero vs. Blue dart plugins) */
add_action( 'admin_footer', 'de_conflict_script', 100 );

function de_conflict_script() {
	wp_dequeue_script( 'jquery.js' );
	wp_deregister_script( 'jquery.js' );
}


/* Add Payment tab to My Account */
										/* DEVELOPING BY A.LEON DON'T TOUCH */
/**
 * Account menu items
 *
 * @param arr $items
 * @return arr
 */

function sfoo_account_menu_items( $items ) {
 
    $items['payment-details'] = __( 'Payment details', 'woocommerce' );
 
    return $items;
 
}

add_filter( 'woocommerce_account_menu_items', 'sfoo_account_menu_items', 10, 1 );

/**
 * Add endpoint
 */
function sfoo_add_my_account_endpoint() {
 
    add_rewrite_endpoint( 'payment-details', EP_PAGES );
 
}
 
add_action( 'init', 'sfoo_add_my_account_endpoint' );

/**
 * Information content
 */
function sfoo_myaccount_payment_endpoint_content() {
    ?>

	<div class="sfoo_myaccount_payment_details">
		<h3>Our bank details</h3>
		
		<h4>BHIM UPI:</h4>

    	<p>Account number: 9096029416@kotak</p>

		<h4>Sattvic Innovations:</h4>

	    <p>Bank: Kotak Bank<br>
	    Account number: 9412221362<br>
	    IFSC: KKBK0000701<br>
	    IBAN: Current Account<br>
	    BIC: Panaji, Goa Branch</p>

		<h4>Sattvic Innovations:</h4>

	    <p>Bank: Union Bank<br>
	    Account number: 324901010291851<br>
	    IFSC: UBIN0532495<br>
	    IBAN: Current Account<br>
	    BIC: Rua de Ourem, Panaji</p>

	</div>

    <?php
}
 
add_action( 'woocommerce_account_payment-details_endpoint', 'sfoo_myaccount_payment_endpoint_content' );

/* Allow change payment with On Hold order status */
function sfoo_allow_onhold_payment( $statuses, $order ) {
	$statuses[] = 'on-hold';

	return $statuses;
}

add_filter( 'woocommerce_valid_order_statuses_for_payment', 'sfoo_allow_onhold_payment', 20, 5 );
