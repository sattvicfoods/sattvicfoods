<?php
/**
 * The template for displaying Archive, tag, and category pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * @package Circumference Lite
 * @since 1.0.4
 */

get_header(); ?>

<?php get_sidebar( 'top' ); ?>

<section id="cir-content-area" role="main">
	<div class="container">
  		<div class="row">
    		<div class="col-md-12">

    
                <header class="page-header">
					<h1 class="page-title">
                        <?php
                            if ( is_category() ) :
                                single_cat_title();
    
                            elseif ( is_tag() ) :
                                single_tag_title();
    
                            elseif ( is_author() ) :
                                printf( __( 'Articles by %s', 'circumference-lite' ), '<span class="vcard">' . get_the_author() . '</span>' );
    
                            elseif ( is_day() ) :
                                printf( __( 'Articles from %s', 'circumference-lite' ), '<span>' . get_the_date() . '</span>' );
    
                            elseif ( is_month() ) :
                                printf( __( 'Articles from %s', 'circumference-lite' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'circumference-lite' ) ) . '</span>' );
    
                            elseif ( is_year() ) :
                                printf( __( 'Articles from %s', 'circumference-lite' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'circumference-lite' ) ) . '</span>' );
    
                            elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
                                _e( 'Asides', 'circumference-lite' );
    
                            elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
                                _e( 'Galleries', 'circumference-lite');
    
                            elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
                                _e( 'Images', 'circumference-lite');
    
                            elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
                                _e( 'Videos', 'circumference-lite' );
    
                            elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
                                _e( 'Quotes', 'circumference-lite' );
    
                            elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
                                _e( 'Links', 'circumference-lite' );
    
                            elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
                                _e( 'Statuses', 'circumference-lite' );
    
                            elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
                                _e( 'Audios', 'circumference-lite' );
    
                            elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
                                _e( 'Chats', 'circumference-lite' );
    
                            else :
                                _e( 'Archives', 'circumference-lite' );
    
                            endif;
                        ?>
					</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header>



            </div>
        </div>
    </div>
    
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div id="cir-content" role="main">
				<?php	
                // get all the posts
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

   
    
</section><!-- #primary -->


<?php get_footer(); ?>
