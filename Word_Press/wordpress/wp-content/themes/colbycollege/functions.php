<?php

global $colby_college;

require_once('lib/class-colby-college.php');
require_once('lib/class-asset-handler.php');

if ( ! function_exists( 'pp' ) ) :
	/**
	 * Concise function to pretty-print data in the browser; optionally var_dump; optionally wp_die.
	 *
	 * @param  mixed   $data Any variable.
	 * @param  integer $die  Zero for false, one for true.
	 * @param  integer $dump Zero for false, one for true.
	 */
	function pp( $data, $die = 0, $dump = 0 ) {
		echo '<pre>';
		if ( 1 === $dump ) {
			var_dump( $data );
		} else {
			print_r( $data );
		}
			echo '</pre>';
		if ( 1 === $die ) {
			wp_die(
				'', '', [
					'response' => 200,
				]
			);
		}
	}
endif;

if ( isset( $_GET['debug'] ) ) {
	register_shutdown_function(
		function() {
				pp( error_get_last() );
		}
	);
}

$info = [
	'Theme Name' => 'Colby College',
	'Description' => 'Colby College\'s Wordpress Environment',
	'Version' => '0.0.06',
	'Author' => 'John Watkins',
	'Text Domain' => 'colby-college',
	'Namespace' => 'Colby_College',
	'Admin Email' => 'communicationsweb@colby.edu',
];

if ( ! function_exists( 'register_wp_autoload' ) ) {
	require_once( 'vendor/autoload.php' );
}

register_wp_autoload( 'Colby_College\\', __DIR__ . '/lib' );

/** Create theme object. */
$colby_college = new Colby_College\Colby_College( __FILE__, $info );

new Colby_College\Asset_Handler( $colby_college );

add_action(
	'wp_enqueue_scripts', function() {
		wp_enqueue_script(
			'youvisit',
			'//www.youvisit.com/tour/Embed/js2',
			[],
			'0.1',
			true
		);
	}, 99
);

date_default_timezone_set( 'America/New_York' );

require_once( 'library/bones.php' );            // core functions (don't remove)
require_once( 'library/plugins.php' );          // plugins & extra functions (optional)
require_once( 'library/options-panel.php' );
include_once( 'actions.php' );
include_once( 'filters.php' );
include_once( 'temporary.php' );

// Remove shortlink from header (fix directory Google issue)
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// Shortcodes
require_once( 'library/inc/shortcodes.php' );

// Administration Functions
if ( is_admin() ) {
	require_once( 'library/admin.php' );
}

// Thumbnail sizes
add_image_size( 'wpbs-featured', 638, 300, true );
add_image_size( 'wpbs-featured-home', 970, 311, true );
add_image_size( 'wpbs-featured-carousel', 970, 400, true );
add_image_size( 'slideshow-rectangle', 600, 400, true );
add_image_size( 'slideshow-rectangle-portrait', 153, 230, true );
add_image_size( 'featured-rectangle-medium', 638, 260, true );
add_image_size( 'small-rectangle', 165, 110, true );

// Set content width for oEmbeds, etc...
if ( ! isset( $content_width ) ) {
	$content_width = 580;
}

// Create front page slideshow logic...
include( 'library/inc/front-page-slideshow.php' );
include( 'library/inc/front-page-slideshow-fields.php' );

// Sidebars and widgets...
require_once( 'library/inc/widgets/colbyContactWidget.php' );

// Cleanup widgets that won't be needed...
function remove_unused_widgets() {
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

add_action( 'widgets_init', 'remove_unused_widgets' );

function bones_register_sidebars() {
	register_sidebar(
		array(
			'id' => 'sidebar1',
			'name' => 'Main Sidebar',
			'description' => 'Used on every page EXCEPT the homepage page template.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		)
	);

	register_sidebar(
		array(
			'id' => 'sidebar2',
			'name' => 'Front Page Sidebar',
			'description' => 'Used only on the front page page template.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		)
	);

	register_sidebar(
		array(
			'id' => 'sidebar3',
			'name' => 'Middle Sidebar',
			'description' => 'Used only on the 3-column page template.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		)
	);

	register_sidebar(
		array(
			'id' => 'footer1',
			'name' => 'Footer',
			'before_widget' => '<div id="%1$s">', // class="widget span12 %2$s"
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
		)
	);

	register_sidebar(
		array(
			'id' => 'footercontact',
			'name' => 'Footer Contact',
			'description' => 'Used if the default contact information for the college is to be overwritten.',
			'before_widget' => '<div id="%1$s">', // class="widget span12 %2$s"
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
		)
	);
}

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<div class="comment-author vcard row-fluid clearfix">
				<div class="avatar span3">
					<?php echo get_avatar( $comment, $size = '75' ); ?>
				</div>
				<div class="span9 comment-text">
					<?php printf( '<h4>%s</h4>', get_comment_author_link() ); ?>
					<?php edit_comment_link( __( 'Edit','bonestheme' ),'<span class="edit-comment btn btn-small btn-info"><i class="icon-white icon-pencil"></i>','</span>' ); ?>

					<?php if ( $comment->comment_approved == '0' ) : ?>
						   <div class="alert-message success">
						  <p><?php _e( 'Your comment is awaiting moderation.','bonestheme' ); ?></p>
						  </div>
					<?php endif; ?>

					<?php comment_text(); ?>

					<time datetime="<?php echo comment_time( 'Y-m-j' ); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php comment_time( 'F jS, Y' ); ?> </a></time>

					<?php
					comment_reply_link(
						array_merge(
							$args, array(
								'depth' => $depth,
								'max_depth' => $args['max_depth'],
							)
						)
					);
?>
				</div>
			</div>
		</article>
<?php
}

// Don't show admin bar for subscribers...
add_action( 'set_current_user', 'cc_hide_admin_bar' );

function cc_hide_admin_bar() {
	// $userGroups = pp_get_groups_for_user( get_current_user_id());
	$adminBar = current_user_can_for_blog( get_current_blog_id(),'edit_others_posts' );

	if ( ! $adminBar ) {
		show_admin_bar( false );
	}
}

if ( isset( $_GET['renderforprint'] ) ) {
	show_admin_bar( false );
}

// User permissions...
$role_object = get_role( 'editor' );

// Add capabilities...
if ( is_object( $role_object ) && method_exists( $role_object, 'add_cap' ) ) {
	$role_object->add_cap( 'edit_theme_options' );
}

function colby_account_name() {
	$account = '';

	/* secret key used for authentication */
	$colby_secret = 'DSFGJDfkldsalfkalkDSAFGjioerwroeiR@%$54$56DFGFf
';

	if ( array_key_exists( 'ColbyTicket', $_COOKIE ) ) {
			$cookie_items = split( '&', $_COOKIE['ColbyTicket'] );
		for ( $i = 0; $i < count( $cookie_items ); $i = $i + 2 ) {
						$cookie[ $cookie_items[ $i ] ] = $cookie_items[ $i + 1 ];
		}

			$ip_address = $_SERVER['REMOTE_ADDR'];
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if ( $cookie['hash'] && $cookie['user'] && $cookie['time'] && $cookie['expires'] ) {

			$hash_vals = array( $colby_secret, $cookie['ip'], $cookie['time'], $cookie['expires'], $cookie['user'], $cookie['profile'], $cookie['type'], $user_agent );
			$newhash = md5( $colby_secret . md5( join( ':', $hash_vals ) ) );
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

  <input type="text" value="' . trim( get_search_query() ) . '" name="s" id="s" placeholder="Enter search term..." />
  <input type="submit" id="searchsubmit" value="' . esc_attr__( 'Search','bonestheme' ) . '" />
  </form>';
	return $form;
}

// Enable shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Disable jump in 'read more' link
function remove_more_jump_link( $link ) {
	$offset = strpos( $link, '#more-' );
	if ( $offset ) {
		$end = strpos( $link, '"',$offset );
	}
	if ( $end ) {
		$link = substr_replace( $link, '', $offset, $end - $offset );
	}
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_jump_link' );

// Add thumbnail class to thumbnail links
function add_class_attachment_link( $html ) {
	$postid = get_the_ID();
	$html = str_replace( '<a','<a class="thumbnail"',$html );
	return $html;
}
add_filter( 'wp_get_attachment_link', 'add_class_attachment_link', 10, 1 );

// Menu output mods
/*
 Bootstrap_Walker for WordPress
     * Author: George Huger, Illuminati Karate, Inc
     * More Info: http://illuminatikarate.com/blog/bootstrap-walker-for-wordpress
     *
     * Formats a WordPress menu to be used as a Bootstrap dropdown menu (http://getbootstrap.com).
     *
     * Specifically, it makes these changes to the normal WordPress menu output to support Bootstrap:
     *
     *        - adds a 'dropdown' class to level-0 <li>'s which contain a dropdown
     *         - adds a 'dropdown-submenu' class to level-1 <li>'s which contain a dropdown
     *         - adds the 'dropdown-menu' class to level-1 and level-2 <ul>'s
     *
     * Supports menus up to 3 levels deep.
     *
     */
class Bootstrap_Walker extends Walker_Nav_Menu {


	/*
         Start of the <ul>
	 *
	 * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".
	 *                   So basically add one to what you'd expect it to be
	 */
	function start_lvl( &$output, $depth = 0, $args = [] ) {
		$tabs = str_repeat( "\t", $depth );
		// If we are about to start the first submenu, we need to give it a dropdown-menu class
		if ( $depth == 0 || $depth == 1 ) { // really, level-1 or level-2, because $depth is misleading here (see note above)
			$output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n";
		} else {
			$output .= "\n{$tabs}<ul>\n";
		}
		return;
	}

	/*
         End of the <ul>
	 *
	 * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".
	 *                   So basically add one to what you'd expect it to be
	 */
	function end_lvl( &$output, $depth = 0, $args = [] ) {
		if ( $depth == 0 ) { // This is actually the end of the level-1 submenu ($depth is misleading here too!)

			// we don't have anything special for Bootstrap, so we'll just leave an HTML comment for now
			$output .= '<!--.dropdown-->';
		}
		$tabs = str_repeat( "\t", $depth );
		$output .= "\n{$tabs}</ul>\n";
		return;
	}

	/*
         Output the <li> and the containing <a>
	 * Note: $depth is "correct" at this level
	 */
	function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		/* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */
		if ( $item->hasChildren ) {
			$classes[] = 'dropdown';
			// level-1 menus also need the 'dropdown-submenu' class
			if ( $depth == 1 ) {
				$classes[] = 'dropdown-submenu';
			}
		}

		/* This is the stock WordPress code that builds the <li> with all of its attributes */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';
		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		$item_output = $args->before;

		/* If this item has a dropdown menu, make clicking on this link toggle it */
		if ( $item->hasChildren && $depth == 0 ) {
			$item_output .= '<a' . $attributes . ' class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="100" data-close-others="true">';
		} else {
			$item_output .= '<a' . $attributes . '>';
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

		/* Output the actual caret for the user to click on to toggle the menu */
		if ( $item->hasChildren && $depth == 0 ) {
			$item_output .= '<b class="caret"></b></a>';
		} else {
			$item_output .= '</a>';
		}

		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		return;
	}

	/*
         Close the <li>
	 * Note: the <a> is already closed
	 * Note 2: $depth is "correct" at this level
	 */
	function end_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$output .= '</li>';
		return;
	}

	/*
         Add a 'hasChildren' property to the item
	 * Code from: http://wordpress.org/support/topic/how-do-i-know-if-a-menu-item-has-children-or-is-a-leaf#post-3139633
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		// check whether this item has children, and set $item->hasChildren accordingly
		$element->hasChildren = isset( $children_elements[ $element->ID ] ) && ! empty( $children_elements[ $element->ID ] );

		// continue with normal behavior
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
add_editor_style( 'editor-style.css' );

// Add Twitter Bootstrap's standard 'active' class name to the active nav link item
add_filter( 'nav_menu_css_class', 'add_active_class', 10, 2 );

function add_active_class( $classes, $item ) {
	if ( $item->menu_item_parent == 0 && in_array( 'current-menu-item', $classes ) ) {
		$classes[] = 'active';
	}

	return $classes;
}

// enqueue styles
if ( ! function_exists( 'theme_styles' ) ) {
	function theme_styles() {
		global $colby_college;
		$themeDetails = wp_get_theme();

		// This is the compiled css file from LESS - this means you compile the LESS file locally and put it in the appropriate directory if you want to make any changes to the master bootstrap.css.
		wp_register_style( 'bootstrap', get_template_directory_uri() . '/library/css/bootstrap.css', array(), $themeDetails->Version, 'all' );
		wp_register_style(
			'wp-bootstrap',
			get_template_directory_uri() . '/dist/colby-college.min.css',
			array(),
			$colby_college->version,
			'all'
		);
		wp_register_style( 'fancyboxcss', get_template_directory_uri() . '/library/js/fancybox/source/jquery.fancybox.css', array(), $themeDetails->Version, 'all' );

		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'wp-bootstrap' );
		wp_enqueue_style( 'fancyboxcss' );

		if ( get_the_title() == 'Front Page' && get_current_blog_id() == '1' ) {
			// Colby.edu homepage...trim down stylesheets
			wp_register_style( 'homepagecss', get_template_directory_uri() . '/library/css/homepage.css', array(), $themeDetails->Version, 'all' );
			wp_enqueue_style( 'homepagecss' );
			wp_dequeue_style( 'fancyboxcss' );
			wp_dequeue_style( 'tboot_shortcode_styles' );
			wp_dequeue_style( 'searchcss' );
			wp_dequeue_style( 'wooslider-common' );
		}

		// Load jumbo slideshow CSS in header...
		if ( of_get_option( 'slider_options_type' ) == '2' && (is_front_page() || get_the_title() == 'Front Page') ) {
			wp_register_style( 'jumboslideshow', (get_template_directory_uri() . '/library/css/jumboslideshow.css'), array( 'bootstrap' ), '1.0', 'all' );
			wp_enqueue_style( 'jumboslideshow' );
		}

		if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Firefox' ) !== false ) {
			wp_register_style( 'firefoxstyle', get_template_directory_uri() . '/library/css/firefox.css', array(), $themeDetails->Version, 'all' );
			wp_enqueue_style( 'firefoxstyle' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'theme_styles' );

// enqueue javascript
if ( ! function_exists( 'theme_js' ) ) {
	function theme_js() {

		$themeDetails = empty( $themeDetails ) ? wp_get_theme() : $themeDetails;

		wp_register_script( 'bootstrap', get_template_directory_uri() . '/library/js/bootstrap.min.js', array( 'jquery' ),'1.3',true );
		wp_register_script( 'fancybox', get_template_directory_uri() . '/library/js/fancybox/source/jquery.fancybox.pack.js', array( 'jquery' ),'1.4',true );
		wp_register_script( 'wpbs-scripts', get_template_directory_uri() . '/dist/Colby_College.js', array( 'jquery', 'wooslider-flexslider' ),$themeDetails->Version ,true );

		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'fancybox' );
		wp_enqueue_script( 'wpbs-scripts' );
		wp_enqueue_script( 'wooslider-flexslider' );

		if ( get_the_title() == 'Front Page' && get_current_blog_id() == '1' ) {
			// Colby.edu homepage...trim down stylesheets/scripts
			wp_dequeue_script( 'fancybox' );
			wp_dequeue_script( 'wooslider-fitvids' );
			wp_dequeue_script( 'wooslider-mousewheel' );
			wp_deregister_script( 'comment-reply' );
			wp_dequeue_script( 'comment-reply' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'theme_js' );

remove_theme_support( 'custom-background' );



add_action( 'wp_head','opengraph' );
function opengraph() {
	global $post;

	// Adds Colby Open Graph information...
	if ( has_post_thumbnail( $post->ID ) ) {
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
		$thumbnail_src = esc_attr( $thumbnail_src[0] );
	}

	echo '<meta name="twitter:site" content="@colbycollege"><meta name="twitter:title" content="' . wp_title( '|', false, 'right' ) . '">';

	if ( isset( $thumbnail_src ) && strlen( $thumbnail_src ) ) {
		echo '<meta name="twitter:card" content="summary_large_image" /><meta name="twitter:image" content="' . $thumbnail_src . '">';
	} else {
		echo '<meta name="twitter:card" content="summary" />';
	}

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( ! is_plugin_active( 'facebook/facebook.php' ) ) {
		$thumbnail_src = 'http://www.colby.edu/wp-content/themes/colbycollege/library/images/colby300.jpg';

		if ( get_bloginfo( 'url' ) == 'http://www.colby.edu/museum' ) {
			$thumbnail_src = 'http://www.colby.edu/wp-content/themes/colbymuseum/images/museumofart-share.jpg';
		}
		echo '<meta property="og:locale" content="en_US" /><meta property="fb:app_id" content="109266592476030" />';
		echo '<meta property="og:title" content="' . wp_title( '|', false, 'right' ) . '" />';
		echo '<meta property="og:type" content="article" />';

		$curURL = get_permalink( $post->ID );

		if ( isset( $_GET['profileid'] ) ) {
			$curURL = esc_url( add_query_arg( 'profileid', $_GET['profileid'], get_permalink() ) );
		}

		if ( isset( $_GET['bID'] ) ) {
			$curURL = esc_url( add_query_arg( 'bID', $_GET['bID'], $curURL ) );
		}

		if ( isset( $_GET['rID'] ) ) {
			$curURL = esc_url( add_query_arg( 'rID', $_GET['rID'], $curURL ) );
		}

		echo '<meta property="og:url" content="' . str_replace( 'author.colby.edu','www.colby.edu',$curURL ) . '" />';
		if ( get_bloginfo( 'url' ) == 'http://www.colby.edu/museum' ) {
			echo '<meta property="og:site_name" content="Colby College Museum of Art" />';
		} else {
			echo '<meta property="og:site_name" content="Colby College" />';
		}

		echo '<meta property="og:description" content="' . nl2br( esc_attr( strip_tags( get_the_excerpt( $post->ID ) ) ) ) . '" />';

		if ( ! strlen( trim( $thumbnail_src ) ) ) {
			if ( get_bloginfo( 'url' ) == 'http://www.colby.edu/museum' ) {
				$thumbnail_src = 'http://www.colby.edu/wp-content/themes/colbymuseum/images/museumofart-share.jpg';
			} else {
				$thumbnail_src = 'http://www.colby.edu/wp-content/themes/colbycollege/library/images/colby300.jpg';
			}
		}

		echo '<meta property="og:image" content="' . $thumbnail_src . '"/>';

		echo '<meta name="thumbnail" content="' . $thumbnail_src . '" />';

	}
	return;
}

// header defined images...
if ( ! defined( 'NO_HEADER_TEXT' ) ) {
	define( 'NO_HEADER_TEXT', true ); } // no header text
if ( ! defined( 'HEADER_TEXTCOLOR' ) ) {
	define( 'HEADER_TEXTCOLOR', 'ffffff' ); } // header text color
if ( ! defined( 'HEADER_IMAGE' ) ) {
	define( 'HEADER_IMAGE', get_template_directory_uri() . '/library/images/default_header.jpg' ); } // default header image
if ( ! defined( 'HEADER_IMAGE_WIDTH' ) ) {
	define( 'HEADER_IMAGE_WIDTH', 1600 ); } // the width of the logo
if ( ! defined( 'HEADER_IMAGE_HEIGHT' ) ) {
	define( 'HEADER_IMAGE_HEIGHT', 160 ); } // the height of the logo

register_default_headers(
	array(
		'leaves' => array(
			'url' => '%s/library/images/default_header.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/default_header_thumb.jpg',
			'description' => __( 'Green Leaves', 'bones' ),
		),
		'books' => array(
			'url' => '%s/library/images/copy-header-general-books-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-books-1600x600.jpg',
			'description' => __( 'Books', 'bones' ),
		),
		'booksvibrant' => array(
			'url' => '%s/library/images/copy-header-general-books-vibrant-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-books-vibrant-1600x600.jpg',
			'description' => __( 'Books (vibrant)', 'bones' ),
		),
		'eustis' => array(
			'url' => '%s/library/images/copy-header-general-eustis-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-eustis-1600x600.jpg',
			'description' => __( 'Eustis', 'bones' ),
		),
		'facultyes' => array(
			'url' => '%s/library/images/copy-header-general-faculty-es-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-faculty-es-1600x600.jpg',
			'description' => __( 'Outdoor studies', 'bones' ),
		),
		'lovejoyfall' => array(
			'url' => '%s/library/images/copy-header-general-lovejoy-fall-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-lovejoy-fall-1600x600.jpg',
			'description' => __( 'Fall Leaves', 'bones' ),
		),
		'museum' => array(
			'url' => '%s/library/images/copy-header-general-museum-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-museum-1600x600.jpg',
			'description' => __( 'Colby College Museum of Art', 'bones' ),
		),
		'notebooks' => array(
			'url' => '%s/library/images/copy-header-general-notebooks-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-notebooks-1600x600.jpg',
			'description' => __( 'Notebooks', 'bones' ),
		),
		'olin' => array(
			'url' => '%s/library/images/copy-header-general-olin-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-olin-1600x600.jpg',
			'description' => __( 'Olin', 'bones' ),
		),
		'studentfall' => array(
			'url' => '%s/library/images/copy-header-general-student-fall-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-student-fall-1600x600.jpg',
			'description' => __( 'Students outside in the fall', 'bones' ),
		),
		'studentscomp' => array(
			'url' => '%s/library/images/copy-header-general-students-comp-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/copy-header-general-students-comp-1600x600.jpg',
			'description' => __( 'Students using computers', 'bones' ),
		),
		'generallab' => array(
			'url' => '%s/library/images/header-general-lab-1600x6001.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-lab-1600x6001.jpg',
			'description' => __( 'Lab', 'bones' ),
		),
		'studentdiamond' => array(
			'url' => '%s/library/images/header-general-students-diamond-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-students-diamond-1600x600.jpg',
			'description' => __( 'Diamond building', 'bones' ),
		),
		'studentsgroup' => array(
			'url' => '%s/library/images/header-general-students-group-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-students-group-1600x600.jpg',
			'description' => __( 'Group of students', 'bones' ),
		),
		'summerright' => array(
			'url' => '%s/library/images/header-general-summer-right-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-summer-right-1600x600.jpg',
			'description' => __( 'Green grass', 'bones' ),
		),
		'tower' => array(
			'url' => '%s/library/images/header-general-tower-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-tower-1600x600.jpg',
			'description' => __( 'Miller tower', 'bones' ),
		),
		'writingchem' => array(
			'url' => '%s/library/images/header-general-writing-chem-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-writing-chem-1600x600.jpg',
			'description' => __( 'Writing on chalkboard (chemistry)', 'bones' ),
		),
		'writingfrench' => array(
			'url' => '%s/library/images/header-general-writing-french-1600x600.jpg',
			'thumbnail_url' => '%s/library/images/header-thumbnails/header-general-writing-french-1600x600.jpg',
			'description' => __( 'Writing on chalkboard (french)', 'bones' ),
		),
	)
);

// gets included in the site header
function bones_header_style() {

?>
	<style type="text/css"> header[role=banner] { background: url(<?php header_image(); ?>); } </style>
																						<?php
}
// gets included in the admin header
function admin_header_style() {

?>
	<style type="text/css">
	#headimg {
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	}
	</style>
<?php
}

// START DELETE FOR WP 4.6 UPGRADE (08/18/2016)
// Grab thumnail caption...
if ( ! function_exists( 'the_post_thumbnail_caption' ) ) :
	function the_post_thumbnail_caption() {
		global $post;

		$thumbnail_id    = get_post_thumbnail_id( $post->ID );
		$thumbnail_image = get_posts(
			array(
				'p' => $thumbnail_id,
				'post_type' => 'attachment',
			)
		);

		if ( $thumbnail_image && isset( $thumbnail_image[0] ) ) {
			return $thumbnail_image[0]->post_excerpt;
		}
	}
endif;
// END DELETE FOR WP 4.6 UPGRADE (08/18/2016)
add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
add_filter( 'query_vars','my_insert_query_vars' );
add_action( 'wp_loaded','my_flush_rules' );

// flush_rules() if our rules are not yet included
function my_flush_rules() {
	$rules = get_option( 'rewrite_rules' );

	if ( ! isset( $rules['events/(viewevent)/(\d*)$'] ) ) {
		global $wp_rewrite;
		   $wp_rewrite->flush_rules();
	}
}

// Adding a new rule
function my_insert_rewrite_rules( $rules ) {
	$newrules = array();
	$newrules['events/(viewevent)/(\d*)$'] = 'index.php?pagename=$matches[1]&rid=$matches[2]';
	return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function my_insert_query_vars( $vars ) {
	array_push( $vars, 'rid' );
	return $vars;
}
if ( get_bloginfo( 'name' ) == 'Events' ) {
	remove_action( 'wp_head', 'rel_canonical' );
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
	if ( is_page() && get_bloginfo( 'name' ) == 'College Directory' && isset( $_GET ) && count( $_GET ) && function_exists( 'wp_directory_title' ) ) {
		return wp_directory_title();
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

function the_excerpt_max_charlength( $charlength ) {

	$excerpt = strip_tags( get_the_excerpt(),'<b><i><em><strong>' );
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
function get_wpbs_theme_options() {
	$theme_options_styles = '';

	  $heading_typography = of_get_option( 'heading_typography' );

	if ( isset( $heading_typography['face'] ) && $heading_typography['face'] != 'Default' ) {
		$theme_options_styles .= '
        h1, h2, h3, h4, h5, h6{
          font-family: ' . $heading_typography['face'] . ';
          font-weight: ' . $heading_typography['style'] . ';
          color: ' . $heading_typography['color'] . ';
        }';
	}

	  $main_body_typography = of_get_option( 'main_body_typography' );
	if ( isset( $main_body_typography['face'] ) && $main_body_typography['face'] != 'Default' ) {
		$theme_options_styles .= '
        body{
          font-family: ' . $main_body_typography['face'] . ';
          font-weight: ' . $main_body_typography['style'] . ';
          color: ' . $main_body_typography['color'] . ';
        }';
	}

	  $link_color = of_get_option( 'link_color' );
	if ( $link_color ) {
		$theme_options_styles .= '
        a{
          color: ' . $link_color . ';
        }';
	}

	  $link_hover_color = of_get_option( 'link_hover_color' );
	if ( $link_hover_color ) {
		$theme_options_styles .= '
        a:hover{
          color: ' . $link_hover_color . ';
        }';
	}

	  $link_active_color = of_get_option( 'link_active_color' );
	if ( $link_active_color ) {
		$theme_options_styles .= '
        a:active{
          color: ' . $link_active_color . ';
        }';
	}

	  $topbar_position = of_get_option( 'nav_position' );
	if ( $topbar_position == 'scroll' ) {
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

	if ( $topbar_bg_color && ! $use_gradient ) {
		$theme_options_styles .= '
        .navbar-inner, .navbar .fill {
          background-color: ' . $topbar_bg_color . ';
          background-image: none;
        }' . $topbar_bg_color;
	}

	if ( $use_gradient ) {
		$topbar_bottom_gradient_color = of_get_option( 'top_nav_bottom_gradient_color' );

		$theme_options_styles .= '
        .navbar-inner, .navbar .fill {
          background-image: -khtml-gradient(linear, left top, left bottom, from(' . $topbar_bg_color . '), to(' . $topbar_bottom_gradient_color . '));
          background-image: -moz-linear-gradient(top, ' . $topbar_bg_color . ', ' . $topbar_bottom_gradient_color . ');
          background-image: -ms-linear-gradient(top, ' . $topbar_bg_color . ', ' . $topbar_bottom_gradient_color . ');
          background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ' . $topbar_bg_color . '), color-stop(100%, ' . $topbar_bottom_gradient_color . '));
          background-image: -webkit-linear-gradient(top, ' . $topbar_bg_color . ', ' . $topbar_bottom_gradient_color . '2);
          background-image: -o-linear-gradient(top, ' . $topbar_bg_color . ', ' . $topbar_bottom_gradient_color . ');
          background-image: linear-gradient(top, ' . $topbar_bg_color . ', ' . $topbar_bottom_gradient_color . ');
          filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'' . $topbar_bg_color . '\', endColorstr=\'' . $topbar_bottom_gradient_color . '2\', GradientType=0);
        }';
	} else {
	}

	  $topbar_link_color = of_get_option( 'top_nav_link_color' );
	if ( $topbar_link_color ) {
		$theme_options_styles .= '
        .navbar .nav li a {
          color: ' . $topbar_link_color . ';
        }';
	}

	  $topbar_link_hover_color = of_get_option( 'top_nav_link_hover_color' );
	if ( $topbar_link_hover_color ) {
		$theme_options_styles .= '
        .navbar .nav li a:hover {
          color: ' . $topbar_link_hover_color . ';
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
	if ( $topbar_dropdown_item_color ) {
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
          background-color: ' . $hero_unit_bg_color . ';
        }';
	}

	  $suppress_comments_message = of_get_option( 'suppress_comments_message' );
	if ( strlen( $suppress_comments_message ) ) {
		$theme_options_styles .= '
        #main article {
          border-bottom: none;
        }';
	}

	  $additional_css = of_get_option( 'wpbs_css' );
	if ( $additional_css ) {
		$theme_options_styles .= $additional_css;
	}

	if ( $theme_options_styles ) {
		echo '<style>'
		. htmlspecialchars_decode( $theme_options_styles ) . '
        </style>';
	}

	  $bootstrap_theme = of_get_option( 'wpbs_theme' );
	  $use_theme = of_get_option( 'showhidden_themes' );

	if ( $bootstrap_theme && $use_theme ) {
		if ( $bootstrap_theme == 'default' ) {
		} else {
			echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/admin/themes/' . $bootstrap_theme . '.css">';
		}
	}
} // end get_wpbs_theme_options function

// Emergency message check...
include_once( 'library/inc/emergencyMessages.php' );

// nofollow functionality
function addnoindex() {
	add_action( 'wp_head', 'enqueue_noindexfollow' );
}

function enqueue_noindexfollow() {
	echo '<meta name="robots" content="noindex,follow">';
}

function add_theme_caps() {
	$role = get_role( 'administrator' );
	$role->add_cap( 'manage_network_plugins' );
}

if ( get_bloginfo( 'url' ) == 'http://www.colby.edu/libraries' ) {
	add_action( 'admin_init', 'add_theme_caps' );
}

if ( strpos( get_bloginfo( 'url' ), 'colby.edu/service-catalog' ) !== false ) {
	/**
	 * Join posts and postmeta tables
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
	 */
	function cf_search_join( $join ) {
		global $wpdb;

		if ( is_search() ) {
			$join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
		}

		return $join;
	}
	add_filter( 'posts_join', 'cf_search_join' );

	/**
	 * Modify the search query with posts_where
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
	 */
	function cf_search_where( $where ) {
		global $wpdb;

		if ( is_search() ) {
			$where = preg_replace(
				'/\(\s*' . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
				'(' . $wpdb->posts . '.post_title LIKE $1) OR (' . $wpdb->postmeta . '.meta_value LIKE $1)', $where
			);
		}

		return $where;
	}
	add_filter( 'posts_where', 'cf_search_where' );

	/**
	 * Prevent duplicates
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
	 */
	function cf_search_distinct( $where ) {
		global $wpdb;

		if ( is_search() ) {
			return 'DISTINCT';
		}

		return $where;
	}
	add_filter( 'posts_distinct', 'cf_search_distinct' );
}

add_shortcode(
	'download', function( $atts ) {
		if ( ! $atts['media-id'] ) {
			return '';
		}

		$image = wp_get_attachment_image_src( $atts['media-id'], 'full' );

		if ( ! $image ) {
			return '';
		}

		$alt = isset( $atts['alt'] ) ? esc_attr( $atts['alt'] ) : '';

		return "
<a class=nogallery href=\"{$image[0]}\" download>
	<img src=\"{$image[0]}\" width={$image[1]} height={$image[2]} alt=\"{$alt}\">
</a>
<div>
	<a class=\"nogallery btn btn-primary\" href=\"{$image[0]}\" download>Download</a>
</div>";

	}
);
