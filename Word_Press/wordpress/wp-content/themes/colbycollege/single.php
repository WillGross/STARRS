<?php 
	$postTemplate = true;	// Load post template indicator
	$showSidebar = true;
	
	if( isset($_GET['print'])) {
		$showSidebar = false;
	}
	
	if($linkURL = get_field('link_url')){
		if(strpos($linkURL,'://')===false)
			$linkURL = 'http://'.trim($linkURL);
		if(!(filter_var($linkURL, FILTER_VALIDATE_URL) === FALSE))
			header("Location: ".$linkURL);
	}
	
	if($post->post_type == "a_z_terms"){
		// A-Z Item...301 redirect to the page
		header("HTTP/1.1 301 Moved Permanently"); 
		$redirectURL = get_field('resource_url',$post->ID);
		if(!strlen($redirectURL)){
			$redirectURL = 'http://www.colby.edu';
		}
		
		if(!stripos($redirectURL,'://'))
			$redirectURL = 'http://'.$redirectURL;
		
		$redirectURL = str_ireplace('http://www.colby.edu','',$redirectURL);
		
		header("Location: ".$redirectURL);
	}
	if(in_category('Requirements')) {
		$showSidebar = false;
	}
	
	get_header(); ?>
			<div id="content" class="clearfix row-fluid">
			
				<?php
				if ($showSidebar)
					echo '<div id="main" class="span8 clearfix" role="main">';
				else
					echo '<div id="main" class="span12 clearfix" role="main">';
				?>
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>							
							<div class="page-header"><h1 class="single-title" itemprop="headline">
							<?php 
								if($post->post_type=='roster' && get_field('uniform_number', $post->ID) != '') {
									echo "#".get_field('uniform_number', $post->ID)." ";
								}
								the_title(); 
							?></h1></div>
							
							<?php							
							if(in_category('event') || in_category('featured-event')){
								get_template_part( 'templates/single', 'event' );
								$postTemplate = false;
							}
							
							if($post->post_type=='podcast')
								get_template_part( 'templates/single', 'podcast' );
							else
							if(in_category('Requirements'))//should also limit to catalogue page only
								get_template_part( 'templates/single', 'requirement');
							else	
							if($post->post_type=='roster')
								get_template_part( 'templates/single', 'roster' );
							else {

								if($postTemplate) {
									get_template_part( 'templates/single', 'post' );
								
									}
							}
							?>
												
						<footer>
			
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","bonestheme") . ':</span> ', ' ', '</p>'); ?>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php 

					comments_template('',true); ?>
					
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
    
				<?php 
				// If this is the althletics website and there are any teams categorized, output the names/links for the team pages.
				if(get_bloginfo('name')=='Athletics') {
					get_template_part('templates/sidebar','athleticsnews');
				}
				
				if ($showSidebar)
					get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>