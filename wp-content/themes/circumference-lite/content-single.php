<?php
/**
 * Full post content
 * @package Circumference Lite
 * @since 1.0.4
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title">
		<?php if( get_theme_mod( 'hide_edit' ) == '') { ?>
		<?php edit_post_link( __( 'Edit', 'circumference-lite' ), '<span class="edit-link">', '</span>' ); ?>
		<?php } ?>
		<?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php circumferencelite_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
<div class="entry-content clearfix">

<?php if( get_theme_mod( 'hide_featured' ) == '') { ?>
	<?php // do not show featured image if post is paged
    $paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : false;
       if ( $paged === false ): 
       
       if ( has_post_thumbnail()) :          
                    echo '<div class="post-thumbnail">';
                        the_post_thumbnail();
                    echo '</div>';             
        endif; 
        
    endif; ?>
<?php } ?>
	
		<?php the_content(); ?>
		
	</div><!-- .entry-content -->

	<footer class="entry-meta">	
    <?php if( get_theme_mod( 'hide_postinfo' ) == '') { ?>	
			<?php the_tags(__('<span class="meta-tagged">Tagged with:<span class="entry-meta-value">', 'circumference-lite') . ' ', ', ', '</span></span><br />'); ?> 
			<?php printf(__('<span class="meta-posted">Posted in:<span class="entry-meta-value"> %s', 'circumference-lite'), get_the_category_list(', ')); ?></span></span> <br />
            <?php printf( __( '<span class="meta-author">Articles by %s', 'circumference-lite' ), '<span class="vcard entry-meta-value"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span></span>' ); ?><br />
            <span class="meta-date"><?php _e('Published: ', 'circumference-lite');?> <span class="entry-meta-value"><?php the_time(get_option('date_format')); ?></span></span>    <?php } ?>
			<?php circumferencelite_multi_pages(); ?>
    </footer><!-- .entry-meta -->
</article><!-- #post-## -->
