<?php get_header(); ?>
<div id=content class="clearfix row-fluid">
	<div id=main class="span12 clearfix" role=main>
		<article id=post-not-found class=clearfix>
			<header>
<?php
/* get the title, etc for this error (it might not be a 404) */
$title = 'File Not Found';
$lead = 'We can\'t find the file you requested.';
$description = 'Please use the navigation above or <a href=/search/>search</a> to find what you\'re looking for.';
?>
        <h1 class=page-title><?php _e( $title, "bonestheme" ); ?></h1>
        <p class=lead><?php echo $lead; ?></h3>
        <p><?php _e( $description, "bonestheme" ); ?></p>
			</header> <!-- end article header -->

			<section class=post_content>
				<div class=row-fluid>
					<div class=span12></div>
				</div>
			</section> <!-- end article section -->

			<footer>
			</footer> <!-- end article footer -->
		</article> <!-- end article -->
	</div> <!-- end #main -->
</div> <!-- end #content -->
<?php get_footer(); ?>
