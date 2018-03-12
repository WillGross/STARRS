<?php 
	$curSite = get_current_site();
	if(get_bloginfo('name')=='Events'){
		// Events...load single event.
		include('single.php');
		exit();
	}
	
	
	function custom_excerpt_more( $output ) {
	  if ( has_excerpt() && ! is_attachment() ) {
	   		$output = strip_tags($output,'<b><strong><i><em>');
	  }
	  return $output;
	}
	
	add_filter( 'get_the_excerpt', 'custom_excerpt_more' );
	

	
	get_header(); 
	$pageCat = get_query_var('cat');
	
	$archiveFilters = false;
	

?>			<div id="content" class="clearfix row-fluid">
			
				<div id="main" class="span8 clearfix" role="main">
				
					<div class="page-header">
					<?php
						 if (is_category()) { 
							 
							if(($pageCat==12 || $pageCat==11 || $pageCat==10) && get_bloginfo('name') == 'News'){
						?>
						<h1 class="page-title h2">
							<?php 
							if($pageCat==12){
								echo 'Press Releases';	
								$archiveFilters = true;
							}
							if($pageCat==11){
								echo 'Colby in the News';	
								$archiveFilters = true;
							}
							if($pageCat==10){
								echo 'News';	
								$archiveFilters = true;
							} ?>
						</h1>						
					<?php				
							}							
							else{
								$category = single_cat_title('',false);
								if($category=='Type of News'){
									$category = 'All Colby News';
									$archiveFilters = true;
								}
								
								if($category == 'x_Colby Magazine') {
									$category = 'Colby Magazine';
								}
					?>
						<h1 class="archive_title h2 page-title">
							<span><?php _e("", "bonestheme"); ?></span> <?php echo $category;?>
						</h1>						
					<?php
							} 
						} elseif (is_tag()) { 

							$tag = explode(',',get_query_var('tag'));
						?> 
						<h1 class="page-title">
							<span><?php 
								if (!is_tag('faculty-accomplishments')) {
									echo 'Tagged: ';
								} ?></span> <?php 
								// Functionality for multiple tags...
								foreach($tag as $tagitem) {
									if(isset($tagItem))
										echo ', ';
									$tagItem = get_term_by('slug',$tagitem,'post_tag');
									echo $tagItem->name;
								}
							 ?>
						</h1>
					<?php } elseif (is_author()) { ?>
						<h1 class="archive_title h2 page-title">
							<span><?php _e("Posts By:", "bonestheme"); ?></span> <?php get_the_author_meta('display_name'); ?>
						</h1>
					<?php } elseif (is_day()) { ?>
						<h1 class="archive_title h2 page-title">
							<span><?php _e("Daily Archives:", "bonestheme"); ?></span> <?php the_time('l, F j, Y'); ?>
						</h1>
					<?php } elseif (is_month()) { ?>
					    <h1 class="archive_title h2 page-title">
					    	<span><?php _e("Monthly Archives:", "bonestheme"); ?></span> <?php the_time('F Y'); ?>
					    </h1>
					<?php } elseif (is_year()) { ?>
					    <h1 class="archive_title h2 page-title">
					    	<span><?php _e("Yearly Archives:", "bonestheme"); ?></span> <?php the_time('Y'); ?>
					    </h1>
					    
					   
					<?php } elseif (get_post_type()==='podcast') { 
						//If post type is a podcast will include an RSS feed
						//current feed is on the page http://author.colby.edu/newtest/podcast/feed/, no style associated with it
						query_posts($query_string . '&orderby=date&order=DESC');
						//bloginfo('rss2_url');
			
						?>
					
					    <h1 class="page-title archive_title h2">
					    	<span><?php echo get_option('colbyPodcast_title');/* echo post_type_archive_title(); */?></span>
					    </h1>					    
						
					    
					<?php }elseif(get_post_type() !== false){ 
							// Sort by name...
							query_posts($query_string . '&orderby=title&order=ASC');
					?>
						<h1 class="page-title archive_title h2">
					    	<span><?php echo post_type_archive_title();?>
					    </h1>
							
					<?php
					}
					
					if($archiveFilters){
						// Archive filters. Display.
						$instance = 'title=&category_id='.$pageCat.'&display_style=pulldown&show_counts=1';
						

						
						?>
						<div id="archiveFilters" class="clearfix span12">
							<div class="clearfix span6">
							<?php
								the_widget( 'WP_Category_Archive_Widget', $instance, $args );
							?>
							</div>							
							<div class="clearfix span6">
							<!-- Output search form for category -->
							<form role="search" method="get" id="searchform" action="">
							  <input type="text" value="" name="s" id="s" placeholder="Enter search term...">
							  <input type="submit" id="searchsubmit" value="Search">
							  </form>
						</div>
						<div class="span11">
							<hr />
							</div>

						</div>
						 <?php
						
					}
					?>
					</div>

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<header>							
							<?php
								if(has_post_thumbnail()){ 
									?>
									<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php
									the_post_thumbnail( 'thumbnail',array('class'=>'alignright')); 
									?></a><?php
								}	
								?>
							<h3 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							<?php
							if(get_post_type() === false || in_category('in-the-news') || in_category('news') || in_category('athletics-news') || in_category('colby-news') || in_category('press-release')){?>
							<p class="meta"><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php echo date('F d, Y',strtotime($post->post_date)); ?></time></p>
							<?php }?>
							
						</header> <!-- end article header -->
					
						<section class="post_content">
						
							<?php echo get_the_excerpt(); ?>
					
						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php endwhile; ?>	
					
					<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
						
						<?php page_navi(); // use the page navi function ?>

					<?php } else { // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next">
							<ul class="clearfix">
								<li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', "bonestheme")) ?></li>
								<li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', "bonestheme")) ?></li>
							</ul>
						</nav>
					<?php } ?>
								
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("No Posts Yet", "bonestheme"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, What you were looking for is not here.", "bonestheme"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->

					<?php 
					if (get_post_type()==='podcast') {?>
		    			<div id="sidebar1" class="fluid-sidebar sidebar span4" role="complementary">
		    				<div class="widget">
								<a href="<?php echo get_site_url() ?>/podcast/feed/">Subscribe <img src="http://www.mozilla.org/images/feed-icon-14x14.png" alt="RSS Feed" title="RSS Feed" /></a><br /><?php
								echo "<div><h3>" . get_option('colbyPodcast_title') . "</h3>";
								if(strlen(get_option('colbyPodcast_authorName')))
									echo '<div>'.get_option('colbyPodcast_authorName') . "</div>";
								if(get_option('colbyPodcast_authorEmail') != "")
									echo '<a href="mailto:'.get_option('colbyPodcast_authorEmail').'">'.get_option('colbyPodcast_authorEmail') . "</a>";
								if(strlen(get_option('colbyPodcast_description'))) {
									echo '<hr />';
									echo get_option('colbyPodcast_description') . "<br /></div>";
								}
					?>		</div>
					</div><?php
					} ?>    				

    			
				<?php get_sidebar(); // sidebar 1 ?>
				
				
    
			</div> <!-- end #content -->

<?php get_footer(); ?>