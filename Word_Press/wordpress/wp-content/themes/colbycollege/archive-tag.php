<?php
/*
	Template Name: Tag Archive
*/
get_header();
?>
	<div id=content class="clearfix row-fluid">
		<div id=main class="span8 clearfix" role=main>
			<div class=page-header>
<?php
// Grab the tags, sort and output to screen. Need to manually sort, as WP orderby doesn't work...
$list = '';
$tags = get_terms( 'post_tag', array( 'fields'=>'all', 'orderby' => 'name' ) );
usort( $tags, 'cmp' );
?>
	<h1 class=page-title>Tags</h1>
	<?php
		$list = "<ul>";
		foreach ( $tags as $tag ) {
			$url = esc_attr( get_tag_link( $tag->term_id ) );
			$count = intval( $tag->count );
			$name = apply_filters( 'the_title', $tag->name );
			$list .= "\n\t\t<li><a href=$url>$name</a> ($count)</li>";
		}

		$list .= "</ul>";
		echo $list;
	?>
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
