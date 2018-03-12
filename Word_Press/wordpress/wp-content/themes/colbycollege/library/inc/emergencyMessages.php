<?php
/*
	Emergency Message Check...
	Returns false if no emergency messages. Returns array with placement, message and title of message otherwise.
*/

function check_emercencymessages(){
	global $post;
	$emergencyMessage = false;

	//$mainSite = $blog_details = get_blog_details(1);
	//switch_to_blog($mainSite->blog_id);

	if ( false === ( $postslist = get_transient( 'emergency_homepage' ) ) ) {
    	// Cache to speed up future queries. This is automatically cleared when an emergency message is saved...
    	$args = array( 	'post_type' => 'emergencymessage',
					'posts_per_page' => 1,
					'offset'=> 0,
					'no_found_rows' => 'true',
					'update_post_meta_cache' => 'false',
					'update_post_term_cache' => 'false' );

    	$postslist = new WP_Query( $args );

    	set_transient( 'emergency_homepage', $postslist, .08 * HOUR_IN_SECONDS );

    }

	if( $postslist->have_posts() ){

		while ( $postslist->have_posts() ) : $postslist->the_post();

			$placement = get_field('placement', get_the_ID() );

			if(strlen($placement) && $placement != 'inactive') {
				$emergencyMessage = array(  'placement' => $placement,
											'post_content' => get_the_content(),
											'post_title' => get_the_title() );
			}

		endwhile;
	}

	wp_reset_postdata();

	return $emergencyMessage;
}
