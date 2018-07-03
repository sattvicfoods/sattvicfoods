<?php
/**
 * Custom functions that act independently of the theme templates
 * Eventually, some of the functionality here could be replaced by core features
 * @package Circumference Lite 
 */



/** 
 * A way to stop the WP editor from stripping out empty div containers
 */ 
function circumferencelite_change_mce_options( $init ) {
$init['extended_valid_elements'] = 'div[*]';
return $init;
}
add_filter('tiny_mce_before_init', 'circumferencelite_change_mce_options');




/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 * @param array $args Configuration arguments.
 * @return array
 */
function circumferencelite_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'circumferencelite_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 * @param array $classes Classes for the body element.
 * @return array
 */
function circumferencelite_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'circumferencelite_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function circumferencelite_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'circumference-lite' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'circumferencelite_wp_title', 10, 2 );



/* Prevent page scroll after clicking read more */
function circumferencelite_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'circumferencelite_remove_more_link_scroll' );


	
/* Customize the comments form using Bootstrap 3 */		
add_filter( 'comment_form_default_fields', 'circumferencelite_comment_form_fields' );
function circumferencelite_comment_form_fields( $fields ) {
    $commenter = wp_get_current_commenter();
    
    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html5    = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
    
    $fields   =  array(
        'author' => '<div class="form-group comment-form-author">' . '<label for="author">' . __( 'Name', 'circumference-lite' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
        'email'  => '<div class="form-group comment-form-email"><label for="email">' . __( 'Email', 'circumference-lite' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input class="form-control" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
        'url'    => '<div class="form-group comment-form-url"><label for="url">' . __( 'Website', 'circumference-lite' ) . '</label> ' .
                    '<input class="form-control" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
    );
    
    return $fields;
}	
	
add_filter( 'comment_form_defaults', 'circumferencelite_comment_form' );
function circumferencelite_comment_form( $args ) {
    $args['comment_field'] = '<div class="form-group comment-form-comment">
             
            <textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
        </div>';
    return $args;
}	
	
	
	