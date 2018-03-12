<?php
/*
Template Name: Three Column Front Page Template
*/
	// Enqueue styles specific to this template...
	$themeDetails = wp_get_theme();
	wp_register_style( '3columnstyles', get_template_directory_uri() . '/library/css/3-column-template.css', array(), $themeDetails->Version, 'all' );
	wp_enqueue_style( '3columnstyles');

?>

<?php get_header(); ?>
			
			<div id="content" class="clearfix row-fluid">
			
				<div id="main" class="span12 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<header>
						<!-- remove page title 
							<div class="page-header"><h1 class="page-title"><?php the_title(); ?></h1></div>
						-->	
						</header> <!-- end article header -->
					
						<section class="post_content clearfix">
							
<div class="span5">
	<div id="left-column">
		<?php echo the_content();?>
	</div>
</div>

<div class="span4">
 <div id="center-column">
	<?php
		get_sidebar('sidebar3');
	?>
</div><!-- end center-column -->
</div><!-- end span4 -->

<div class="span3"> <!-- wrapper for right column - testing to see effect of span classes -->
	<div id="right-column"><?php 
	if ( is_active_sidebar( 'sidebar2' ) )
		dynamic_sidebar( 'sidebar2' );
?>
	</div><!-- end right-column -->	
</div> <!-- end span3 -->

<?php 
	if ( is_active_sidebar('footer1')){
		dynamic_sidebar('footer1');

}?>
			
						</section> <!-- end article section -->
						
						<footer>
			
							<p class="clearfix"><?php the_tags('<span class="tags">' . __("Tags","bonestheme") . ': ', ', ', '</span>'); ?></p>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php comments_template(); ?>
					
					<?php endwhile; ?>	
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "bonestheme"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "bonestheme"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
    
				<?php //get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>