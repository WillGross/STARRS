<?php
if ( $_GET[ 'page' ] == 'go' && get_field( 'url' ) != '' ) {
	wp_redirect( get_field('url') );
}

if ( $_GET[ 'page'] != 'simple' ) { get_header(); }

?>

<script type="text/javascript" language="javascript" src="/javascript/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/style/jquery-ui.min.css" type="text/css" />
<?php if ( $_GET[ 'page'] != '' )  { ?>
<style>
div { 
    font-size: normal;
}
a {
    color: #08c!important;
    text-decoration: none;
}
</style>
<?php } ?>

<?php while ( have_posts() ) : the_post(); ?>
	<div style="margin-bottom: 26px;">

	<?php
	if ( $_GET[ 'page'] != 'simple' ) {
		echo '<h1>';
		if ( get_field( 'icon' ) ) { 
			echo '<i class="fa ' . get_field( 'icon' ) . '"';
			if ( get_field( 'color' ) != '#000099' ) { echo ' style="color: ' . get_field( 'color' ) . ';"'; }
			echo '></i> ';
		}
                echo $post->post_title . '</h1>';
	}
	?>

        <div style="margin: 12px 0px;">
                <a href="/service-catalog/">Service Catalog</a>
                <?php
                $terms = get_the_terms( $post, 'service-areas');
                foreach ( $terms as $term ) {
                        print ' | <a href="/service-catalog/' . $term->slug . '/">' . $term->name . '</a>';
                }
		print ' | <strong>' . $post->post_title . '</strong>';
                ?>
        </div><hr/>

	<div>
		<h4>Service Description</h4>
		<?php the_field('description'); ?>
	</div>

        <?php if ( get_field( 'documentation_url' ) != '' ) { ?>
                <h4>More Information</h4>
                <div><a href="<?php the_field( 'documentation_url' ); ?>" title="more information"><?php the_field( 'documentation_url' ); ?></a></div>
        <?php } ?>

	<?php if ( get_field( 'how_to_get' ) != '' ) { ?>
		<div>
			<h4>How Do I Get It?</h4>
			<?php the_field('how_to_get'); ?>
		</div>
	<?php } ?>

	 <?php if ( get_field( 'training' ) != '' ) { ?>
                <div>
                        <h4>Where Can I Get Training?</h4>
                        <?php the_field('training'); ?>
                </div>
        <?php } ?>

	<?php if ( get_field( 'cost' ) != '' ) { ?>
		<div>
			<h4>What Does It Cost?</h4>
			<?php the_field( 'cost' ); ?>
		</div>
	<?php } ?>

	<?php if ( get_field( 'who_supports' ) != '' || ( is_array( get_field( 'include_it_support_info' ) ) && get_field( 'include_it_support_info' )[0] == 'Yes' ) ) { ?>
		<div>
			<h4>Who Supports It?</h4>
			<?php the_field( 'who_supports' ); ?>

			<?php if  ( is_array( get_field( 'include_it_support_info' ) ) && get_field( 'include_it_support_info' )[0] == 'Yes' ) {
				?>

				<div>
					<h4><span style="color: #a5a5a5;">Contact ITS Support Center</span></h4>
					<p><span style="color: #353535;">Lovejoy 146<br/>P: 207-859-4222</span></p>
					<p><a href="mailto:%22support@colby.edu%22"><span>support@colby.edu</span></a></p>
				</div>

				<?php
			}
			?>

		</div>
	<?php } ?>

	<?php if ( get_field( 'url' ) != '' ) { ?>
		<div><a href="?page=go"><i class="fa fa-link" aria-hidden="true" style="margin-top: 6px;"></i> Quick Link</a></div>
	<?php } ?>

	<div style="margin-top: 22px; font-size: small;">
		<i>Last Updated: <?php the_modified_date() ?> <?php the_modified_time() ?></i>
	</div>
	</div>

<?php endwhile; // end of the loop. ?>

<?php 

if ( $_GET[ 'page'] != 'simple' ) { get_footer(); }

?>
