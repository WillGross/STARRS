<?php
// Sidebar that appears with athletics news....

$categories = wp_get_post_categories($post->ID);
if(count($categories)) {
	echo '<div class="fluid-sidebar sidebar span4">';
	echo '<h3>Related Teams:</h3>';
	foreach($categories as $category) {
		if(cat_is_ancestor_of(get_cat_ID('athletics teams'),$category)) {
			// Found team. Output with link
			$teampage = get_posts(array('post_type'=>'sport','category'=>$category));
			if(count($teampage)) {
				if (count(get_field('gender',$teampage[0]->ID)) > 1 ) {
					$teamtitle = get_the_title($teampage[0]->ID);
				}
				else {
					$teamtitle = get_field('gender',$teampage[0]->ID)[0] . " " . get_the_title($teampage[0]->ID);
				}					
				echo '<div><a class="label" href="'.get_permalink($teampage[0]->ID).'">'.$teamtitle.'</a></div>';
			}
		}
	}
	echo '</div>';
}