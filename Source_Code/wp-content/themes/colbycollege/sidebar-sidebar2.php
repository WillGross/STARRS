<?php
	global $blog_id;
	$notfound = true;
?>				<div id="sidebar2" class="fluid-sidebar sidebar span<?php if(strpos(get_page_template(),'page-homepage-equal-width') !== false){echo '';}else{echo '4';}?>" role="complementary">
				<hr class="sidebar responsive" /><!-- hr above sidebar for single column -->				
					<?php if ( is_active_sidebar( 'sidebar2' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar2' ); ?>
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