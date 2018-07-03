<?php
/**
 * Post Format Link
 * @package Circumference Lite
 * @since 1.0.4
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	

<div class="entry-content clearfix">
 
  <?php if ( has_post_thumbnail()) : ?>
  
    <div class="link-thumbnail">
      <a target="_blank" href="<?php echo esc_url( circumferencelite_get_link_url() ); ?>"><?php the_post_thumbnail(); ?></a>
      </div>

      
    <div class="entry-content-link">
    <?php else : ?>
    <div>
	<?php endif; ?>    
      <header class="entry-header">
       <h2><a target="_blank" href="<?php echo esc_url( circumferencelite_get_link_url() ); ?>"><?php the_title(); ?></a></h2>
        </header>
      <?php the_content( __( 'Continue reading...', 'circumference-lite' ) ); ?>
    </div>
      
  
 
</div><!-- .entry-content -->

</article><!-- #post-## -->

<div class="article-separator"></div>