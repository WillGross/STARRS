<?php

date_default_timezone_set('America/New_York');
require_once('library/bones.php');            // core functions (don't remove)
require_once('library/plugins.php');          // plugins & extra functions (optional)

// Options panel
require_once('library/options-panel.php');

// Remove shortlink from header (fix directory Google issue)
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
// Shortcodes
require_once('library/inc/shortcodes.php');

// Administration Functions
require_once('library/admin.php');         // custom admin functions

// Thumbnail sizes
add_image_size( 'wpbs-featured', 638, 300, true );
add_image_size( 'wpbs-featured-home', 970, 311, true);
add_image_size( 'wpbs-featured-carousel', 970, 400, true);
add_image_size( 'slideshow-rectangle', 600, 400, true );
add_image_size( 'featured-rectangle-medium', 638, 260, true );
add_image_size( 'small-rectangle', 165, 110, true );

// Set content width for oEmbeds, etc...
if ( ! isset( $content_width ) )
	$content_width = 580;

// Create front page slideshow logic...
include('library/inc/front-page-slideshow.php');
include('library/inc/front-page-slideshow-fields.php');

// Sidebars and widgets...
require_once('library/inc/widgets/colbyContactWidget.php');

// Cleanup widgets that won't be needed...
function remove_unused_widgets() {
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_Tag_Cloud');
}

add_action( 'widgets_init', 'remove_unused_widgets' );

function bones_register_sidebars() {
    register_sidebar(array(
    	'id' => 'sidebar1',
    	'name' => 'Main Sidebar',
    	'description' => 'Used on every page EXCEPT the homepage page template.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));

    register_sidebar(array(
    	'id' => 'sidebar2',
    	'name' => 'Front Page Sidebar',
    	'description' => 'Used only on the front page page template.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));

    register_sidebar(array(
    	'id' => 'sidebar3',
    	'name' => 'Middle Sidebar',
    	'description' => 'Used only on the 3-column page template.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));

    register_sidebar(array(
      'id' => 'footer1',
      'name' => 'Footer',
      'before_widget' => '<div id="%1$s">', //  class="widget span12 %2$s"
      'after_widget' => '</div>',
      'before_title' => '<h4 class="widgettitle">',
      'after_title' => '</h4>',
    ));
}

// Comment Layout
function bones_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<div class="comment-author vcard row-fluid clearfix">
				<div class="avatar span3">
					<?php echo get_avatar( $comment, $size='75' ); ?>
				</div>
				<div class="span9 comment-text">
					<?php printf('<h4>%s</h4>', get_comment_author_link()) ?>
					<?php edit_comment_link(__('Edit','bonestheme'),'<span class="edit-comment btn btn-small btn-info"><i class="icon-white icon-pencil"></i>','</span>') ?>

                    <?php if ($comment->comment_approved == '0') : ?>
       					<div class="alert-message success">
          				<p><?php _e('Your comment is awaiting moderation.','bonestheme') ?></p>
          				</div>
					<?php endif; ?>

                    <?php comment_text() ?>

                    <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('F jS, Y'); ?> </a></time>

					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
			</div>
		</article>
<?php
}

// Display trackbacks/pings callback function
function list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
        <li id="comment-<?php comment_ID(); ?>"><i class="icon icon-share-alt"></i>&nbsp;<?php comment_author_link(); ?>
<?php

}

// Only display comments in comment count (which isn't currently displayed in wp-bootstrap, but i'm putting this in now so i don't forget to later)
add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
	    $comments_by_type = separate_comments(get_comments('status=approve&post_id=' . $id));
	    return count($comments_by_type['comment']);
	} else {
	    return $count;
	}
}

// Don't show admin bar for subscribers...
add_action('set_current_user', 'cc_hide_admin_bar');
//$userGroups = pp_get_groups_for_user( get_current_user_id());

function cc_hide_admin_bar() {
	//$userGroups = pp_get_groups_for_user( get_current_user_id());
	$adminBar = current_user_can_for_blog(get_current_blog_id(),'edit_others_posts');

	if (!$adminBar) {
		show_admin_bar(false);
	}
}

if(isset($_GET['renderforprint'])) {
	show_admin_bar( false );
}

// User permissions...
$role_object = get_role( 'editor' );
// Add capabilities...
$role_object->add_cap( 'edit_theme_options' );
//$role_object->add_cap( 'edit_others_pages' );

// 2014-03-28 commented out this section, it isn't doing anything and it was throwing php warnings to syslog which filled up the filesystem. SKT
//foreach($userGroups as $group) {
//	if($group->group_id == 2 || $group->group_id == 1) {
//		// Editor or administrator...
//		if($group->group_id == 2) {
//			// If editor and site is academic site, prevent editing front page.
//
//		}
//
//	}
//}

function colby_account_name() {
    $account = '';

    /* secret key used for authentication */
    $colby_secret='DSFGJDfkldsalfkalkDSAFGjioerwroeiR@%$54$56DFGFf
';

    if ( array_key_exists( 'ColbyTicket', $_COOKIE ) ) {
            $cookie_items = split( '&', $_COOKIE['ColbyTicket'] );
            for ( $i = 0; $i < count( $cookie_items ); $i = $i+2 ) {
                            $cookie[ $cookie_items[ $i ] ] = $cookie_items[ $i+1 ];
            }

            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if ( $cookie['hash'] && $cookie['user'] && $cookie['time'] && $cookie['expires'] ) {

                $hash_vals = array( $colby_secret, $cookie['ip'], $cookie['time'], $cookie['expires'], $cookie['user'], $cookie['profile'], $cookie['type'], $user_agent );
                $newhash = md5( $colby_secret.md5( join( ':', $hash_vals ) ) );
				if ( $newhash == $cookie['hash'] ) {
                    $account = $cookie['user'];
				}
       }
    } /* end array_key_exists */

    return $account;
}

// Search Form
function bones_wpsearch( $form ) {
  $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >

  <input type="text" value="' . ( trim( get_search_query() ) ? get_search_query() : '' ) . '" name="s" id="s" class="test" placeholder="Enter search term..." />
  <input type="submit" id="searchsubmit" value="'. esc_attr__('Search','bonestheme') .'" />
  </form>';
  return $form;
} // don't remove this bracket!


add_filter( 'the_password_form', 'custom_password_form' );

function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<div class="clearfix"><form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
	' . '<p>' . __( "This post is password protected. To view it please enter your password below:" ,'bonestheme') . '</p>' . '
	<label for="' . $label . '">' . __( "Password:" ,'bonestheme') . ' </label><div class="input-append"><input name="post_password" id="' . $label . '" type="password" size="20" /><input type="submit" name="Submit" class="btn btn-primary" value="' . esc_attr__( "Submit",'bonestheme' ) . '" /></div>
	</form></div>
	';
	return $o;
}

// Enable shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Disable jump in 'read more' link
function remove_more_jump_link( $link ) {
	$offset = strpos($link, '#more-');
	if ( $offset ) {
		$end = strpos( $link, '"',$offset );
	}
	if ( $end ) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_jump_link' );

// Remove height/width attributes on images so they can be responsive
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );

function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

// Add the Meta Box to the homepage template
function add_homepage_meta_box() {
	global $post;

	// Only add homepage meta box if template being used is the homepage template
	// $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : "");
	$post_id = $post->ID;
	$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);

	if ( $template_file == 'page-homepage.php' ){
	    add_meta_box(
	        'homepage_meta_box', // $id
	        'Optional Homepage Tagline', // $title
	        'show_homepage_meta_box', // $callback
	        'page', // $page
	        'normal', // $context
	        'high'); // $priority
    }
}

add_action( 'add_meta_boxes', 'add_homepage_meta_box' );

// Field Array
$prefix = 'custom_';
$custom_meta_fields = array(
    array(
        'label'=> 'Homepage tagline area',
        'desc'  => 'Displayed underneath page title. Only used on homepage template. HTML can be used.',
        'id'    => $prefix.'tagline',
        'type'  => 'textarea'
    )
);

// The Homepage Meta Box Callback
function show_homepage_meta_box() {
  global $custom_meta_fields, $post;

  // Use nonce for verification
  wp_nonce_field( basename( __FILE__ ), 'wpbs_nonce' );

  // Begin the field table and loop
  echo '<table class="form-table">';

  foreach ( $custom_meta_fields as $field ) {
      // get value of this field if it exists for this post
      $meta = get_post_meta($post->ID, $field['id'], true);
      // begin a table row with
      echo '<tr>
              <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
              <td>';
              switch($field['type']) {
                  // text
                  case 'text':
                      echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="60" />
                          <br /><span class="description">'.$field['desc'].'</span>';
                  break;

                  // textarea
                  case 'textarea':
                      echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="80" rows="4">'.$meta.'</textarea>
                          <br /><span class="description">'.$field['desc'].'</span>';
                  break;
              } //end switch
      echo '</td></tr>';
  } // end foreach
  echo '</table>'; // end table
}

// Save the Data
function save_homepage_meta( $post_id ) {

    global $custom_meta_fields;

    // verify nonce
    if ( !isset( $_POST['wpbs_nonce'] ) || !wp_verify_nonce($_POST['wpbs_nonce'], basename(__FILE__)) )
        return $post_id;

    // check autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;

    // check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
        } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
    }

    // loop through fields and save the data
    foreach ( $custom_meta_fields as $field ) {
        $old = get_post_meta( $post_id, $field['id'], true );
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta( $post_id, $field['id'], $new );
        } elseif ( '' == $new && $old ) {
            delete_post_meta( $post_id, $field['id'], $old );
        }
    } // end foreach
}
add_action( 'save_post', 'save_homepage_meta' );

// Add thumbnail class to thumbnail links
function add_class_attachment_link( $html ) {
    $postid = get_the_ID();
    $html = str_replace( '<a','<a class="thumbnail"',$html );
    return $html;
}
add_filter( 'wp_get_attachment_link', 'add_class_attachment_link', 10, 1 );

// Add lead class to first paragraph
function first_paragraph( $content ){
    global $post;

    // if we're on the homepage, don't add the lead class to the first paragraph of text
    return $content;
    /*
    if( is_page_template( 'page-homepage.php' ) )
        return $content;
    else
        return preg_replace('/<p([^>]+)?>/', '<p$1 class="lead">', $content, 1);
    */
}
add_filter( 'the_content', 'first_paragraph' );

// Menu output mods
/* Bootstrap_Walker for Wordpress
     * Author: George Huger, Illuminati Karate, Inc
     * More Info: http://illuminatikarate.com/blog/bootstrap-walker-for-wordpress
     *
     * Formats a Wordpress menu to be used as a Bootstrap dropdown menu (http://getbootstrap.com).
     *
     * Specifically, it makes these changes to the normal Wordpress menu output to support Bootstrap:
     *
     *        - adds a 'dropdown' class to level-0 <li>'s which contain a dropdown
     *         - adds a 'dropdown-submenu' class to level-1 <li>'s which contain a dropdown
     *         - adds the 'dropdown-menu' class to level-1 and level-2 <ul>'s
     *
     * Supports menus up to 3 levels deep.
     *
     */
    class Bootstrap_Walker extends Walker_Nav_Menu
    {

        /* Start of the <ul>
         *
         * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".
         *                   So basically add one to what you'd expect it to be
         */
        function start_lvl(&$output, $depth)
        {
            $tabs = str_repeat("\t", $depth);
            // If we are about to start the first submenu, we need to give it a dropdown-menu class
            if ($depth == 0 || $depth == 1) { //really, level-1 or level-2, because $depth is misleading here (see note above)
                $output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n";
            } else {
                $output .= "\n{$tabs}<ul>\n";
            }
            return;
        }

        /* End of the <ul>
         *
         * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".
         *                   So basically add one to what you'd expect it to be
         */
        function end_lvl(&$output, $depth)
        {
            if ($depth == 0) { // This is actually the end of the level-1 submenu ($depth is misleading here too!)

                // we don't have anything special for Bootstrap, so we'll just leave an HTML comment for now
                $output .= '<!--.dropdown-->';
            }
            $tabs = str_repeat("\t", $depth);
            $output .= "\n{$tabs}</ul>\n";
            return;
        }

        /* Output the <li> and the containing <a>
         * Note: $depth is "correct" at this level
         */
        function start_el(&$output, $item, $depth, $args)
        {
            global $wp_query;
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            $class_names = $value = '';
            $classes = empty( $item->classes ) ? array() : (array) $item->classes;

            /* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */
            if ($item->hasChildren) {
                $classes[] = 'dropdown';
                // level-1 menus also need the 'dropdown-submenu' class
                if($depth == 1) {
                    $classes[] = 'dropdown-submenu';
                }
            }

            /* This is the stock Wordpress code that builds the <li> with all of its attributes */
            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
            $class_names = ' class="' . esc_attr( $class_names ) . '"';
            $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
            $item_output = $args->before;

            /* If this item has a dropdown menu, make clicking on this link toggle it */
            if ($item->hasChildren && $depth == 0) {
                $item_output .= '<a'. $attributes .' class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="100" data-close-others="true">';
            } else {
                $item_output .= '<a'. $attributes .'>';
            }

            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

            /* Output the actual caret for the user to click on to toggle the menu */
            if ($item->hasChildren && $depth == 0) {
                $item_output .= '<b class="caret"></b></a>';
            } else {
                $item_output .= '</a>';
            }

            $item_output .= $args->after;
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            return;
        }

        /* Close the <li>
         * Note: the <a> is already closed
         * Note 2: $depth is "correct" at this level
         */
        function end_el (&$output, $item, $depth, $args)
        {
            $output .= '</li>';
            return;
        }

        /* Add a 'hasChildren' property to the item
         * Code from: http://wordpress.org/support/topic/how-do-i-know-if-a-menu-item-has-children-or-is-a-leaf#post-3139633
         */
        function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
        {
            // check whether this item has children, and set $item->hasChildren accordingly
            $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);

            // continue with normal behavior
            return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
        }
    }
add_editor_style('editor-style.css');

// Add Twitter Bootstrap's standard 'active' class name to the active nav link item
add_filter('nav_menu_css_class', 'add_active_class', 10, 2 );

function add_active_class($classes, $item) {
	if( $item->menu_item_parent == 0 && in_array('current-menu-item', $classes) ) {
    $classes[] = "active";
	}

  return $classes;
}

// enqueue styles
if( !function_exists("theme_styles") ) {
    function theme_styles() {
    	$themeDetails = wp_get_theme();

        // This is the compiled css file from LESS - this means you compile the LESS file locally and put it in the appropriate directory if you want to make any changes to the master bootstrap.css.
        wp_register_style( 'bootstrap', get_template_directory_uri() . '/library/css/bootstrap.css', array(), $themeDetails->Version, 'all' );
        wp_register_style( 'wp-bootstrap', get_stylesheet_uri(), array(), $themeDetails->Version, 'all' );
        wp_register_style( 'fancyboxcss', get_template_directory_uri() . '/library/js/fancybox/source/jquery.fancybox.css', array(), $themeDetails->Version, 'all' );

        wp_enqueue_style( 'bootstrap' );
        wp_enqueue_style( 'wp-bootstrap');
        wp_enqueue_style( 'fancyboxcss');

		if(get_the_title()=='Front Page' && get_current_blog_id()=='1'){
			// Colby.edu homepage...trim down stylesheets
        	wp_register_style( 'homepagecss', get_template_directory_uri() . '/library/css/homepage.css', array(), $themeDetails->Version, 'all' );
        	wp_enqueue_style( 'homepagecss');
        	wp_dequeue_style('fancyboxcss');
        	wp_dequeue_style('tboot_shortcode_styles');
        	wp_dequeue_style('searchcss');
        	wp_dequeue_style('wooslider-common');
        }

        // Load jumbo slideshow CSS in header...
		if(of_get_option('slider_options_type')=='2' && (is_front_page() || get_the_title()=='Front Page')){
			wp_register_style( 'jumboslideshow', (get_template_directory_uri() . '/library/css/jumboslideshow.css'), array('bootstrap'), '1.0', 'all' );
			wp_enqueue_style( 'jumboslideshow' );
		}

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
	        wp_register_style( 'firefoxstyle', get_template_directory_uri() . '/library/css/firefox.css', array(), $themeDetails->Version, 'all' );
	        wp_enqueue_style( 'firefoxstyle');
        }
     }
}
add_action( 'wp_enqueue_scripts', 'theme_styles' );

// enqueue javascript
if( !function_exists( "theme_js" ) ) {
  function theme_js(){

    wp_register_script( 'bootstrap', get_template_directory_uri() . '/library/js/bootstrap.min.js', array('jquery'),'1.2',true );
    wp_register_script(  'fancybox', get_template_directory_uri() . '/library/js/fancybox/source/jquery.fancybox.pack.js', array('jquery'),'1.2',true);
    wp_register_script( 'wpbs-scripts', get_template_directory_uri() . '/library/js/scripts.js', array('jquery'),$themeDetails->Version,true );

    wp_enqueue_script('bootstrap');
    wp_enqueue_script('fancybox');
    wp_enqueue_script('wpbs-scripts');

	if(get_the_title()=='Front Page' && get_current_blog_id()=='1'){
		// Colby.edu homepage...trim down stylesheets/scripts
    	wp_dequeue_script('fancybox');
    	wp_dequeue_script('wooslider-fitvids');
    	wp_dequeue_script('wooslider-mousewheel');
		wp_deregister_script( 'comment-reply' );
    	wp_dequeue_script( 'comment-reply');
    }
  }
}
add_action( 'wp_enqueue_scripts', 'theme_js' );

remove_theme_support('custom-background');



add_action('wp_head','opengraph');
function opengraph(){
	// Adds Colby Open Graph information...

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if(!is_plugin_active('facebook/facebook.php')){
		$thumbnail_src="http://www.colby.edu/wp-content/themes/colbycollege/library/images/colby300.jpg";
		echo '<meta property="og:locale" content="en_US" /><meta property="fb:app_id" content="109266592476030" />';
		echo '<meta property="og:title" content="' . wp_title( '&middot;', false, 'right' ) . '" />';
		echo '<meta property="og:type" content="article" />';

		$curURL = get_permalink($post->ID);

		if(isset($_GET['profileid']))
			$curURL = add_query_arg( 'profileid', $_GET['profileid'], get_permalink() );
		if(isset($_GET['bID'])) {
			$curURL = add_query_arg( 'bID', $_GET['bID'], $curURL );
		}
		if(isset($_GET['rID']))
			$curURL = add_query_arg( 'rID', $_GET['rID'], $curURL );

		echo '<meta property="og:url" content="' . str_replace('author.colby.edu','www.colby.edu',$curURL) . '" />';
		echo '<meta property="og:site_name" content="Colby College" />';

		echo '<meta property="og:description" content="'.nl2br(esc_attr( strip_tags(get_the_excerpt($post->ID)))).'" />';
		if(has_post_thumbnail( $post->ID )){
	        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
	        $thumbnail_src = esc_attr($thumbnail_src[0]);
	    }
	    if(!strlen(trim($thumbnail_src)))
	    	$thumbnail_src="http://www.colby.edu/wp-content/themes/colbycollege/library/images/colby300.jpg";
	    echo '<meta property="og:image" content="' .$thumbnail_src . '"/>';
	}
	return;
}

// header defined images...
if (!defined('NO_HEADER_TEXT')) { define('NO_HEADER_TEXT', true ); } // no header text
if (!defined('HEADER_TEXTCOLOR')) { define('HEADER_TEXTCOLOR', 'ffffff'); } // header text color
if (!defined('HEADER_IMAGE')) { define('HEADER_IMAGE', get_template_directory_uri() . '/library/images/default_header.jpg'); } // default header image
if (!defined('HEADER_IMAGE_WIDTH')) { define('HEADER_IMAGE_WIDTH', 1600); } // the width of the logo
if (!defined('HEADER_IMAGE_HEIGHT')) { define('HEADER_IMAGE_HEIGHT', 160); } // the height of the logo

register_default_headers( array(
	'leaves' => array(
		'url' => '%s/library/images/default_header.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/default_header_thumb.jpg',
		'description' => __( 'Green Leaves', 'bones' )
	),
	'books' => array(
		'url' => '%s/library/images/copy-header-general-books-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-books-1600x600.jpg',
		'description' => __( 'Books', 'bones' )
	),
	'booksvibrant' => array(
		'url' => '%s/library/images/copy-header-general-books-vibrant-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-books-vibrant-1600x600.jpg',
		'description' => __( 'Books (vibrant)', 'bones' )
	),
	'eustis' => array(
		'url' => '%s/library/images/copy-header-general-eustis-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-eustis-1600x600.jpg',
		'description' => __( 'Eustis', 'bones' )
	),
	'facultyes' => array(
		'url' => '%s/library/images/copy-header-general-faculty-es-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-faculty-es-1600x600.jpg',
		'description' => __( 'Outdoor studies', 'bones' )
	),
	'lovejoyfall' => array(
		'url' => '%s/library/images/copy-header-general-lovejoy-fall-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-lovejoy-fall-1600x600.jpg',
		'description' => __( 'Fall Leaves', 'bones' )
	),
	'museum' => array(
		'url' => '%s/library/images/copy-header-general-museum-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-museum-1600x600.jpg',
		'description' => __( 'Colby College Museum of Art', 'bones' )
	),
	'notebooks' => array(
		'url' => '%s/library/images/copy-header-general-notebooks-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-notebooks-1600x600.jpg',
		'description' => __( 'Notebooks', 'bones' )
	),
	'olin' => array(
		'url' => '%s/library/images/copy-header-general-olin-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-olin-1600x600.jpg',
		'description' => __( 'Olin', 'bones' )
	),
	'studentfall' => array(
		'url' => '%s/library/images/copy-header-general-student-fall-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-student-fall-1600x600.jpg',
		'description' => __( 'Students outside in the fall', 'bones' )
	),
	'studentscomp' => array(
		'url' => '%s/library/images/copy-header-general-students-comp-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-students-comp-1600x600.jpg',
		'description' => __( 'Students using computers', 'bones' )
	),
	'generallab' => array(
		'url' => '%s/library/images/header-general-lab-1600x6001.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-lab-1600x6001.jpg',
		'description' => __( 'Lab', 'bones' )
	),
	'studentdiamond' => array(
		'url' => '%s/library/images/header-general-students-diamond-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-students-diamond-1600x600.jpg',
		'description' => __( 'Diamond building', 'bones' )
	),
	'studentsgroup' => array(
		'url' => '%s/library/images/header-general-students-group-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-students-group-1600x600.jpg',
		'description' => __( 'Group of students', 'bones' )
	),
	'summerright' => array(
		'url' => '%s/library/images/header-general-summer-right-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-summer-right-1600x600.jpg',
		'description' => __( 'Green grass', 'bones' )
	),
	'tower' => array(
		'url' => '%s/library/images/header-general-tower-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-tower-1600x600.jpg',
		'description' => __( 'Miller tower', 'bones' )
	),
	'writingchem' => array(
		'url' => '%s/library/images/header-general-writing-chem-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-writing-chem-1600x600.jpg',
		'description' => __( 'Writing on chalkboard (chemistry)', 'bones' )
	),
	'writingfrench' => array(
		'url' => '%s/library/images/header-general-writing-french-1600x600.jpg',
		'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-writing-french-1600x600.jpg',
		'description' => __( 'Writing on chalkboard (french)', 'bones' )
	)
) );

// gets included in the site header
function bones_header_style() { ?>
    <style type="text/css"> header[role=banner] { background: url(<?php header_image(); ?>); } </style><?php
}
// gets included in the admin header
function admin_header_style() { ?>
    <style type="text/css">
	#headimg {
	    width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	    height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	}
    </style>
<?php }

// Grab thumnail caption...
function the_post_thumbnail_caption() {
  global $post;

  $thumbnail_id    = get_post_thumbnail_id($post->ID);
  $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

  if ($thumbnail_image && isset($thumbnail_image[0])) {
    return $thumbnail_image[0]->post_excerpt;
  }
}

add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
add_filter( 'query_vars','my_insert_query_vars' );
add_action( 'wp_loaded','my_flush_rules' );

// flush_rules() if our rules are not yet included
function my_flush_rules(){
	$rules = get_option( 'rewrite_rules' );

	if ( ! isset( $rules['events/(viewevent)/(\d*)$'] ) ) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}

// Adding a new rule
function my_insert_rewrite_rules( $rules ){
	$newrules = array();
	$newrules['events/(viewevent)/(\d*)$'] = 'index.php?pagename=$matches[1]&rid=$matches[2]';
	return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function my_insert_query_vars( $vars ){
    array_push($vars, 'rid');
    return $vars;
}
if(get_bloginfo('name') == 'Events') {
	remove_action('wp_head', 'rel_canonical');
	remove_action( 'template_redirect',   'wp_shortlink_header', 11, 0 );
	function at_remove_dup_canonical_link() {
		return false;
	}
	add_filter( 'wpseo_canonical', 'at_remove_dup_canonical_link' );

}

// Title filter...
function bones_filter_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// College directory title...
	if(is_page() && get_bloginfo('name')=='College Directory' && isset($_GET) && count($_GET))
		return wp_directory_title();

	if(is_single() && get_bloginfo('name')=='Events' && isset($_GET) && count($_GET))
		return wp_events_title();

	// Athletics title...
	if(get_post_type()=='sport' && get_bloginfo('name')=='Athletics') {
		return wp_athletics_title();
	}

	// Course Catalogue titles..
	if(strpos(get_bloginfo('wpurl'),'catalogue') !== false){
		if(get_query_var('pagename')=='requirements' || get_query_var('pagename')=='courses') {
			return wp_catalogue_title();
		}
	}

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'bonestheme' ), max( $paged, $page ) );
	}

	return $title;
}

add_filter( 'wp_title', 'bones_filter_title', 10, 2 );

function the_excerpt_max_charlength($charlength) {

	$excerpt = strip_tags(get_the_excerpt(),'<b><i><em><strong>');
	$charlength++;
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			return mb_substr( $subex, 0, $excut );
		} else {
			return $subex;
		}
		echo '...';
	} else {
		return $excerpt;
	}
}
// Get theme options
function get_wpbs_theme_options(){
  $theme_options_styles = '';

      $heading_typography = of_get_option( 'heading_typography' );

      if (isset($heading_typography['face']) && $heading_typography['face'] != 'Default' ) {
        $theme_options_styles .= '
        h1, h2, h3, h4, h5, h6{
          font-family: ' . $heading_typography['face'] . ';
          font-weight: ' . $heading_typography['style'] . ';
          color: ' . $heading_typography['color'] . ';
        }';
      }

      $main_body_typography = of_get_option( 'main_body_typography' );
      if ( isset($main_body_typography['face']) && $main_body_typography['face'] != 'Default' ) {
        $theme_options_styles .= '
        body{
          font-family: ' . $main_body_typography['face'] . ';
          font-weight: ' . $main_body_typography['style'] . ';
          color: ' . $main_body_typography['color'] . ';
        }';
      }

      $link_color = of_get_option( 'link_color' );
      if ($link_color) {
        $theme_options_styles .= '
        a{
          color: ' . $link_color . ';
        }';
      }

      $link_hover_color = of_get_option( 'link_hover_color' );
      if ($link_hover_color) {
        $theme_options_styles .= '
        a:hover{
          color: ' . $link_hover_color . ';
        }';
      }

      $link_active_color = of_get_option( 'link_active_color' );
      if ($link_active_color) {
        $theme_options_styles .= '
        a:active{
          color: ' . $link_active_color . ';
        }';
      }

      $topbar_position = of_get_option( 'nav_position' );
      if ($topbar_position == 'scroll') {
        $theme_options_styles .= '
        .navbar{
          position: static;
        }
        body{
          padding-top: 0;
        }
        #content {
          padding-top: 27px;
        }
        '
        ;
      }

      $topbar_bg_color = of_get_option( 'top_nav_bg_color' );
      $use_gradient = of_get_option( 'showhidden_gradient' );

      if ( $topbar_bg_color && !$use_gradient ) {
        $theme_options_styles .= '
        .navbar-inner, .navbar .fill {
          background-color: '. $topbar_bg_color . ';
          background-image: none;
        }' . $topbar_bg_color;
      }

      if ( $use_gradient ) {
        $topbar_bottom_gradient_color = of_get_option( 'top_nav_bottom_gradient_color' );

        $theme_options_styles .= '
        .navbar-inner, .navbar .fill {
          background-image: -khtml-gradient(linear, left top, left bottom, from(' . $topbar_bg_color . '), to('. $topbar_bottom_gradient_color . '));
          background-image: -moz-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
          background-image: -ms-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
          background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ' . $topbar_bg_color . '), color-stop(100%, '. $topbar_bottom_gradient_color . '));
          background-image: -webkit-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . '2);
          background-image: -o-linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
          background-image: linear-gradient(top, ' . $topbar_bg_color . ', '. $topbar_bottom_gradient_color . ');
          filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'' . $topbar_bg_color . '\', endColorstr=\''. $topbar_bottom_gradient_color . '2\', GradientType=0);
        }';
      }
      else{
      }

      $topbar_link_color = of_get_option( 'top_nav_link_color' );
      if ( $topbar_link_color ) {
        $theme_options_styles .= '
        .navbar .nav li a {
          color: '. $topbar_link_color . ';
        }';
      }

      $topbar_link_hover_color = of_get_option( 'top_nav_link_hover_color' );
      if ( $topbar_link_hover_color ) {
        $theme_options_styles .= '
        .navbar .nav li a:hover {
          color: '. $topbar_link_hover_color . ';
        }';
      }

      $topbar_dropdown_hover_bg_color = of_get_option( 'top_nav_dropdown_hover_bg' );
      if ( $topbar_dropdown_hover_bg_color ) {
        $theme_options_styles .= '
          .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover {
            background-color: ' . $topbar_dropdown_hover_bg_color . ';
          }
        ';
      }

      $topbar_dropdown_item_color = of_get_option( 'top_nav_dropdown_item' );
      if ( $topbar_dropdown_item_color ){
        $theme_options_styles .= '
          .dropdown-menu a{
            color: ' . $topbar_dropdown_item_color . ' !important;
          }
        ';
      }

      $hero_unit_bg_color = of_get_option( 'hero_unit_bg_color' );
      if ( $hero_unit_bg_color ) {
        $theme_options_styles .= '
        .hero-unit {
          background-color: '. $hero_unit_bg_color . ';
        }';
      }

      $suppress_comments_message = of_get_option( 'suppress_comments_message' );
      if ( strlen($suppress_comments_message) ){
        $theme_options_styles .= '
        #main article {
          border-bottom: none;
        }';
      }

      $additional_css = of_get_option( 'wpbs_css' );
      if( $additional_css ){
        $theme_options_styles .= $additional_css;
      }

      if( $theme_options_styles ){
        echo '<style>'
        . htmlspecialchars_decode($theme_options_styles) . '
        </style>';
      }

      $bootstrap_theme = of_get_option( 'wpbs_theme' );
      $use_theme = of_get_option( 'showhidden_themes' );

      if( $bootstrap_theme && $use_theme ){
        if( $bootstrap_theme == 'default' ){}
        else {
          echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/admin/themes/' . $bootstrap_theme . '.css">';
        }
      }
} // end get_wpbs_theme_options function


// CUSTOM FIELDS...
add_action('acf/include_field_types', 'my_register_fields');
add_action('acf/add_fields', 'my_register_fields4');

function my_register_fields(){
	include_once('library/inc/fields/EMS-v5.php');
}

function my_register_fields4(){
	include_once('library/inc/fields/EMS-v4.php');
}


// Emergency message check...
include_once('library/inc/emergencyMessages.php');
