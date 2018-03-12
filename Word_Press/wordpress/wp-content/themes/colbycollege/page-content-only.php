<?php
/**
 * Template Name: Content Only
 *
 * @package ColbyCollege
 */
 
wp_head(); ?>

<main class="content-only">

<?php 
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		the_content();
	}
} ?>

</main><?php
wp_footer();
