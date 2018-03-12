<?php
	$printView = (isset($_GET['print']) || isset($_GET['renderforprint'])?true:false);
?>
			<?php
		          $pageTemplate = get_page_template();
		          if (is_active_sidebar('footer1') && (strpos($pageTemplate,'/page-three-column.php')===false )){
		          ?>

		            <?php
		            	$pageTemplate =  get_page_template();

		            	if(strpos($pageTemplate,'/page-three-column.php')!==false || strpos($pageTemplate,'homepage')!==false) { ?>
			            	<div id="siteFooter">
					          <div id="inner-footer" class="clearfix">
					          <div id="widget-footer" class="clearfix row-fluid">
		            	<?php
			            	dynamic_sidebar('footer1');?>
			            	</div>
					<nav class="clearfix">
						<?php //
						// If we decide to use footer links, uncomment here.
						//bones_footer_links(); // Adjust using Menus in Wordpress Admin ?>
					</nav>
					</div>
			            	<?php
			            }
						?>

				</div>

					<?php
					}?>
			</div>

<?php
	if(!$printView) {?>
	<div class="push"></div>
<?php
}?>
			<?php
			if(!$printView){?>
			<footer id="colbytemplatefooter">

			<div id="footerWrapper">
				<div id="footerNav">
				<ul class="footerCol firstCol">
					<li><a href="/news/">News</a></li>
					<li><a href="/events/">Events</a></li>
					<li><a href="/magazine/"><em>Colby</em> Magazine</a></li>
					<li><a href="/museum">Museum of Art</a></li>
					<li><a href="/library">Libraries</a></li>
				</ul>
				<ul class="footerCol secondCol">
					<li><a href="/about/">About Colby</a></li>
					<li><a href="/visit/">Visit Colby</a></li>
					<li><a href="/directory/">Directory</a></li>
					<li><a href="/map">Campus Map</a></li>
					<li><a href="/employment">Employment</a></li>

				</ul>
				<ul class="footerCol thirdCol">
					<li><a href="http://my.colby.edu">myColby</a></li>

					<li><a href="http://email.colby.edu">Webmail</a></li>
					<li><a href="/careercenter/">Career Center</a></li>
					<li><a href="/diningservices/menus/">Dining Menus</a></li>
					<li><a href="/contact-colby-college/">Site Feedback</a></li>
				</ul>
				<ul class="footerCol lastCol">
					<li id="colby-loginli"></li>
					<li>&nbsp;</li>
					<li><a href="/colbyalumni/">Alumni</a></li>
					<li><a href="/parents/">Parents</a></li>
					<li><a href="/admission/">Admissions</a></li>
				</ul>
				<a rel="nofollow" href="/reflective.php" style="display:none;">uvular-surrounding</a>
			</div>

			<div id="contactSupportConnect">
				<div id="footerContactInfo"<?php if (is_active_sidebar('footercontact') ) {
					echo ' class="customFooterContact"';
					}?>><?php
					if (is_active_sidebar('footercontact') ) {

						// Output whatever widgetized content that is set...
						dynamic_sidebar('footercontact');

					}
					else {

						 // Output standard footer contact information
						 echo 'Colby College<br />4000 Mayflower Hill<br />Waterville, ME 04901<br />207-859-4000<br /><a href="/contact-colby-college/">Contact Us</a>';
					} ?>
				</div>
				<div id="connectSupport">
				<div id="footerSupport">

					<?php if ( 54 === get_current_blog_id() ) : ?>

					<h2 id="join-the-conversation">Join the Conversation</h2>
					<h2 id="colby2016">#Colby2017</h2>
					<?php else: ?>

					<a id="request-info" class="btn btn-primary btn-large buttonBlue" href="/admission/why-colby/request-info/">Request Information</a><br />
					<a id="support" class="btn btn-primary btn-large buttonBlue" href="/giving/">Giving</a>

					<?php endif; ?>
				</div>
				<div id="footerConnect" class=connect-wrapper>
						<h4><a href="/social">Connect with Colby</a></h4>
						<a id="twitter" title="Twitter" href="http://www.twitter.com/colbycollege">
							<svg class="social-icon social-icon--twitter">
								<use xlink:href="#twitter-icon"></use>
							</svg>
						</a>
						<a id="facebook" title="Facebook" href="http://www.facebook.com/colbycollege">
							<svg class="social-icon social-icon--facebook">
								<use xlink:href="#facebook-icon"></use>
							</svg>
						</a>
						<a id="youtube" title="YouTube" href="http://www.youtube.com/colbycollege">
							<svg class="social-icon social-icon--youtube">
								<use xlink:href="#youtube-icon"></use>
							</svg>
						</a>
						<a id="vimeo" title="Vimeo" href="http://vimeo.com/colbycollege">
							<svg class="social-icon social-icon--vimeo">
								<use xlink:href="#vimeo-icon"></use>
							</svg>
						</a>
						<a id="rss" title="RSS" href="/news/?feed=rss">
							<svg class="social-icon social-icon--rss">
								<use xlink:href="#rss-icon"></use>
							</svg>
						</a>
					</div>
				</div>
			</div>
				<div class="clearfix">&nbsp;</div>
				</div>
			</footer>
			<?php
			} ?>
		<!--[if lt IE 7 ]>
  			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
  			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		<![endif]-->
		<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri();?>/library/js/respond.min.js"></script><![endif]-->

		<script>(function() {
		var _fbq = window._fbq || (window._fbq = []);
		if (!_fbq.loaded) {
		var fbds = document.createElement('script');
		fbds.async = true;
		fbds.src = '//connect.facebook.net/en_US/fbds.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(fbds, s);
		_fbq.loaded = true;
		}
		_fbq.push(['addPixelId', '856862474372994']);
		})();
		window._fbq = window._fbq || [];
		window._fbq.push(['track', 'PixelInitialized', {}]);
		</script>
		<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=856862474372994&amp;ev=PixelInitialized" /></noscript>

<?php

wp_footer();

include 'assets/svg/sprite.svg';
