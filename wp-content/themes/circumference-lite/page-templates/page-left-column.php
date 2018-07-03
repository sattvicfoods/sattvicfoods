<?php
/**
 *
   Template Name: Page Left Column
 *
 * Description: A page template with a left column
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
            
			<div class="col-md-8">
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
    	</div><!-- .row -->
	</div><!-- .container -->
</section>


<?php get_footer(); ?>