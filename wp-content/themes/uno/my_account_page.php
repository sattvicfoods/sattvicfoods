<?php
/**
 * Template Name: My Account Page
 *
 */

get_header();
?>
       
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
    	<div id="main-sidebar-container">    

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="my_account_main">  
				<?php if ( is_user_logged_in() ) { ?>
					
					  <div class="welcome_text">
							<h3>Hello, <span><?php echo esc_html( $current_user->display_name ); ?></span></h3>
						<p>From your account dashboard you can view your recent orders, manage your shipping and billing addresses and edit your password and account details.</p>    
					  </div>
					  <?php
							woo_loop_before();
							
							if (have_posts()) { $count = 0;
								while (have_posts()) { the_post(); $count++;
									woo_get_template_part( 'content', 'page' ); // Get the page content template file, contextually.
								}
							}
							
							woo_loop_after();
					  ?>     
			
				<?php } else { ?>
				 <section id="main">
					<?php
						woo_loop_before();
						
						if (have_posts()) { $count = 0;
							while (have_posts()) { the_post(); $count++;
								woo_get_template_part( 'content', 'page' ); // Get the page content template file, contextually.
							}
						}
						
						woo_loop_after();
					?>     
				 </section>
					<?php get_sidebar(); ?>
				<?php } ?>
									 
				
            </section><!-- /#main -->
    
	</div><!-- /#main-sidebar-container -->         

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>