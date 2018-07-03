<?php

/**
 * The logo group for the header
 * @package Circumference Lite
 * @since 1.0.4
 */
?>




<?php 
	$logostyle = get_theme_mod( 'logo_style', 'default' );
	 switch ($logostyle) {
		case "default" : // default theme logo ?>
        
        <div id="cir-logo-group">
            <div id="cir-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">				
           <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/images/circumference-logo.png" width="57" height="57" alt="<?php bloginfo( 'name' ); ?>" /></a></div>   
            <div id="cir-site-title-group">
            <h1 id="cir-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" style="color: <?php echo get_theme_mod( 'sitetitle', '#565656' ); ?>;"><?php bloginfo('name'); ?></a></h1>
            <h2 id="cir-site-tagline" style="color: <?php echo get_theme_mod( 'tagline', '#378B92' ); ?>;"><?php bloginfo('description'); ?></h2>
            </div>
        </div>
            	 
		<?php break;
		case "custom" : // your own logo ?>
			
			<div id="cir-logo-group">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img class="img-responsive" src="<?php echo get_option( 'my_logo' ); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
				</a>
			</div>
						 
		<?php break;
		case "logotext" : // your own logo with text based title and site description ?>
        
            <div id="cir-logo-group">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                    <img class="img-responsive" src="<?php echo get_option( 'my_logo' ); ?>" alt="<?php bloginfo( 'name' ); ?> "/>
                </a>
            </div>
            
            <div id="cir-site-title-group" style="margin: <?php echo get_theme_mod( 'titlemargin', '0 0 0 0' ); ?>;">
                <h1 id="cir-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" 
                    rel="home" style="color: <?php echo get_theme_mod( 'sitetitle', '#565656' ); ?>;"><?php bloginfo('name'); ?></a></h1>
                <h2 id="cir-site-tagline" style="color: <?php echo get_theme_mod( 'tagline', '#378B92' ); ?>;"><?php bloginfo('description'); ?></h2>
            </div>
            			
		<?php break;
		case "text" : // text based title and site description ?>
			
            <div id="cir-site-title-group">
                <h1 id="cir-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" 
                    rel="home" style="color: <?php echo get_theme_mod( 'sitetitle', '#565656' ); ?>;"><?php bloginfo('name'); ?></a></h1>
                <h2 id="cir-site-tagline" style="color: <?php echo get_theme_mod( 'tagline', '#378B92' ); ?>;"><?php bloginfo('description'); ?></h2>
            </div>	
            		
		<?php break;
	} 
?>


