<?php

global $post;

?>

<?php
$total = 0;     // Total number of services displayed

// get the current user
$current_user = wp_get_current_user();

// global for WP database stuff
global $wpdb;

$services = '<div style="margin-top: 16px;">';

while ( have_posts() ) {
        the_post();

	if ( $current_user->ID == 0 ) {		// ID == 0 means the user is not logged in
		// is this post locked down? see if there is a Colby Groups record for this post ID
		$roles = $wpdb->get_row(
			$wpdb->prepare( "SELECT twp_116_cc_group_roles.roles FROM twp_116_cc_group_roles WHERE post_id = " . $post->ID, ARRAY_N )
		);

		$can_view = 1;			// assume the user can view the post

		if ( $roles ) {			// if there are any Colby Group roles then the post is locked down
			$can_view = 0;
		}

	} else {				// Logged in users see all services on this category
		$can_view = 1;
	}

	$show = 1;
	if ( $this->params[ 'customfield_value' ] == '' && get_field( 'division') != '' ) {
		$show = 0;
	}

	if ( $show ) {
		// show in catalog?
		if ( get_field( 'show_in_catalog' ) == '' ) {
			$services .= '<i aria-hidden="true" class="fa fa-circle-o" title="Hide in Catalog"></i>&nbsp;';
		} else {
			$services .= '<i aria-hidden="true" class="fa fa-check-circle-o" title="Show in Catalog"></i>&nbsp;';
		}

		// software?
		if ( get_field( 'software' ) == '' ) {
	                $services .= '<i aria-hidden="true" class="fa fa-circle-o" title="Not Software"></i>&nbsp;';
	        } else {
	                $services .= '<i aria-hidden="true" class="fa fa-check-circle-o" title="Software"></i>&nbsp;';
	        }

		// A-Z only
		if ( get_field( 'show_only_in_a-z' ) == '' ) {
	                $services .= '<i aria-hidden="true" class="fa fa-circle-o" title="Show in Listings"></i>&nbsp;';
	        } else {
	                $services .= '<i aria-hidden="true" class="fa fa-check-circle-o" title="Show only in A-Z"></i>&nbsp;';
	        }
	
		// include IT support info
		if ( get_field( 'include_it_support_info' ) == '' ) {
	                $services .= '<i aria-hidden="true" class="fa fa-circle-o" title="No IT Support Information"></i>&nbsp;';
	        } else {
	                $services .= '<i aria-hidden="true" class="fa fa-check-circle-o" title="Show IT Support Information"></i>&nbsp;';
	        }

		// is this service locked to authenticated users only?
		if ( $can_view == 0 ) {
			$services .= '<i aria-hidden="true" class="fa fa-check-circle-o" title="Authentication Required"></i>&nbsp;&nbsp;';
		} else {
			$services .= '<i aria-hidden="true" class="fa fa-circle-o" title="Unauthenticated Allowed"></i>&nbsp;&nbsp;';
		}

	        $services .= '<a href="/service-catalog/wp-admin/post.php?post=' . $post->ID . '&action=edit">';
	        if ( get_field( 'icon' ) != '' ) { $services .= '<i aria-hidden="true" class="fa ' . get_field( 'icon' ) . '"></i> '; }
	        $services .= $post->post_title;
	        $services .= '</a> - ';

		$audiences = 0;
		$terms = get_the_terms( $post, 'audience');
                foreach ( $terms as $term ) {
			$services .= $term->name . "&nbsp;&nbsp;";
			$audiences++;
                }
		if ( 0 == $audiences ) {
			$services .=  "none";
		}

		$services .= '<br/>';
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
