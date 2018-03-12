<?php

if(get_query_var('pagename') == 'viewevent')
	get_template_part( 'templates/header', 'event');
get_header();
?>			<div id="content" class="clearfix row-fluid">

				<div id="main" class="span8 clearfix" role="main">

					<?php
						if (have_posts()) : while (have_posts()) : the_post();

						if(get_query_var('pagename') == 'viewevent')
							get_template_part( 'templates/single', 'event');
						else{
							get_template_part( 'templates/single', 'page');
						}

					?>

					<?php endwhile; ?>

					<?php else : ?>

					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "bonestheme"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "bonestheme"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>

					<?php endif; ?>

				</div> <!-- end #main -->

				<?php
				if ( ! isset( $_GET['print'] ) ) :
					get_sidebar();
				endif; // sidebar 1 ?>

			</div> <!-- end #content -->

<?php get_footer(); ?>
