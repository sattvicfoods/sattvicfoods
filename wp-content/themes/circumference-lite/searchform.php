<?php
/**
 * The template for displaying search forms in Circumference
 * @package Circumference Lite
 * @since 1.0.4
 */
?>


<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'circumference-lite' ); ?></span>
<div class="input-group">
      <input type="text" class="form-control" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'circumference-lite' ); ?>">Go!</button>
      </span>
    </div><!-- /input-group -->
</form>    