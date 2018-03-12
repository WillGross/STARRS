<?php
/**
 * Template Name: Commencement Homepage
 *
 * @package ColbyCollege
 */

$menu = wp_get_nav_menu_items( '2016 Menu' );

get_template_part( 'parts/header-banner' ); ?>

<main class="commencement-homepage">

<?php if ( have_posts() ) : while ( have_posts() ): the_post(); ?>

	<article class="commencement">
	<?php the_content(); ?>
	
		<nav class="commencement-right">
			<?php foreach ( $menu as $menu_item ) : ?>

			<a class="<?php 
echo esc_html( $menu_item->menu_item_parent ?
	'commencement-right__child-item' : 'commencement-right__parent-item' );
	?>" href="<?php echo esc_url( $menu_item->url ); ?>">
				<?php echo wp_kses_post( $menu_item->title ); ?>

			</a>
			<?php endforeach; ?>
		</nav>
	</article>
	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>

	<img src="<?php echo esc_html( $thumbnail[0] ); ?>"
		 width="<?php echo esc_attr( $thumbnail[1] ); ?>"
		 height="<?php echo esc_attr( $thumbnail[2] ); ?>"
		 alt="<?php echo esc_html( $thumbnail[3] ? $thumbnail[3] : 'Background image' ); ?>"
		 class="commencement__background">

<?php
	endif; 
endwhile;
endif; ?>

</main>
<?php get_footer();
