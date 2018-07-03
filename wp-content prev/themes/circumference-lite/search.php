<?php
/**
 * The template for displaying Search Results pages.
 * @package Circumference Lite
 * @since 1.0.4
 */

get_header(); ?>

<section id="cir-content-area" style="background-color: #ffffff;" role="main">
	<div class="container">
  		<div class="row">
    		<div class="col-md-12">
 
                <header class="page-header">
					<h1 class="page-title">
						<?php printf( __( 'Search Results for: %s', 'circumference-lite' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header><!-- .page-header -->
			</div>
		</div>
        <div class="row">
        	<div class="col-md-12">
            	<div id="cir-content" role="main">          
					<?php if ( have_posts() ) : ?>
                        <?php /* Start the Loop */ ?>
                        <?php while ( have_posts() ) : the_post(); ?>
            
                            <?php get_template_part( 'content', 'search' ); ?>
            
                        <?php endwhile; ?>
            
                        <?php circumferencelite_paging_nav(); ?>
            
                    <?php else : ?>
            
                        <?php get_template_part( 'content', 'none' ); ?>
            
                    <?php endif; ?>
				</div>
             </div>
          </div>
	
   </div>
</section>	


<?php get_footer(); ?>