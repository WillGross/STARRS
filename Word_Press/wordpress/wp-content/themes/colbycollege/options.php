<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
    $the_theme = wp_get_theme(  get_template_directory() . '/style.css' );
    $themename = $the_theme['Title'];        
	$themename = preg_replace("/\W/", "", strtolower($themename) );	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {

	$themesPath = dirname(__FILE__) . '/admin/themes';
	
	// Insert default option
	$theList['default'] = OPTIONS_FRAMEWORK_DIRECTORY . '/themes/default-thumbnail-100x60.png';
	
	if ($handle = opendir( $themesPath )) {
	    while (false !== ($file = readdir($handle)))
	    {
	        if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'css')
	        {
	        	$name = substr($file, 0, strlen($file) - 4);
				$thumb = OPTIONS_FRAMEWORK_DIRECTORY . '/themes/' . $name . '-thumbnail-100x60.png';
				$theList[$name] = $thumb;
	        }
	    }
	    closedir($handle);
	}
	
	// fixed or scroll position
	$fixed_scroll = array("scroll" => "Scroll","fixed" => "Fixed");
	
	// Multicheck Defaults
	$multicheck_defaults = array("one" => "1","five" => "1");
	
	// Background Defaults
	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
	
	// Site type defaults...
	$sitetype_defaults = array('' => '',"administrative" => "Administrative Office","academic" => "Academic Department", "athletic" => "Athletic Team", "studentclub" => "Student Club");
	
	// Academic department codes ***TODO - tie into class that contains official list of academic codes
	$academic_defaults = array('' =>'','AD' => 'Administrative Studies','CS' => 'Computer Science');
	
	$slider_type_defaults = array('Posts','Front Page Slides','Jumbo Slideshow (Posts)');
	
	$slider_slidecount_defaults = array('1','2','3','4','5','6','7','8','9','10');
	
	// Grab all user-creatd slideshows...	
	$slideshowTerms = get_terms('slide-page');
	$slideshowGroups[] = '';
	foreach($slideshowTerms as $term){
		$slideshowGroups[] = $term->slug;
	}

	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_stylesheet_directory_uri() . '/images/';
		
	$options = array();
							
	$options[] = array( "name" => "Site Settings",
						"type" => "heading");

/*	$options[] = array( "name" => "Type of Site",
						"desc" => "",
						"id" => "site_type",
						"std" => "",
						"type" => "select",
						"class" => "tiny", //mini, tiny, small
						"options" => $sitetype_defaults);
*/
	$options[] = array( "name" => "Academic Department Name",
						"desc" => "",
						"id" => "academic_options",
						"class" => "mini hidden",
						"std" => "",
						"type" => "select",
						"options" => $academic_defaults);

	$options[] = array( "name" => "Front Page Slideshow",
						"desc" => "Show slideshow on front page of site",
						"id" => "showhidden_slideroptions",
						"std" => "0",
						"type" => "checkbox");

	$options[] = array( "name" => "Slideshow Source",
							"desc" => "Add slides through the <a href='edit.php?post_type=frontpage_slideshow'>Front Page Slides</a> section.<br /> Posts must be categorized as 'Front Page Slide' to appear in posts slideshow.",
						"id" => "slider_options_type",
						"class" => "mini hidden",
						"std" => "Front Page Slides",
						"options" => $slider_type_defaults,
						"type" => "select");	

	$options[] = array( "name" => "Select Slideshow",
						"desc" => "Select a slide group. [ <a href='edit-tags.php?taxonomy=slide-page&post_type=slide'>Add a new group</a> ]",
						"id" => "slideshow-slug",
						"class" => "mini hidden",
						"std" => "4",
						"options" => $slideshowGroups,
						"type" => "select");		
	
	$options[] = array( "name" => "Slideshow Name",
						"desc" => "",
						"id" => "slideshow-name",
						"class" => "mini hidden",
						"std" => "",
						"type" => "text");
	
	$options[] = array( "name" => "Number of slides to show.",
						"desc" => "",
						"id" => "slider_options",
						"class" => "mini hidden",
						"std" => "5",
						"options" => $slider_slidecount_defaults,
						"type" => "select");			
	
/*	$options[] = array( "name" => "Search bar",
						"desc" => "Show search bar in top nav",
						"id" => "search_bar",
						"std" => "",
						"type" => "checkbox");
*/						
	
	$options[] = array( "name" => "Share Buttons",
						"desc" => "Hide share buttons on posts",
						"id" => "show_share",
						"std" => "0",
						"type" => "checkbox");

	$options[] = array( "name" => "Post Dates",
						"desc" => "Hide dates on posts",
						"id" => "hide_dates",
						"std" => "0",
						"type" => "checkbox");

	
	$options[] = array( "name" => "Breadcrumbs",
						"desc" => "Display breadcrumbs on top of pages and posts",
						"id" => "show_breadcrumbs",
						"std" => "",
						"type" => "checkbox");
	
	$options[] = array( "name" => "Disable all comments",
						"desc" => "Suppress all comment fields",
						"id" => "suppress_comments_message",
						"std" => "1",
						"type" => "checkbox");
						
/*	$options[] = array( "name" => "Showcase Text",
						"desc" => "Display front page with showcase text style",
						"id" => "blog_hero",
						"std" => "",
						"type" => "checkbox");
*/						
	
	$options[] = array( "name" => "Contact Info",
					"type" => "heading");
												
	$options[] = array( "name" => "Mailbox Number",
						"desc" => "Address for contact block (defaults to 4000).",
						"id" => "contact_mailbox",
						"std" => "",
						"class" => "mini",
						"type" => "text");	
						
	$options[] = array( "name" => "Contact E-mail",
						"desc" => "E-mail address to use for contact block.",
						"id" => "contact_email",
						"std" => "",
						"class" => "small",
						"type" => "text");	
						
	$options[] = array( "name" => "Building/Floor",
						"desc" => "Physical building location and floor (optional)",
						"id" => "contact_floor",
						"std" => "",
						"class" => "small",
						"type" => "text");									
							
	$options[] = array( "name" => "Phone Extension",
						"desc" => "4-digit phone extension",
						"id" => "contact_phone_extension",
						"std" => "",
						"class" => "mini",
						"type" => "text");
						
	$options[] = array( "name" => "Fax Extension",
						"desc" => "4-digit fax extension",
						"id" => "contact_fax_extension",
						"std" => "",
						"class" => "mini",
						"type" => "text");
	$options[] = array( "name" => "Office Hours",
						"desc" => "",
						"id" => "contact_office_hours",
						"std" => "",
						"class" => "mini",
						"type" => "text");						
						
/*	$options[] = array(
		'name' => __('Social', 'options_check'),
		'type' => 'heading');
	$options[] = array( "name" => "Facebook Handle",
						"desc" => "",
						"id" => "facebook_handle",
						"std" => "",
						"class" => "mini",
						"type" => "text");
						
	$options[] = array( "name" => "Twitter Handle",
						"desc" => "",
						"id" => "twitter_handle",
						"std" => "",
						"class" => "mini",
						"type" => "text");			
*/				
	$options[] = array( "name" => "Other Settings",
						"type" => "heading");

	$options[] = array( "name" => "CSS",
						"desc" => "Additional CSS",
						"id" => "wpbs_css",
						"std" => "",
						"type" => "textarea");
	
	$options[] = array( "name" => "Navigation Menu Source Site",
						"desc" => "If you wish to pull in the navigation menu of another site, enter its ID (eg. news)",
						"id" => "menuoverride_site",
						"std" => "",
						"class" => "mini",
						"type" => "text");
						
	$options[] = array( "name" => "Analytics Secondary ID",
						"desc" => "Enter the unique Google Analytics ID (eg. UA-XXXXXX-AA)",
						"id" => "analytics_secondary",
						"std" => "",
						"class" => "mini",
						"type" => "text");
															
	return $options;
}

add_action('admin_head', 'wpbs_javascript');

function wpbs_javascript() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {

	var data = {
		action: 'wpbs_theme_check',
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery('#check-bootswatch').click( function(){ 
		jQuery.post(ajaxurl, data, function(response) {
			alert(response);
		});
	});
});
</script>
<?php
}
?>