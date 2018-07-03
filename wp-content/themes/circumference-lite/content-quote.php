<?php
/**
 * Post Format Quote
 * @package Circumference Lite
 * @since 1.0.4
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	

<div class="entry-content">
  <div class="row">
  <?php if ( has_post_thumbnail()) : ?>
  <div class="col-md-3">
    <div class="testimonial-thumbnail">
      <?php the_post_thumbnail(); ?>
      </div>
     </div>
    <?php endif; ?>
    <?php if ( has_post_thumbnail()) : ?>
    <div class="col-md-9">
    <?php else : ?>
     <div class="col-md-12">
     <?php endif; ?>
      <header class="entry-header">
        <h1 class="entry-title"><span class="icon-quotes-left"></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
        </header>
      <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'circumference-lite' ) ); ?>
      </div>
  
  </div>
</div><!-- .entry-content -->

</article><!-- #post-## -->

<div class="article-separator"></div>