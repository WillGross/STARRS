<?php get_header(); ?>
	<div id=content class="clearfix row-fluid">
		<div id=main class="span8 clearfix" role=main>
			<div class=page-header>
	       <h1 class=page-title>A-Z Index</h1>
	<?php

	$args = array( 'post_type' => 'a_z_terms',
			'orderby' => 'title',
			'order' => 'asc',
			'posts_per_page' => '-1' );

	$terms = new WP_Query( $args );

	?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role=article>
				<header></header>

				<section class="post_content"><br />
				<?php
				if ( $terms->have_posts() ) {
					$curLetter = '';

					// Output alphabetical letters...
					$alphas = range('A', 'W');

					foreach ( $alphas as $alpha ) {
						echo "<a href=\"#section$alpha\"> $alpha &nbsp;</a>";
					}

					echo '<hr />';

					// Outut search terms...
					while ( $terms->have_posts() ) {
						$terms->next_post();

						if ( true === get_field( 'exclude', $terms->post->ID ) ) {
							continue;
						}

						$URL = get_field( 'resource_url', $terms->post->ID );
						$title = get_the_title( $terms->post->ID );
						if ( strtoupper( substr( $title, 0, 1 ) ) != $curLetter ) {
							if ( $curLetter != '' )
								echo '</ul>';
							$curLetter = strtoupper( substr( $title, 0, 1 ) );
							echo "<h3 id=\"section$curLetter\"> $curLetter </h3>";
							echo '<ul>';
						}
						?>
						<li class=h2>
              <a href="<?php echo $URL; ?>" rel=bookmark title="<?php echo $title;?>">
                <?php echo get_the_title( $terms->post->ID ); ?>
              </a>
            </li>
				<?php
					}
				}
        ?>
				</ul>
				</section> <!-- end article section -->
				<footer></footer> <!-- end article footer -->
			</article> <!-- end article -->
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php
get_footer();

// Functions...
function cmp( $a, $b ) {
	return ( strcmp( $a->name, $b->name ) );
}
?>
