<?php
/*
Template Name: Front Page Template (Sectional)
*/
?>

<?php get_header(); ?>
			<div id="content" class="clearfix row-fluid">
			
				<div id="main" class="span12 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<header>							
							&nbsp;
						</header>
						<section class="post_content">
							<?php the_content(); ?>					
						</section>						
						<footer>			
							<p class="clearfix"><?php the_tags('<span class="tags">' . __("Tags","bonestheme") . ': ', ', ', '</span>'); ?></p>							
						</footer>					
					</article>
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
				</div>
			</div>
<?php get_footer(); ?>