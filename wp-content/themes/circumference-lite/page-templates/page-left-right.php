<?php
/**
 *
   Template Name: Page Left &amp; Right Column
 *
 * Description: A page template with equal width columns (left, content, right)
 * @package Circumference Lite
 * @since 1.0.4
 */

get_header(); ?>


<?php get_sidebar( 'top' ); ?>

<section id="cir-content-area" role="main">
    <div class="container">
        <div class="row">
        	<div class="col-md-4">
                <aside id="cir-left" role="complementary">
                    <?php get_sidebar( 'left' ); ?>
                </aside>
            </div>    
			<div class="col-md-4">
				<?php while ( have_posts() ) : the_post(); ?>
        
                    <?php get_template_part( 'content', 'page' ); ?>
        
                    <?php
                        // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || '0' != get_comments_number() ) :
                            comments_template();
                        endif;
                    ?>
        
                <?php endwhile; // end of the loop. ?>
			</div> 
        	<div class="col-md-4">
                <aside id="cir-right" role="complementary">
                    <?php get_sidebar( 'right' ); ?>
                </aside>
            </div>                  
    	</div><!-- .row -->
	</div><!-- .container -->
</section>


<?php get_footer(); ?>