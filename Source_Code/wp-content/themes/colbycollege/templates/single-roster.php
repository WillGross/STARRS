<?php
/*
	Post template for roster posts...
*/

?>
</header> <!-- end article header -->
					
<section class="post_content clearfix" itemprop="articleBody">
	<?php 
		if(has_post_thumbnail() && get_field('no_featured_image') == ''){
			$fullImageURL = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
			$medImageURL = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium');
			$thumbCaption = nl2br(the_post_thumbnail_caption(  get_the_id() ));
			if(strlen(trim($thumbCaption)))
				echo '<div class="alignright wp-caption" style="width:'.$medImageURL[1].'px">';
			echo '<a class="fancybox" href="'.$fullImageURL[0].'">';
			the_post_thumbnail( 'medium',array('class'=>'alignleft') ); 
			echo '</a>';
			if(strlen(trim($thumbCaption)))
				echo '<p class="wp-caption-text">'.$thumbCaption.'</p></div>';
		}

	//need roster number before title	
	if(get_field('class_year')!="") {
		echo "<p><strong>Class Year:</strong> ".get_field('class_year')."</p>";
	}
	if(get_field('position_event')!="") {
		echo "<p><strong>Position:</strong> ".get_field('position_event')."</p>";
	}
	if(get_field('high_school')!="") {
		echo "<p><strong>High Scool:</strong> ".get_field('high_school')."</p>";
	}
	if(get_field('hometown')!="") {
		echo "<p><strong>Hometown:</strong> ".get_field('hometown')."</p>";
	}
	// Display content. If there isn't any content, output the excerpt...
	echo '<hr />';
	echo the_content();		
	
	wp_link_pages(); ?>

</section> <!-- end article section -->