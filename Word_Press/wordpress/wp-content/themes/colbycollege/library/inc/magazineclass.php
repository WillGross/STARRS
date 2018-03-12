<?php
class colbyMagazine {
	public $curIssue = array();
	
	function colbyMagazine($category = null) {
		// Constructor. Set any necessary variables.
		$this->setCurrentIssue($category);

	}
	
	private function setCurrentIssue() {
		$this->curIssue = $this->getCurrentIssue();
	}
	
	public function getCurrentIssue($currentonly = false) {
		if($currentonly !== false)
			$issueID = $currentonly;

		$str = array('post_type' => 'issue',
					 'orderby' => 'date',
					 'order' => 'desc',
					 'posts_per_page' => 1,
					 'name' => $issueID);
		$issueQuery = get_posts($str);

		if(count($issueQuery)) {
			$issueID = $issueQuery[0]->post_name;	
			$issueName = $issueQuery[0]->post_title;
			return $issueQuery[0];
		}
	}
	
	// Utility functions
	public function cleanExcerpt($excerpt) {
		$excerpt = str_ireplace('&lt;![CDATA[','',$excerpt);
		$excerpt = str_ireplace(']]&gt;','',$excerpt);
		
		if(substr($excerpt,0,1) == ',')
			$excerpt = substr($excerpt, 1);
		
		$excerpt = trim($excerpt);
		
		return $excerpt;
	}
	private function return_1800( $seconds ){
	  return 0;
  	}
	public function outputMostLinks($type) {
		switch($type) {
			case 'shared':
				add_filter( 'wp_feed_cache_transient_lifetime' , 'return_1800' );
				$rss = fetch_feed("http://q.addthis.com/feeds/1.0/clicked.rss?pubid=ra-538f33942f3feef2&period=month");
				$maxItems = 3;
				$i = 0;
				remove_filter( 'wp_feed_cache_transient_lifetime' , 'return_1800' );
					
				if (!is_wp_error( $rss ) ) :
				    $maxitems = $rss->get_item_quantity( -1 ); 
				    $rss_items = $rss->get_items( 0, $maxItems );
				endif; 
				
				?>
				<ul>
				    <?php if ( $maxitems == 0 ) : ?>
				        <li><?php _e( 'No items', 'my-text-domain' ); ?></li>
				    <?php else : 
				    
						foreach ( $rss_items as $item ) : 
				        	if($i >= $maxItems)
				        		break;

				        	if(true) { 
						        $title = str_ireplace('Colby Magazine','',str_ireplace('Colby College','',( $item->get_title() )));
						        $url = esc_url( $item->get_permalink() );
						        $relatedPost = get_page_by_title(substr($title,0,strlen($title)-7),OBJECT,'post');		
					        ?>
				        	<li class="clearfix">
				        		<a class="section-listing" title="" href="<?php echo preg_replace('/\?.*/', '', $url); ?>"><?php 				        		
				        		echo substr($title,0,strlen($title)-7); ?></a>
				        		<a title="" href="<?php echo preg_replace('/\?.*/', '', $url); ?>">
				        		<?php
				        			echo get_the_post_thumbnail($relatedPost->ID, 'small-rectangle',array('class' => 'alignright'));
				        		?></a>
								<div class="most-excerpt">
									<?php 
									$excerpt = get_field('subhead',$relatedPost->ID);
									


									if(!strlen(trim($excerpt))) {
										$excerpt = strip_shortcodes($relatedPost->post_content);
										$excerpt = colbyMagazine::cleanExcerpt($excerpt);					
									}
									?>
									<a title="" href="<?php echo preg_replace('/\?.*/', '', $url); ?>">
									<?php
									echo wp_trim_words(strip_shortcodes($excerpt), 10);?></a>
								</div>
							</li>  
				        <?php
					        	$i++;
				        	}	
				        	endforeach; ?>
				    <?php endif; ?>
					</li>	
				</ul><?php
			break;
			
		}
	}
}
?>