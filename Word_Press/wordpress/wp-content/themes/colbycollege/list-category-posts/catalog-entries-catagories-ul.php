<?php

global $post;

// get the current user
$current_user = wp_get_current_user();

// global for WP database stuff
global $wpdb;

?>

<?php
$max = 3;		// number of posts to display
$services = '<ul>';

$total = 0;		// total number of posts
while ( have_posts() ) {
	the_post();

        if ( $current_user->ID == 0 ) {         // ID == 0 means the user is not logged in
                // is this post locked down? see if there is a Colby Groups record for this post ID
                $roles = $wpdb->get_row(
                        $wpdb->prepare( "SELECT pwp_215_cc_group_roles.roles FROM pwp_215_cc_group_roles WHERE post_id = " . $post->ID, ARRAY_N )
                );

                $can_view = 1;                  // assume the user can view the post

                if ( $roles ) {                 // if there are any Colby Group roles then the post is locked down
                        $can_view = 0;
                }

        } else {                                // Logged in users see all services on this category
                $can_view = 1;
        }

	if ( is_array( get_field( 'show_in_catalog' ) ) && get_field( 'show_in_catalog' )[0] == 'Yes' && ( !is_array( get_field('show_only_in_a-z') ) || get_field('show_only_in_a-z')[0] !='Yes' ) && $can_view == 1 ) {
		if ( $total < $max ) {
	       	 	$services .= '<li><a href="catalog-entry/' . $post->post_name . '/" title="' . $post->post_title . '"> ' . $post->post_title . '</a></li>';
		}

		$total++;
	}
}

/* if ( $total > $max ) {
	$services .= '<li><a href="../' . $this->params['terms'] . '/" title="more services">more</a></li>';
} */

$services .= '</ul>';

if ( $total > $max ) {
        $services .= '<div style="padding-left: 2em;"><a href="' . $this->params['terms'] . '/" title="see all services">See All &gt;&gt;</a></div>';
}

$this->lcp_output = $services;

?>
