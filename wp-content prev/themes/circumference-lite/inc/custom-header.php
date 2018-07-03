<?php
/**
 * Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Circumference Lite 
 */

function circumferencelite_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'circumferencelite_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/banner-bg.jpg',
		'random-default'         => false,
		'flex-width'    		 => true,
		'width'                  => 2560,
		'flex-height'            => true,
		'height'                 => 40,	
		'uploads'       		 => true,
		'header-text'            => false,
		'admin-preview-callback' => 'circumferencelite_admin_header_image',
	) ) );
    
    //register the default header
    register_default_headers( array(
        'mypic' => array(
        'url'   => get_template_directory_uri() . '/images/banner-bg.jpg',
        'thumbnail_url' => get_template_directory_uri() . '/images/banner-bg.jpg',
        'description'   => _x( 'Default Header', 'Default Header', 'circumference-lite' )),
    ));
    
}
add_action( 'after_setup_theme', 'circumferencelite_custom_header_setup' );

if ( ! function_exists( 'circumferencelite_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see circumferencelite_custom_header_setup().
 */
function circumferencelite_admin_header_image() {
	
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php
}
endif; // circumferencelite_admin_header_image
