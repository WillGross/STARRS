<?php

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<link rel="stylesheet" href="'.get_template_directory_uri() . '/admin/css/editor-styles.css" type="text/css" media="all" />';
}

// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	// remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Widget
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Widget
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Widget
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Widget
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Widget
	remove_meta_box('dashboard_primary', 'dashboard', 'core');         //
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');       //

	// removing plugin dashboard boxes
	remove_meta_box('yoast_db_widget', 'dashboard', 'normal');         // Yoast's SEO Plugin Widget
}

if (is_admin()) :
// Remove editing screen meta boxes if needed...
function my_remove_meta_boxes() {
	if(of_get_option('suppress_comments_message') == '1'){
		remove_meta_box('commentstatusdiv', 'post', 'normal');
		remove_meta_box('commentsdiv', 'post', 'normal');
	}
}

add_action( 'admin_menu', 'my_remove_meta_boxes' );

/*
	-- Verify access level before editing page --

	Administrators or Office of Communications/ITS should be only editors of front pages for websites.
	If user is trying to edit front page and is not an administrator, do not allow them to do so.

*/
/* add_action( 'admin_head', 'verify_not_editor' ); */

function verify_not_editor() {
	global $post;

	if( $post ) {

		if( $post->ID == get_option('page_on_front') && !current_user_can( 'switch_themes' ) ) {

			echo 'You do not have access to edit this page. Only site administrators may edit the front page. Contact web@colby.edu for more information.';

			exit();
			return false;
		}

	}
}

endif;

/** Custom dashboard widgets... */

// Web RSS Dashboard Widget
function colby_rss_dashboard_widget() {
	if(function_exists('fetch_feed')) {
		include_once(ABSPATH . WPINC . '/feed.php');               // include the required file
		$feed = fetch_feed('http://www.colby.edu/weboffice/feed/');        // specify the source feed
		$limit = $feed->get_item_quantity(3);                      // specify number of items
		$items = $feed->get_items(0, $limit);                      // create an array of items
	}
	if ($limit == 0) echo '<div>The RSS Feed is either empty or unavailable.</div>';   // fallback message
	else foreach ($items as $item) : ?>

	<h4 style="margin-bottom: 0;">
		<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo $item->get_date('j F Y @ g:i a'); ?>" target="_blank">
			<?php echo $item->get_title(); ?>
		</a>
	</h4>
	<p style="margin-top: 0.5em;">
		<?php echo strip_tags(substr($item->get_description(), 0, 200)); ?>
	</p>
	<?php endforeach;
	?>
	<div><strong><a href="/weboffice">Web Requests and Tutorials ></a><strong></div>
	<?php
}

function colby_news_dashboard_widget(){
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
		    jQuery('#homepageDate').datepicker({
		        dateFormat : 'mm/dd/yy'
		    });

		    jQuery('#homepagePreviewLink').click(function(){
				if(jQuery("#homepageDate").val()!='')
					window.open('http://www.colby.edu/front-page/?passedDate=' + jQuery("#homepageDate").val(),'_blank');
		    });
		});
	</script>

	Preview for date:<br />
	<input type="text" name="homepageDate" id="homepageDate" value="" /> <a id="homepagePreviewLink" href="javascript:void(0)">Preview &raquo;</a>
	<?php
}

// calling all custom dashboard widgets
function bones_custom_dashboard_widgets() {

	global $blog_id;

//	wp_add_dashboard_widget('colby_rss_dashboard_widget', 'Colby Web Announcements', 'colby_rss_dashboard_widget');
	global $wp_meta_boxes;

	 //$my_widget = $wp_meta_boxes['dashboard']['normal']['core']['colby_rss_dashboard_widget'];
	 //unset($wp_meta_boxes['dashboard']['normal']['core']['colby_rss_dashboard_widget']);
	 unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	 //$wp_meta_boxes['dashboard']['side']['core']['colby_rss_dashboard_widget'] = $my_widget;

	if( get_bloginfo('name') == 'News'){
		add_meta_box('colby_news_dashboard_widget', 'Colby.edu Homepage', 'colby_news_dashboard_widget', 'dashboard', 'side', 'high');
	}

}

// Custom Backend Footer
add_filter('admin_footer_text', 'bones_custom_admin_footer');
function bones_custom_admin_footer() {
	echo '<div id="footer-thankyou" style="padding-bottom:10px;width:70%;float:left;"><a href="http://www.colby.edu"><img src="'.get_template_directory_uri().'/images/ColbyLogo.png" style="width:90px; margin-bottom:-20px;margin-right:25px;padding-bottom:5px;" /></a>Powered by Wordpress. For project requests and standards please contact the <a href="http://www.colby.edu/weboffice">Web Office</a>.</div><style>#update-nag,.update-nag{display:none!important;}</style><script>jQuery( "p:contains(\'WooThemes Updater must be network activated when in multisite environment.\')").parent().remove();</script>';
}

// adding it to the admin area
add_filter('admin_footer_text', 'bones_custom_admin_footer');


// Cut down on the clutter of the WP admin for non-administrators
add_action( 'admin_menu', 'remove_menu_pages' );

function remove_menu_pages() {
	if(!is_super_admin())
		remove_menu_page('tools.php');

	if(!is_super_admin() && of_get_option('suppress_comments_message') == '1')
		remove_menu_page('edit-comments.php');
}


// removing the dashboard widgets
add_action('admin_menu', 'disable_default_dashboard_widgets');
// adding any custom widgets
add_action('wp_dashboard_setup', 'bones_custom_dashboard_widgets');


/************* CUSTOM LOGIN PAGE *****************/

// calling your own login css so you can style it
function bones_login_css() {
	/* i couldn't get wp_enqueue_style to work :( */
	echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/library/css/login.css">';
}

// changing the logo link from wordpress.org to your site
function bones_login_url() { echo home_url(); }

// changing the alt text on the logo to show your site name
function bones_login_title() { echo get_option('blogname'); }

// calling it only on the login page
add_action('login_head', 'bones_login_css');


// Customizations for editor screen...


function enable_more_buttons($buttons) {
  $buttons[] = 'hr';
 return $buttons;
}
add_filter("mce_buttons", "enable_more_buttons");

add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

function my_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );

    //Remove the text color selector
      $remove = 'forecolor';

     //Find the array key and then unset
     if ( ( $key = array_search($remove,$buttons) ) !== false )
	 	unset($buttons[$key]);

	//Remove the underline button...
    $remove = 'underline';

    //Find the array key and then unset
    if ( ( $key = array_search($remove,$buttons) ) !== false )
		unset($buttons[$key]);

    return $buttons;
}
function colby_add_editor_styles() {
     add_editor_style( 'library/css/bootstrap.css' );
     add_editor_style( 'style.css' );
     add_editor_style( 'library/css/editor-style-overrides.css' );
}
add_action( 'init', 'colby_add_editor_styles' );

add_filter( 'tiny_mce_before_init', 'addEditorStyles' );

function addEditorStyles( $settings ) {

    $style_formats = array(
    	array(
    		'title' => 'Lead Text',
    		'block' => 'p',
    		'classes' => 'lead'
    	),
    	array(
    		'title' => 'Small Text',
    		'inline' => 'span',
    		'classes' => 'small'
    	),
    	array(
        	'title' => 'Blockquote',
        	'block' => 'div',
        	'classes' => 'blockquote',
        	'wrapper' => true
        ),
        array(
        	'title' => 'Right Sidewell',
        	'block' => 'div',
        	'classes' => 'well sidewell-right',
        	'wrapper' => true
        ),
        array(
        	'title' => 'Left Sidewell',
        	'block' => 'div',
        	'classes' => 'well sidewell-left',
        	'wrapper' => true
        ),
		array('title' => 'Image styles'),
		array(
    		'title' => 'Rounded Corners',
    		'selector' => 'img',
    		'classes' => 'img-rounded'
    	),
		array(
    		'title' => 'Polaroid',
    		'selector' => 'img',
    		'classes' => 'img-polaroid'
    	),
    	array(
    		'title' => 'Circle',
    		'selector' => 'img',
    		'classes' => 'img-circle'
    	),
    	array('title' => 'Link styles'),
    	array(
    		'title' => 'Button',
    		'selector' => 'a',
    		'classes' => 'btn btn-primary'
    	),

    );

	if(!array_key_exists('style_formats',$settings))
	    $settings['style_formats'] = json_encode( $style_formats );

	$settings['block_formats'] = "Paragraph=p; Preformatted=pre; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4";

    return $settings;
}

// Always show 'kitchen sink' elements...
function unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );

// List any default categories that will be used...
function create_default_categories () {
	if (file_exists (ABSPATH.'/wp-admin/includes/taxonomy.php')) {
		require_once (ABSPATH.'/wp-admin/includes/taxonomy.php');

	    if ( ! get_cat_ID( 'Audience')) {
	    	// Audience category doesn't exist...recreate...
	        $parentid = wp_create_category( 'Audience' );

		    if ( ! get_cat_ID( 'Frontpage Slide')) {
		        wp_create_category( 'Frontpage Slide' );
		    }
		    if ( ! get_cat_ID( 'Academics')) {
		        wp_create_category( 'Academics',$parentid );
		    }
		    if ( ! get_cat_ID( 'Students')) {
		        wp_create_category( 'Students',$parentid );
		    }
		    if ( ! get_cat_ID( 'Faculty and Staff')) {
		        wp_create_category( 'Faculty and Staff',$parentid );
		    }
		    if ( ! get_cat_ID( 'Alumni')) {
		        wp_create_category( 'Alumni',$parentid );
		    }
		    if ( ! get_cat_ID( 'Parents')) {
		        wp_create_category( 'Parents',$parentid );
		    }
	    }
	}
}

// Execute the following on the activation of the theme...
if (isset($_GET['activated']) && is_admin()){
    add_action('init', 'create_default_categories');

	 $samplePage = get_page_by_title( 'Sample Page','OBJECT','page');
	 $helloPost = get_page_by_title( 'Hello world!','OBJECT','post');

	 // Delete the 'Sample Page' if it exists and is the only page...
	 if($samplePage == null && wp_count_posts('page') == 1){
		 wp_delete_post($samplePage->ID,true);
	 }

	 // Delete the 'Hello World' post if it exists and is the only post...
	 if($helloPost == null && wp_count_posts('post') == 1){
		 wp_delete_post($helloPost->ID,true);
	 }

     $frontPage = get_page_by_title( 'Front Page','OBJECT','page');

     if($frontPage == null && wp_count_posts('page') < 2){
	    // Create default front page...
		$my_post = array(
		  'post_title'    => 'Front Page',
		  'post_content'  => 'Add any front page content to this page.',
		  'post_status'   => 'publish',
		  'post_type' => 'page',
		  'post_author'   => 1
		);
		// Insert the post into the database
		$frontID = wp_insert_post( $my_post );

		update_option('show_on_front', 'page');    // show on front a static page
		update_option('page_on_front', $frontID);
		update_post_meta($frontID,'_wp_page_template','page-homepage.php');

	}
}

wp_enqueue_script( 'admin-js', get_template_directory_uri() . '/library/js/admin.js', array(), '1.0.0', true );

// Emergency message post save check. If it's an emergency message and a post is being saved, clear the transient (cache)
function colbyemergency_save_check( $post_id ) {

	if( get_post_type( $post_id ) != 'emergencymessage' ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	delete_transient( 'emergency_homepage' );
}

add_action( 'save_post', 'colbyemergency_save_check' );
