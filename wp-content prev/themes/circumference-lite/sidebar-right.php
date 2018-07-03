<?php
/**
 * The Right Sidebar for the blog and pages
 * @package Circumference Lite
 * @since 1.0.4
 */

if (   ! is_active_sidebar( 'blogright'  )
	&& ! is_active_sidebar( 'pageright' ) 
	)
	return;

if ( is_page() ) {   
	
	dynamic_sidebar( 'pageright' );	

} else {
  
	dynamic_sidebar( 'blogright' );
	
}
?>