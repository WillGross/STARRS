	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
		<?php $hideTitle = get_post_custom_values('hidetitle');?>
		<header>					
			<div class="page-header"><?php if(count($hideTitle)==0){?><h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1><?php }else{echo '&nbsp;';}?></div>						
		</header> <!-- end article header -->
	
		<section class="post_content clearfix" itemprop="articleBody">						
		<?php 
			the_content(); 
		?>					
		</section> <!-- end article section -->
		
		<footer>

			<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","bonestheme") . ':</span> ', ', ', '</p>'); ?>
			
		</footer> <!-- end article footer -->
	
	</article> <!-- end article -->