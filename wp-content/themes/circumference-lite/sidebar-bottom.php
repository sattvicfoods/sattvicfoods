<?php
/**
 * The Bottom Sidebar
 * @package Circumference Lite
 * @since 1.0.4
 */

if (   ! is_active_sidebar( 'bottom1'  )
	&& ! is_active_sidebar( 'bottom2' )
	&& ! is_active_sidebar( 'bottom3'  )
	&& ! is_active_sidebar( 'bottom4'  )		
		
	)

		return;
	// If we get this far, we have widgets. Let do this.
?>

<aside id="cir-bottom-wrapper" role="complementary" style="background-color: <?php echo get_theme_mod( 'bottom_bg', '#384149' ); ?>; color:<?php echo get_theme_mod( 'bottomtext', '#abb3b4' ); ?>;">
    <div class="container">
        <div id="cir-bottom-group" class="row">                    
			
                <div id="bottom1" <?php circumferencelite_bottomgroup(); ?> role="complementary">
                    <?php dynamic_sidebar( 'bottom1' ); ?>
                </div><!-- #top1 -->
           
                <div id="bottom2" <?php circumferencelite_bottomgroup(); ?> role="complementary">
                    <?php dynamic_sidebar( 'bottom2' ); ?>
                </div><!-- #top2 -->          
            
                <div id="bottom3" <?php circumferencelite_bottomgroup(); ?> role="complementary">
                    <?php dynamic_sidebar( 'bottom3' ); ?>
                </div><!-- #top3 -->
         
                <div id="bottom4" <?php circumferencelite_bottomgroup(); ?> role="complementary">
                    <?php dynamic_sidebar( 'bottom4' ); ?>
                </div><!-- #top4 -->
                                    
        </div>
    </div>
</aside>