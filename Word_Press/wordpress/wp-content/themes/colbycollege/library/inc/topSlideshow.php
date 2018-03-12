<?php

	if(of_get_option('slider_options_type')=='1' || of_get_option('slider_options_type')=='0' ){
		echo '<div id="frontSlideshow">';
		echo render_frontpage_slideshow(array('id'=>'wooslider-id-1'));
		echo '</div>';
	}

	if(of_get_option('slider_options_type')=='0' && false){
	/* Bootstrap carousel */
?>	
			<div id="myCarousel" class="carousel slide">

					    <!-- Carousel items -->
					    <div class="carousel-inner">

					    	<?php
							global $post;
							$tmp_post = $post;
							$show_posts = (of_get_option('slider_options') + 1);
							$catobj = get_category_by_slug('frontpage-slide');
							if(isset($catobj))
								$args = array( 'numberposts' => $show_posts,'cat'=>$catobj->term_id );							
							else
								$args = array( 'numberposts' => $show_posts );

							$myposts = get_posts( $args );
							$post_num = 0;
							foreach( $myposts as $post ) :	setup_postdata($post);
								$post_num++;
								$post_thumbnail_id = get_post_thumbnail_id();
								$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'wpbs-featured-carousel' );
							?>

						    <div class="<?php if($post_num == 1){ echo 'active'; } ?> item">
						    	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'wpbs-featured-carousel' ); ?></a>

							   	<div class="carousel-caption">

					                <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
					                <p>
					                	<?php
					                		$excerpt_length = 200; // length of excerpt to show (in characters)
					                		$the_excerpt = get_the_excerpt(); 
					                		if($the_excerpt != ""){
					                			$the_excerpt = substr( $the_excerpt, 0, $excerpt_length );
					                			echo $the_excerpt . '... ';
					                	?>
					                	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="btn-small btn-primary">Read more &rsaquo;</a>
					                	<?php } ?>
					                </p>

				                </div>
						    </div>

						    <?php endforeach; ?>
							<?php $post = $tmp_post; ?>

					    </div>

					    <!-- Carousel nav -->
					    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
					    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
				    </div>
<?php
}


?>				    				    