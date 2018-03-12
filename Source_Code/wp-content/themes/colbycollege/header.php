<?php
global $current_blog; // WPMU current site information
global $is_winIE;
// Vars...
$navOutput = false;
$showSlideshow = of_get_option( 'showhidden_slideroptions' );
$headerImage = get_header_image();
$colbyHomepage = ( get_the_title() == 'Front Page' && get_current_blog_id() == '1' );
$printView = ( isset( $_GET['print'] ) || isset( $_GET['renderforprint'] ) ? true : false );

$fullWidth = ( isset( $_GET['fullwidth'] ) ? true : false );
$loadJumbo = false;             // Slideshow carousel (Jumbo slideshow)
$emergencyMessages = false;
$bodyClass = '';
$blogName = get_bloginfo( 'name' );
$blogName = str_replace( 'ColbyNext', '<span>Colby</span><span>Next</span>', $blogName );

$athleticsHeader = (
	$blogName == 'Athletics' ||
	$blogName == 'Mules in the Community' ||
	$blogName == 'Baseball and Softball Fields' ||
	$blogName == 'Friends of Athletics'
);

if ( $printView ) :
	$bodyClass .= ' render-for-print';
endif;

if ( ! strlen( $headerImage ) ) {
	$headerImage = get_template_directory_uri() . '/library/images/default_header.jpg';
}

if ( get_post_meta( 'headerimage' ) != '' ) :
	$headerImage = get_post_meta( get_the_ID(), 'headerimage' );
endif;

if ( wp_is_mobile() ) :
	$bodyClass .= ' mobile';
endif;

if ( $colbyHomepage ) :
	if ( stripos( network_site_url( '/' ), 'author.colby.edu' ) !== false && ! is_user_logged_in() ) :
		echo '<script>alert("Please log in to view the author.colby.edu homepage.");</script>
			<script type=text/javascript src=http://author.colby.edu/wp-content/themes/colbycollege/library/js/scripts.js></script>
			<a href=https://www.colby.edu/ColbyMaster/login/>Log in</a>';
		exit();
	endif;

	$bodyClass .= ' colby-edu-homepage';
endif;

if ( of_get_option( 'slider_options_type' ) == '2' && ( is_front_page() || $colbyHomepage ) ) :
	$loadJumbo = true;
endif;

if ( $colbyHomepage ) :
	$emergencyMessages = check_emercencymessages();
	$bodyClass .= ' emergency-notification emergency-notification-' . $emergencyMessages['placement'];

	if ( $emergencyMessages['placement'] == 'major' ) :
		$loadJumbo = false;
	endif;
endif;

$meta_description = '';
if ( is_front_page() || is_home() ) {
	$meta_description = get_bloginfo( 'description' );
	if ( $meta_description ) {
		$meta_description = '<meta name=description content="' . $meta_description . '" />' . "\n";
	}
}

?><!doctype html>
<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<head>
	<meta charset=utf-8>
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	<title><?php wp_title( '|', true, 'right' ); ?><?php echo ( $blogName != 'Colby College' ) ? ' | Colby College' : ''; ?></title>
	<meta name=viewport content="width=device-width, initial-scale=1.0">
	<link rel=icon href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type=image/x-icon>
	<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<script type=text/javascript src=//use.typekit.net/mko7rzv.js></script>
	<script type=text/javascript>try{Typekit.load();}catch(e){}</script>

	<link rel="stylesheet" type="text/css" href="https://cloud.typography.com/6992672/7903192/css/fonts.css" />

	<?php get_wpbs_theme_options(); ?>
	<?php wp_head(); ?>
	<?php echo $meta_description ?: ''; ?>

	<!--[if IE]>
	<link rel=stylesheet href="<?php echo get_template_directory_uri(); ?>/library/css/ie.css" media=screen type=text/css />
	<script src="<?php echo get_template_directory_uri() . '/library/js/jquery.textshadow.js'; ?>"></script><![endif]-->
	<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri() . '/library/js/jquery.corner.js'; ?>"></script>
	<link rel=stylesheet href="<?php echo get_template_directory_uri(); ?>/library/css/ie8below.css" media=screen type=text/css />
	<![endif]-->

</head>
<body <?php body_class( $bodyClass ); ?>>
	<?php include( 'library/inc/analyticstracking.php' );   // Google Analytics Code ?>
	<?php if ( ! $printView ) : ?>
	<header role="banner"
	<?php
	if ( strlen( $headerImage ) &&
			empty( $jumboSlideshow ) &&
			! preg_match( '/(?i)msie [2-8]/', $_SERVER['HTTP_USER_AGENT'] ) &&
			! $colbyHomepage &&
			get_post_type() != 'sport' ) :
							 echo " style=\"background-image:url('$headerImage')\"";
	endif;
	?>
	 class="<?php echo ($showSlideshow === '1' && is_front_page()) ? 'hasSlideshow' : ''; ?>">
	<?php
	// IE8 and under don't support stretching header images. Add real image to use as background...
	if ( preg_match( '/(?i)msie [2-8]/', $_SERVER['HTTP_USER_AGENT'] ) && ! $colbyHomepage ) :
		echo '<div id=headerBackgroundImageIE-container><img id=headerBackgroundImageIE ';
		if ( strpos( $headerImage, 'default_header.jpg' ) !== false ) {
			echo ' style="height:190px;" ';
		}
		echo 'alt="Background Image" src="' . $headerImage . '"' . ( ( $showSlideshow != '1' ) ? ' class="noSlideshow"' : '' ) . ' /></div>';
	endif;
	?>
	<div class="colby-header container-fluid navbar clearfix">
		<div id=mainNav class="navbar navbar-inverse">
		  <div class=navbar-inner>
			<div class=container>
			  <a class="btn btn-navbar" data-toggle=collapse data-target=.top-menu>
				<span class=icon-bar></span>
				<span class=icon-bar></span>
				<span class=icon-bar></span>
			  </a>

			  <div id=search-wrap>
					<div id=headSearchColby>
						<svg class="search-icon">
							<use xlink:href="#search-icon"></use>
						</svg>
					</div>
			  </div>
			  <div id=quick-links>
					<a rel=nofollow href=http://www.colby.edu/>Offices and Resources</a>
			  </div>
			<div class=colby-logo>
					<?php ob_start(); ?>
					<a href=http://www.colby.edu/ id=logo>
							<svg class=colby-logo-svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 width="612px" height="312px" viewBox="-17 52.5 612 312" enable-background="new -17 52.5 612 312" xml:space="preserve">
							 <title>Colby College Logo</title>
						<path fill="#fff" d="M226.264,149.287c-34.386,0-59.993,26.339-59.993,61.456v0.366l0,0c0,3.658-0.732,6.585-1.829,9.146
							c-13.535,27.069-34.021,40.604-61.822,40.604c-39.873,0-67.675-36.946-67.675-93.281c0-47.922,23.046-84.502,65.846-84.502
							c30.728,0,49.018,15.363,57.798,50.848h5.121V78.32h-5.121c-1.829,4.023-5.122,5.854-9.511,5.854
							c-10.608,0-27.436-7.683-49.75-7.683c-53.042,0-91.818,43.897-91.818,103.524c0.366,62.188,44.995,92.916,87.794,92.916
							c33.289,0,60.725-17.56,73.162-46.093c6.584,26.704,29.265,44.995,58.164,44.995c34.386,0,59.993-26.339,59.993-61.091
							C286.622,175.626,260.65,149.287,226.264,149.287z M226.264,265.615c-25.241,0-37.312-24.875-37.312-54.506
							c0-33.289,13.9-54.871,37.312-54.871c25.241,0,37.312,25.973,37.312,54.871C263.576,238.911,252.236,265.615,226.264,265.615z
							 M325.764,246.593V62.957h-5.121l-37.678,4.023v5.486h7.316c11.339,0,14.997,4.391,14.997,15.73v152.908
							c0,17.193-3.291,21.949-14.631,21.949h-5.487v5.486h61.089v-5.486h-2.926C329.787,263.055,325.764,259.03,325.764,246.593z
							 M420.143,149.287c-13.17,0-27.436,6.585-36.947,17.193V62.957h-5.121L342.59,66.98v5.486c17.561,0,19.389,2.195,19.389,20.12
							v173.76c14.998,2.561,25.24,3.658,34.02,3.658c56.336,0,74.627-36.215,74.627-63.651
							C470.99,174.162,448.676,149.287,420.143,149.287z M401.852,263.786c-15.363,0-18.656-7.316-18.656-22.681V175.26
							c8.779-11.34,17.193-16.461,28.168-16.461c16.826,0,36.58,16.827,36.58,49.75C448.311,248.057,425.996,263.786,401.852,263.786z
							 M544.518,152.945v5.487c12.072,0.731,13.17,8.048,8.779,21.948l-19.387,61.091l-26.34-65.479c-4.023-9.877-0.73-16.462,7.684-17.56
							v-5.487h-52.678v5.487c12.803,0.366,16.828,4.024,22.314,17.193l39.508,96.208l-14.633,41.702
							c-9.877,26.338-27.801,18.29-31.826,16.096l-1.096,23.777c4.023,1.464,2.926,1.464,7.682,1.464c13.168,0,21.217-5.854,29.996-32.558
							l43.531-136.446c6.219-19.389,13.17-27.07,25.24-27.07v-5.487h-38.775V152.945z"/>
						</svg>
					</a>
					<?php echo apply_filters( 'colby_logos', ob_get_clean() ); ?>
			</div>
			  <!-- Everything you want hidden at 940px or less, place within here -->
			  <nav role=navigation class="nav-collapse collapse top-menu">
				<ul class=mainSubmenuCol id=audienceLinksInner>
					<li id=top-support-colby><a href="<?php echo network_home_url(); ?>advancement/">Support Colby</a></li>
					<li id=top-visitors><a href="http://www.colby.edu/visitors/">Visitors</a></li>
					<li id=top-alumni><a href="http://www.colby.edu/alumni">Alumni</a></li>
					<li id=top-parents><a href="http://www.colby.edu/parents/">Parents</a></li>
					<li id=top-mycolby class=mainNavButton><a href=https://my.colby.edu>myColby</a></li>
					<li class=loginli-top></li>
				</ul>
				<?php include( 'library/inc/nav/main-navigation.php' ); ?>
			</nav>
			</div>
		  </div>
		</div>
	</div>
	<?php

	if ( $loadJumbo ) :
		include( 'library/inc/topJumboSlideshow.php' );             // Jumbo slideshow (Gateways, Colby.edu homepage, etc.)...
		$showSlideshow == false;
	endif;

	if ( ! $colbyHomepage ) :
	?>
	<div id="inner-header" class="clearfix<?php echo $showSlideshow == true ? ' slide-view' : ''; ?>">
		<!-- Section title -->
		<div class="section-title container-fluid">

			<?php if ( $current_blog->blog_id != 1 ) : ?>
				<?php
				if ( $athleticsHeader ) {
					echo '<div class="muleLogo">
								<img src="/wp-content/themes/colbycollege/images/White-Mule_no-Background-800.png" id="colby-mule-head-top" />
							</div>';
				}
				?>
			<div class="section-title-inner" rel="home">
			<div class="sectionTitleWrapper">
				<a title="<?php echo get_bloginfo( 'description' ); ?>" href="<?php echo home_url(); ?>">
					<?php
					if ( of_get_option( 'site_name','1' ) ) {
						echo $blogName;}
?>
				</a>
				<?php
				if ( $athleticsHeader ) :
					$tag = wp_get_post_tags( $post->ID );

					if ( count( $tag ) && get_post_type() == 'sport' ) :
						$sport_title = '';
						if ( count( get_field( 'gender' ) ) > 1 ) :
							$sport_title = get_the_title();
						else :
							$sport_title = str_replace( "'",'’',get_field( 'gender' )[0] ) . ' ' . get_the_title();
						endif;

						echo "<h2 class=section-description><a href=\"/colbyathletics/sport/ {$tag[0]->slug} \"> $sport_title </a></h2>";
					else :
						echo '<h2 class=section-description><a href="' . home_url() . '">' . get_bloginfo( 'description' ) . '</a></h2>';
					endif;
				endif;

				echo '</div></div>';
			else :

			?>
			<div rel=home class=section-title-inner>
				<a title="<?php echo get_bloginfo( 'description' ); ?>" href="<?php echo home_url(); ?>">&nbsp;</a>
			</div>
			<?php
		endif;
		?>
		</div>
		<?php
		// Check if user has set a slideshow to view...
		if ( $showSlideshow === '1' && is_front_page() ) {
			include( 'library/inc/topSlideshow.php' );
		}
		?>
		<?php
		if ( has_nav_menu( 'main_nav' ) ) :
			$navOutput = true;
			?>
		<div id=sectionMenu class="navbar container-fluid<?php echo (of_get_option( 'slider_options_type' ) == '1') ? ' hasSlideshow' : ''; ?>">
			<div class=navbar-inner>
				<div class="container-fluid nav-container">
					<nav role=navigation>
						<a class="btn btn-navbar" data-toggle=collapse data-target=.section-menu>
							<span class=icon-bar></span>
							<span class=icon-bar></span>
							<span class=icon-bar></span>
						</a>
						<div class="nav-collapse section-menu">
							<?php bones_main_nav(); ?>
						</div>
					</nav>
					<?php if ( of_get_option( 'search_bar', '1' ) && false ) : ?>
					<form class="navbar-search pull-right" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
						<input name=s id=s type=text class=search-query autocomplete=off placeholder="<?php _e( 'Search', 'bonestheme' ); ?>" data-provide=typeahead data-items=4 data-source='<?php echo $typeahead_data; ?>'>
					</form>
				<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
			else :
				echo '&nbsp;';
			endif;
		?>
	</div>
<?php endif; ?>
</header>
<?php endif; ?>
	<div id="<?php echo $printView == true ? '' : 'content-container'; ?>" class="<?php echo ( ! $colbyHomepage && ! $fullWidth ) ? 'container-fluid' : 'container-homepage'; ?><?php echo $navOutput ? '' : ' no-nav'; ?>">
						<?php
						if ( $emergencyMessages !== false ) :
							if ( $emergencyMessages['placement'] == 'major' ) :
								// Emergency message should take over entire front page...
								echo '<div id=homepageEmergencyMessage class=container-fluid>';
								echo '<div class=span8><h2>' . $emergencyMessages['post_title'] . '</h2>';
								echo $emergencyMessages['post_content'];
								echo '</div></div>';
								endif;
		endif;
