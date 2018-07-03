<?php
/**
 * The Template for displaying all single posts.
 * @package Circumference Lite
 * @since 1.0.4
 */

get_header(); ?>

<section id="cir-content-area" role="main">

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div id="cir-content" role="main">
				<?php
                // get the full post
                    while ( have_posts() ) : the_post(); 
                        get_template_part( 'content', 'single' ); 
                        if( get_theme_mod( 'hide_postnav' ) == '') { 
                            circumferencelite_post_nav();
                        }
                        // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || '0' != get_comments_number() ) :
                            comments_template();
                        endif;
                    endwhile;
                ?>
			</div>
		</div>
        
        <div class="col-md-4">
            <aside id="cir-right" role="complementary">
                <?php get_sidebar( 'right' ); ?>
            </aside>
        </div>
        
	</div>
</div>
		

</section>


<?php
get_footer();