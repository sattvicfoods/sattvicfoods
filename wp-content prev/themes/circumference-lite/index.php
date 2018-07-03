<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
			<div id="cir-content" role="main">
			<?php // get all the posts
				if ( have_posts() ) : while ( have_posts() ) : the_post();				
					// get the article layout
					get_template_part( 'content', get_post_format() );					
				endwhile;
					// get the pagination
					circumferencelite_paging_nav();
				else :
					// if no posts
					get_template_part( 'content', 'none' );					
				endif; 
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