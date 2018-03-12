<?php
/*
	Post template for single podcasts...
*/

?>
<header>
<p class="meta">							
<?php
if(in_category('in-the-news')){
	if (get_post_custom_values('source_name')!=''){
		echo get_post_custom_values('source_name')[0].'<br />';
	}
}

$link = get_permalink();
$base = basename($link);
$link = str_replace($base."/" ,"",get_permalink());
?>
 <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time>
<?php
	if(function_exists('get_field'))
		if(get_field('author') != "")
			echo ' | <span class="authorName">by '.get_field('author').'</span>';
?>
	<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
	<a href="http://www.addthis.com/bookmark.php" class="addthis_button_expanded addthis_button" style="text-decoration:none;">
        <img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
        width="16" height="16" border="0" alt="Share" /> Share</a>	
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52274afd3385fbef"></script>

<!-- AddThis Button END -->

</p>
</header> <!-- end article  -->
					
<section class="post_content clearfix" itemprop="articleBody">
	<?php 
	echo do_shortcode('[displaypodcasts]');
	echo "<hr />";

	if(has_post_thumbnail()){
		$fullImageURL = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
		$medImageURL = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium');
		$thumbCaption = nl2br(the_post_thumbnail_caption( get_the_id() ));
		if(strlen(trim($thumbCaption)))
			echo '<div class="alignright wp-caption" style="width:'.$medImageURL[1].'px">';
		echo '<a class="fancybox" href="'.$fullImageURL[0].'">';
		the_post_thumbnail( 'thumbnail',array('class'=>'alignright') ); 
		echo '</a>';
		if(strlen(trim($thumbCaption)))
			echo '<p class="wp-caption-text">'.$thumbCaption.'</p></div>';
	}
	
	// Display content. If there isn't any content, output the excerpt...
	if(trim(($post->post_content)) != ""){
		the_content();
	}
	else
		the_excerpt(); 
	?>
	<br />
	<small><a href="<?php echo $link ?>">More podcast episodes</a></small> | <small><a href="<?php echo $link ?>/feed"> Subscribe <img src="http://www.mozilla.org/images/feed-icon-14x14.png" alt="Subscribe" /></a></small>
	<?php
	
	if(strlen($post->post_content) > 1000){
	?>
	<div id="shareBottom" class="addthis_toolbox addthis_default_style ">			
		<a href="http://www.addthis.com/bookmark.php" class="addthis_button" style="text-decoration:none;">
        <img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
        width="16" height="16" border="0" alt="Share" /> Share</a>
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>	
					       
		</div>
		
		
	<?php 
	}
	wp_link_pages(); 
	
	//will always return to home podcast page
	
	?>

</section> <!-- end article section -->

