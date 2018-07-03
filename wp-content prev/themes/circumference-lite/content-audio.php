<?php
/**
 * Post Format audio
 * @package Circumference Lite
 * @since 1.0.4
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
   
    <?php 
		if ( has_post_thumbnail()) :
			echo '<div class="audio-thumbnail">';
				the_post_thumbnail();
			echo '</div>';
		endif; 
	?>
   
   <div class="entry-content"> 
        <header>
        <h1 class="entry-title"><?php edit_post_link( __( 'Edit', 'circumference-lite' ), '<span class="edit-link">', '</span>' ); ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php if(the_title( '', '', false ) !='') the_title(); else _e('Untitled', 'circumference-lite'); ?> </a>
        </h1>        
	</header>
		<?php the_content(); ?>
	</div><!-- .entry-content -->
    
	<footer class="entry-meta"></footer>
    
</article><!-- #post-## -->

<div class="article-separator"></div>