<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Circumference Lite
 * @since 1.0.4
 */

get_header(); ?>

<?php get_sidebar( 'top' ); ?>

<section id="cir-content-area" role="main">
    <div class="container">
        <div class="row">    
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
          
            <div class="col-md-4">
                <aside id="cir-right" role="complementary">
                    <?php get_sidebar( 'right' ); ?>
                </aside>
            </div>
            
    	</div><!-- #main -->
	</div><!-- #primary -->
</section>

<?php
get_footer();
