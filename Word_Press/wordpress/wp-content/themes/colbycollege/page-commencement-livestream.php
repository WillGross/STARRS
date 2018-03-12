<?php
/**
 * Template Name: Commencement Livestream (temporary)
 *
 * @package ColbyCollege
 */

global $post;

get_header(); 
setup_postdata( $post );
?>

<main class=commencement-livestream>
	<header>
		<h1><?php the_title(); ?></h1>
	
		<p class=commencement-livestream__content><?php echo get_the_content(); ?></p>
	</header>
	
	<article>
		<div class="commencement-livestream__livestream">
			<iframe id="ls_embed_1462811044" src="//livestream.com/accounts/7613748/events/5332082/player?width=640&height=360&autoPlay=true&mute=false" width="640" height="360" frameborder="0" scrolling="no"></iframe>
		<script type="text/javascript" data-embed_id="ls_embed_1462811044" src="//livestream.com/assets/plugins/referrer_tracking.js"></script>
		</div>
		<div class="commencement-livestream__social-stream pull-right">
			<div class="scrbbl-embed" data-src="/event/2069027/20570"></div>
	<script>(function(d, s, id) {var js,ijs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="//embed.scribblelive.com/widgets/embed.js";ijs.parentNode.insertBefore(js, ijs);}(document, 'script', 'scrbbl-js'));</script>
		</div>
	</article>
</main>

<?php get_footer();
