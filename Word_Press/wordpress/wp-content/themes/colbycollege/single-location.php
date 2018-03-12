<?php get_header(); ?>
			
			<div id="content" class="clearfix row-fluid">
			
				<div id="main" class="span8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>						
							<?php the_post_thumbnail('medium',array('class'=>'alignright img-polaroid')); ?>							
							<div class="page-header"><h1 itemprop="headline" class="single-title"><?php 
							/* If on campus map, make name link to full page */
							if($_GET["more"]!=1){
								$permalink = get_permalink() . "?more=1";

							the_title();

							}
							else{
							the_title();} ?></h1></div>						
						</header> <!-- end article header -->
					
						<section class="post_content clearfix" itemprop="articleBody">

							<?php 
							// Display content. If there isn't any content, output the excerpt...
							if($post->post_content != ""){
								the_content();
							}
							else
								the_excerpt(); ?>
							<!-- If not viewed on campus map, show text in 'more' field -->
							<?php if($_GET["more"]==1){
								echo(get_post_meta(get_the_ID(),'wpcf-more')[0]);
							}
							else{
								/* If on campus map, display 'More…' link if there's text in the field */
								if(get_post_meta(get_the_ID(),'wpcf-more')[0]!=''){
									print("<a href='$permalink'>More...</a>");
									}
							}
							?>
						</section> <!-- end article section -->
					</article> <!-- end article -->
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
    
				<?php get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>