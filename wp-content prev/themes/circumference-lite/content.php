<?php
/**
 * Main content 
 * @package Circumference Lite
 * @since 1.0.4
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title">		
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<span class="featured-post">
			<?php _e( 'Featured', 'circumference-lite' ); ?>
		</span>
	<?php endif; ?>
		
		<?php if( get_theme_mod( 'hide_edit' ) == '') { ?>
		<?php edit_post_link( __( 'Edit', 'circumference-lite' ), '<span class="edit-link">', '</span>' ); ?>
        <?php } ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php if(the_title( '', '', false ) !='') the_title(); else _e('Untitled', 'circumference-lite'); ?> </a>
        </h1>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php circumferencelite_posted_on(); ?>            
            <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
            <span class="comments-link">
				<?php 
                    echo '<span class="entry-comments">';
                    _e( 'Comments: ', 'circumference-lite' );
                    comments_popup_link( __( 'Leave a comment', 'circumference-lite' ), __( '1 Comment', 'circumference-lite' ), __( '% Comments', 'circumference-lite' ) ); 
                endif; ?> 
                </span>
            </span>       
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

<div class="entry-content clearfix">

	<?php if ( has_post_thumbnail()) :?>
            <div class="post-thumbnail" <?php if( get_theme_mod( 'circumference-hide-hover-effect' ) == '' ) echo "id=make-image-hover"; ?> >
              	<?php the_post_thumbnail();
        echo '</div>';
    endif; ?>
        	
	<?php 
        $excon = get_theme_mod( 'excerpt_content', 'content' );
        $excerpt = get_theme_mod( 'excerpt_limit', '50' );
             switch ($excon) {
                case "content" :
                    the_content(__('Continue Reading...', 'circumference-lite'));
                break;
                case "excerpt" : 
                    echo '<p>' . circumferencelite_excerpt($excerpt) . '</p>' ;
                    echo '<p class="more-link"><a href="' . get_permalink() . '">' . __('Continue Reading...', 'circumference-lite') . '</a>' ;
                break;
        }
    ?>   
          
        
  
        
		
	</div><!-- .entry-content -->

	<footer class="summary-entry-meta">
		<?php circumferencelite_multi_pages(); ?>
    </footer><!-- .entry-meta -->
    
</article><!-- #post-## -->

<div class="article-separator"></div>
