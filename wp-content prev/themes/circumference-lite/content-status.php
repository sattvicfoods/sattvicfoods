<?php
/**
 * Post Format Status
 * @package Circumference Lite
 * @since 1.0.4
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="row">
<div class="col-md-2">
<?php echo get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'circumferencelite_status_avatar', '90' ) ); ?>
</div>
<div class="col-md-10">

<header class="entry-header">
    <h2 class="entry-title"><?php edit_post_link( __( 'Edit', 'circumference-lite' ), '<span class="edit-link">', '</span>' ); ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<?php if ( 'post' == get_post_type() ) : ?>
        <div class="entry-meta">
        <span class="post-format">
				<span class="icon-forward post-format-icon"></span><a class="entry-format" href="<?php echo esc_url( get_post_format_link( 'status' ) ); ?>"><?php echo get_post_format_string( 'status' ); ?></a>
			</span>
            <?php circumferencelite_posted_on(); ?>            
        </div><!-- .entry-meta --> 
   <?php endif; ?>      
</header>




	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'circumference-lite' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'circumference-lite' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
			<?php get_template_part( 'author-bio' ); ?>
		<?php endif; ?>
	</footer><!-- .entry-meta -->

</div>

</div>

</article><!-- #post -->

<div class="article-separator"></div>