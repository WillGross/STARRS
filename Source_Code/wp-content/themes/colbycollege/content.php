<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
  
  <header>
  
    <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'wpbs-featured' ); ?></a>
    
    <div class="page-header"><h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3></div>
    
    <!-- <p class="meta"><?php _e("Posted", "bonestheme"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time> <?php _e("by", "bonestheme"); ?> <?php the_author_posts_link(); ?> <span class="amp">&</span> <?php _e("filed under", "bonestheme"); ?> <?php the_category(', '); ?>.</p> -->
  
  </header> <!-- end article header -->

  <section class="post_content clearfix">
    <?php the_content( __("Read more &raquo;","bonestheme") ); ?>
  </section> <!-- end article section -->
  
  <footer>
	<hr />    
  </footer> <!-- end article footer -->

</article> <!-- end article -->
