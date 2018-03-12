<?php
/**
 * Control code tied to filters, or filters that point to functions
 *
 * @package ColbyCollege
 */


add_filter(
	'body_class', function( $body_classes ) {
		$body_classes[] = 'site-' . (string) get_current_blog_id();

		return $body_classes;
	}
);


if ( isset( $_GET['noadminbar'] ) && '1' === $_GET['noadminbar'] ) {
	add_filter(
		'show_admin_bar', function() {
			return false;
		}
	);
}

// Fix problem with image URLs in srcsets having HTTP instead of HTTPS, causing some not to load. JMW 3/25/16
add_filter(
	'wp_calculate_image_srcset', function( $sources ) {
		foreach ( $sources as &$source ) {
			$source['url'] = set_url_scheme( $source['url'], 'https' );
		}

		return $sources;
	}
);

add_filter(
	'wp_kses_allowed_html', function ( $allowedposttags ) {
		$allowedposttags['li']['wooslidercontent'] = 1;
		return $allowedposttags;
	}
);

add_filter(
	'colby_logos', function( $content ) {
		ob_start();

	?>
<a href="//darenorthward.colby.edu" class="dn-logo-container">
	<img src="//darenorthward.colby.edu/wp-content/themes/darenorthward/assets/svg/dn-logo-primary-white-original.svg" width="100" height="100" />
</a>
	<?php

	return $content . ob_get_clean();
	}
);
