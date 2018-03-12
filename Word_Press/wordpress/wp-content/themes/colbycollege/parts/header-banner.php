<?php
/**
 * Lighter header file for Commencement template
 *
 * @package ColbyCollege
 */

global $current_blog;

?><!doctype html>
<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<meta charset="utf-8">
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<title><?php wp_title( '|', true, 'right' ); ?><?php echo ($blogName!='Colby College')?' | Colby College':'';?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="<?php echo get_template_directory_uri();?>/favicon.ico" type="image/x-icon">
<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<script type="text/javascript" src="//use.typekit.net/mko7rzv.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<?php get_wpbs_theme_options(); ?>
<?php wp_head(); ?>

<!--[if IE]><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/ie.css" media="screen" type="text/css" /><script src="<?php echo get_template_directory_uri() . '/library/js/jquery.textshadow.js';?>"></script><![endif]-->
<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri() . '/library/js/jquery.corner.js';?>"></script><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/css/ie8below.css" media="screen" type="text/css" /><![endif]-->
<link href='//fonts.googleapis.com/css?family=Advent+Pro:200' rel='stylesheet' type='text/css'>

<body <?php body_class(); ?>>
	<?php include(get_template_directory() . '/library/inc/analyticstracking.php');	?>

	<div id="main-wrapper">
<?php if ( isset( $_GET['print'] ) || isset( $_GET['renderforprint'] ) ) { return; } ?>

<header role="banner" class="header--commencement">
	<div class="colby-header container-fluid navbar clearfix">
		<div id="mainNav" class="navbar navbar-inverse">
		  <div class="navbar-inner">
		    <div class="container">
		      <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-menu">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </a>

		      <div id="search-wrap">
				  <div id=headSearchColby>
					  <svg class="search-icon">
						<use xlink:href="#search-icon"></use>
					</svg>
				  </div>
			  </div>
			  <div id="quick-links">
					<a rel="nofollow" href="http://www.colby.edu/">Offices and Resources</a>
			  </div>
		      <div class="colby-logo">
				<a href="http://www.colby.edu/" id="logo"><img alt="Colby College" title="Colby College" src="/colby/wp-content/themes/colbycollege/images/ColbyLogo_wide.png" width="150" height="76" /></a>
			  </div>
		      <!-- Everything you want hidden at 940px or less, place within here -->
		      <nav role="navigation" class="nav-collapse collapse top-menu">
				<ul class="mainSubmenuCol" id="audienceLinksInner">
					<li id="top-support-colby"><a href="http://www.colby.edu/advancement/">Support Colby</a></li>
					<li id="top-visitors"><a href="http://www.colby.edu/visitors/">Visitors</a></li>
					<li id="top-alumni"><a href="http://www.colby.edu/alumni">Alumni</a></li>
					<li id="top-parents"><a href="http://www.colby.edu/parents/">Parents</a></li>
					<li id="top-mycolby" class="mainNavButton"><a href="https://my.colby.edu">myColby</a></li>
					<li class="loginli-top"></li>
				</ul>
				<?php include_once( get_template_directory() . '/library/inc/nav/main-navigation.php' ); ?>

		      </nav>
		    </div>
		  </div>
		</div>
	</div>
</header>
