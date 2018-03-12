<?php
/*
	Post template for single Catalogue Requirment posts
*/

//not used
?>
	
<p class="meta">				
 <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate="pubdate"><?php the_date(); ?></time>
	<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
	<a href="http://www.addthis.com/bookmark.php" class="addthis_button_expanded addthis_button" style="text-decoration:none;">
        <img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
        width="16" height="16" border="0" alt="Share" /> Share</a>	
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
<!-- AddThis Button END -->
<?php
//} ?>
</p>
</header> <!-- end article header -->
					
<section class="post_content clearfix" itemprop="articleBody">
<?php
	// Display content.
	
	?>
	<a href="llama">View list of courses</a>
	<?php	
	echo(get_field('faculty'));
	echo(get_field('description'));
	echo(get_field('additional_text'));
	?>
	<h2 onclick=showHide("requirements")>Requirements+</h2>
	<div id="requirements">
	<?php
	echo(get_field('requirements'));
	?>
	</div>
	<script type="text/javascript">document.getElementById('requirements').style.display='none';</script>
	
	<?php
	if (strlen(get_field('approved_courses')) > 5){
		echo('<h2 onclick=showHide("approvedCourses")>Approved Courses+</h2>
				<div id="approvedCourses">'.get_field("approved_courses").'</div>
				<script type="text/javascript">
					document.getElementById("approvedCourses").style.display="none";
				</script>');
	}
	?>
	<div id="shareBottom" class="addthis_toolbox addthis_default_style ">			
		<a href="http://www.addthis.com/bookmark.php" class="addthis_button" style="text-decoration:none;">
        <img src="<?php echo get_template_directory_uri(); ?>/images/sm-plus-custom.png"
        width="16" height="16" border="0" alt="Share" /> Share</a>
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>			       
		</div>
	<?php 
	wp_link_pages(); ?>

</section> <!-- end article section -->
