<?php

global $post;

?>

<?php
$total = 0;     // Total number of services displayed

// get the current user
$current_user = wp_get_current_user();

// global for WP database stuff
global $wpdb;

// icons for certain taxonomy slugs (yup, they're hardcoded)
$icons = array(
	'accounts-email-and-calendar' => 'fa-user-circle-o',
	'business-and-administrative-systems' => 'fa-university',
	'events-and-classroom-logistics' => 'fa-calendar-check-o',
	'_getting-started-at-colby' => 'fa-user-plus',
	'help-and-support' => 'fa-question-circle',
	'information-security' => 'fa-key',
	'internet-and-tv' => 'fa-wifi',
	'phone-and-mobile' => 'fa-phone',
	'printers-copiers-and-scanners' => 'fa-print',
	'research-computing' => 'fa-microchip',
	'servers-backup-and-storage' => 'fa-archive',
	'software-and-hardware' => 'fa-laptop',
	'teaching-and-learning' => 'fa-graduation-cap',
	'web-publishing-and-development' => 'fa-cloud-download'
);

// $services holds the HTML that will be printed
$services = '<div class="sc-table">';

// this will hold all of the service-areas taxonomy values
$taxonomy = array();

// loop through all of the posts
while ( have_posts() ) {
	the_post();

	// get the values for the 'service-areas' taxonomy for this post
	$terms = get_the_terms( $post, 'service-areas');

	// if this post has taxonomy terms...
	if ( $terms ) {
		// ...loop through the terms...
		foreach ( $terms as $term ) {
			// ...if we haven't saved this taxonomy...
			if ( array_key_exists( $term->slug, $taxonomy ) == FALSE ) {
				// ...save it...
				$taxonomy[ $term->slug ] = array();
				$taxonomy[ $term->slug ][ 'name' ] = $term->name;

				// ...and set the icon
				if ( array_key_exists( $term->slug, $icons ) ) {
					$taxonomy[ $term->slug ][ 'icon' ] = $icons[ $term->slug ];
				} else {
					$taxonomy[ $term->slug ][ 'icon' ] = '';
				}
			}
		}
	}
}

// sort the taxonomy (key is the "slug" value)
ksort( $taxonomy );

$total = 0;
foreach ( $taxonomy as $slug => $value) {
	// rows of two (CSS is used to handle rows of one for mobile)
	if ( $total % 2 == 0 ) {
		$services .= '<div class="sc-row">';
	}

	// the "Getting Start @ Colby" service area needs to be hardcoded
	$colorStyle='';
	if ( $slug == '_getting-started-at-colby' ) { 
		$colorStyle = " style='background-color: #ffc !important;'";
		$services .= '<div class="sc-cell" id="' . $slug . '" style="background-color: #ffc !important;"><div class="sc-icon"><a href="' . $slug . '/">';
		if ( $taxonomy[ $slug ][ 'icon' ] ) { $services .= '<i class="fa ' . $taxonomy[ $slug ][ 'icon' ] . '" aria-hidden="true"></i>'; }
                $services .= '</a></div><div class="sc-info"><h3><a href="' . $slug . '/">' . $taxonomy[ $slug ][ 'name' ] .'</a></h3><ul>';
		$services .= '<li><a href="faculty/">Faculty</a></li>';
		$services .= '<li><a href="students/">Students</a></li>';
		$services .= '<li><a href="staff/">Staff</a></li>';
		$services .= '<li><a href="alumni/">Alumni</a></li>';
		$services .= '<li><a href="parents/">Parents</a></li>';
		$services .= '</ul></div></div>';
		
	} else {
		// print out information for the service area, run the shortcode to pull in services for this service area
		$services .= '<div class="sc-cell" id="' . $slug . '" ' . $colorStyle . '><div class="sc-icon"><a href="' . $slug . '/">';
		if ( $taxonomy[ $slug ][ 'icon' ] ) { $services .= '<i class="fa ' . $taxonomy[ $slug ][ 'icon' ] . '" aria-hidden="true"></i>'; }
		$services .= '</a></div><div class="sc-info"><h3><a href="' . $slug . '/">' . $taxonomy[ $slug ][ 'name' ] .'</a></h3>' . do_shortcode( '[catlist taxonomy="service-areas" post_type="catalog-entry" terms="' . $slug . '" customfield_orderby="views" order="DESC" template="catalog-entries-catagories-ul"]' ) . '</div></div>';
	}

	// close the row of two
	if ( $total % 2 == 1 ) {
		$services .= '</div>';
	}

	// another one done
	$total++;
}

// let people know that we know when a service area has no services
if ( $total == 0 ) {
        $services .= 'There are no services in any service area.';
}

$this->lcp_output = $services . '</div>';
?>
