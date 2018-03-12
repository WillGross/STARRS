<?php
	global $blog_id;
	$notfound = true;
	
?>				<div id="sidebar3" class="fluid-sidebar sidebar-middle" role="complementary">
				<hr class="sidebar responsive" /><!-- hr above sidebar for single column -->				
					<?php if ( is_active_sidebar( 'sidebar3' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar3' ); ?>
					<?php else : ?>
						<?php
							if(!$notfound){
						?>
						<!-- This content shows up if there are no widgets defined in the backend. -->						
						<div class="alert alert-message">						
							<p><?php _e("Please activate some Widgets","bonestheme"); ?>.</p>						
						</div>
					<?php 
						}
						endif; ?>
				</div>