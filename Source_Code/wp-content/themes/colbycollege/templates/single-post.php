<?php
/*
	Post template for single posts...
*/

$hidedates = of_get_option('hide_dates');

$subhead = get_post_meta( get_the_ID(), 'subhead', true );

if( strlen( $subhead )) {
	echo '<p class="post-subhead"><em>'. $subhead . '</em></p>';
}

?>
<p class="meta">							
<?php
	
if(in_category('in-the-news')){
	if (get_post_custom_values('source_name')!=''){
		echo get_post_custom_values('source_name')[0].'<br />';
	}
}

if(get_post_type() != 'venue') {

	if(!$hidedates) {
?>
 <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate="pubdate"><?php the_date(); ?></time>
<?php
	}

	if(function_exists('get_field'))
		if(get_field('author') != "")
			echo ' | <span class="authorName">by '.get_field('author').'</span>';

	$hideshare = of_get_option('show_share');

	if(!$hideshare) {
?>
<div class="addthis_toolbox addthis_default_style">
	<a href="http://www.addthis.com/bookmark.php" class="addthis_button_expanded addthis_button" style="text-decoration:none;">
    <img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png" width="16" height="16" border="0" alt="Share" /> Share</a>
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>   
</div>

<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
<?php
	$trackingAccount = 'ra-52274afd3385fbef';
	
	if(get_bloginfo('wpurl')=='magazine') {

		$trackingAccount = 'ra-538f33942f3feef2';
	}
?>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $trackingAccount; ?>"></script>
<?php
}?>
<?php
} ?>
</p>
</header> <!-- end article header -->
					
<section class="post_content clearfix" itemprop="articleBody">
	<?php 
		// Press release contact information...
		if(in_category('press-release')){
			
			echo '<div id="press-contact" class=""><h3>Contact:</h3>';
			$contact_name = get_post_custom_values('contact_name');
			
			if( $contact_name != '' ){
				
				$contact_name = $contact_name[0];
				$contact_email = get_post_custom_values('contact_e-mail');
				$contact_phone = get_post_custom_values('contact_phone');
				
				if( $contact_name == 'Ruth Jacobs' || $contact_name == 'Ruth Jackson' || $contact_name == 'Ruth Jacobs Jackson' ) {
					$contact_name = 'Office of Communications';
				}
				
				echo $contact_name;
				
				if ( $contact_email !='' ) {
					echo ' (<a href="mailto:'.$contact_email[0].'">'.$contact_email[0].'</a>)';
				}
					
				if ( $contact_phone !='' ) {
					echo '<br />'. $contact_phone[0];
				}
			}
			else{
				// Default contact information...
				echo 'Office of Communications (<a href="mailto:pr@colby.edu">pr@colby.edu</a>)<br />207-859-4350<br />';
			}
			echo '</div>';
		}
	
		if(has_post_thumbnail() && get_field('no_featured_image') == '' && get_bloginfo('name') != 'Athletics'){
			$fullImageURL = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
			$medImageURL = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium');
			$thumbCaption = nl2br(the_post_thumbnail_caption( get_the_id() ));
			if(strlen(trim($thumbCaption)))
				echo '<div class="alignright wp-caption" style="width:'.$medImageURL[1].'px">';
			echo '<a class="fancybox" href="'.$fullImageURL[0].'">';
			the_post_thumbnail( 'medium',array('class'=>'alignright') ); 
			echo '</a>';
			if(strlen(trim($thumbCaption)))
				echo '<p class="wp-caption-text">'.$thumbCaption.'</p></div>';
		}
	// Display content. If there isn't any content, output the excerpt...
	if(trim(($post->post_content)) != ""){
		the_content();
	}
	else
		the_excerpt(); ?>
		
	<?php
	if(in_category('in-the-news')){
		if(get_post_custom_values('in_the_news_external_link')!=''){
			echo '<a target="_new" class="btn" href="';
			print_r(get_post_custom_values('in_the_news_external_link')[0]);
			echo '"><i class="icon-share-alt"></i> Read full article</a>';
		}
	}
	
	if(strlen($post->post_content) > 1250 && !$hideshare){
	?>
	<div id="shareBottom" class="addthis_toolbox addthis_default_style ">			
		<a href="http://www.addthis.com/bookmark.php" class="addthis_button" style="text-decoration:none;">
        <img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
        width="16" height="16" border="0" alt="Share" /> Share</a>
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>				       
		</div>
	<?php 
	}
	wp_link_pages(); ?>

</section> <!-- end article section -->