<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Circumference Lite 
*/

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function circumferencelite_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'cir-content',
		'footer'    => 'cir-wrapper',
	) );
}
add_action( 'after_setup_theme', 'circumferencelite_jetpack_setup' );
