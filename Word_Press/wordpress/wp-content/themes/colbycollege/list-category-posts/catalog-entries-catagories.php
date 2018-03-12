<?php

global $post;

?>

<?php
$total = 0;     // Total number of services displayed

// get the current user
$current_user = wp_get_current_user();

// global for WP database stuff
global $wpdb;

$services = '<div style="margin: 12px 0px;"><a href="/service-catalog/">Service Catalog</a></div><hr/><div class="sc-table" style="margin-top: 16px;">';

while ( have_posts() ) {
        the_post();

	if ( $current_user->ID == 0 ) {		// ID == 0 means the user is not logged in
		// is this post locked down? see if there is a Colby Groups record for this post ID
		$roles = $wpdb->get_row(
			$wpdb->prepare( "SELECT pwp_215_cc_group_roles.roles FROM pwp_215_cc_group_roles WHERE post_id = " . $post->ID, ARRAY_N )
		);

		$can_view = 1;			// assume the user can view the post

		if ( $roles ) {			// if there are any Colby Group roles then the post is locked down
			$can_view = 0;
		}

	} else {				// Logged in users see all services on this category
		$can_view = 1;
	}

	// Show this service if the "show_in_catalog" checkbox is checked and the "show_only_in_a-z" is not checked and the catalog entry does not require authentication

	if ( is_array( get_field( 'show_in_catalog' ) ) && get_field( 'show_in_catalog' )[0] == 'Yes' && ( !is_array( get_field('show_only_in_a-z') ) || get_field('show_only_in_a-z')[0] !='Yes' ) && ( $can_view == 1 || $post->post_name == 'software-delivery-and-configuration' ) ) {

		$services .= '<div class="sc-row-category">';
		$services .= '<div class="sc-cell-category" style="vertical-align: middle;"><a href="/service-catalog/catalog-entry/' . $post->post_name .  '/">';
		if ( get_field( 'icon' ) != '' ) { $services .= '<div style="float: left; width: 33%; margin-right: 10px; text-align: center;"><i style="color: ' .get_field('color') . '; font-size: 300%;" aria-hidden="true" class="fa ' . get_field( 'icon' ) . '"></i></div>'; }
		$services .= '<div style="margin-left: 38%; x-word-wrap:break-word;"><h3>';
		$services .= $post->post_title;
		$services .= '</h3></div></a></div>';
		$services .= '<div class="sc-cell-empty-category">' . get_field( 'short_description' );
		if ( get_field( 'url' ) != '' ) { $services .= '<br/><a href="/service-catalog/catalog-entry/' . $post->post_name . '/?page=go" title="' . $post->post_title . ' link"><i class="fa fa-link" aria-hidden="true" style="margin-top: 6px;"></i> Quick Link</a>'; }
		$services .= '</div></div>';

	        $total++;
	}
}

if ( $total == 0 ) {
        $services .= 'There are no services for this service area.';
}

$this->lcp_output = $services . '</div>';

$this->lcp_output.=<<<EOT
<div id="sc-instructions"></div>

<script type="text/javascript" language="javascript" src="/javascript/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/style/jquery-ui.min.css" type="text/css" />

<script type="text/javascript">
function showDialog( url, title, icon ) {
	jQuery(document.body).css({'cursor' : 'wait'});
	jQuery('.fa').css({'cursor' : 'wait'});

	jQuery('#sc-instructions').load('/service-catalog/catalog-entry/'+url+'/?page=simple', function() {
		jQuery('#sc-instructions').dialog( {
			width: '80%',
			title: title,
			modal: true,
			buttons: { OK: function() { jQuery( this ).dialog("close"); } },
			maxHeight: "600px",
			open: function() {
				jQuery( '#sc-title-icon' ).remove();
				jQuery( this ).parent().children( ".ui-dialog-titlebar" ).prepend( '<i aria-hidden="true" id="sc-title-icon" class="fa '+icon+'"></i>' );
			}
		} );

		jQuery(document.body).css({'cursor' : 'default'});
		jQuery('.fa').css({'cursor' : 'default'});
	});

	return false;
}
</script>
EOT;
?>
