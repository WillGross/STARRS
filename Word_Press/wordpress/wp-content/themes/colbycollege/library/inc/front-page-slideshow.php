<?php

/*
	Front Page Slideshows...
	Author: bgreeley@colby.edu

	Creates custom post type of 'Front Page Slides' and adds the fields to be associated with slides.
	Front-end rendering is based off of Wooslider plugin.
*/

// Create custom post type....
add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'frontpage_slideshow',
		array(
			'labels' => array(
				'name' => __( 'Front Page Slides', 'text_domain'  ),
				'singular_name' => __( 'Front Page Slideshow', 'text_domain'  )
			),
		'public' => true,
		'has_archive' => false,
		'exclude_from_search' => true,
		'capabilities' => array(
		    'edit_post'          => 'remove_users',
		    'read_post'          => 'remove_users',
		    'delete_post'        => 'remove_users',
		    'edit_posts'         => 'remove_users',
		    'edit_others_posts'  => 'remove_users',
		    'publish_posts'      => 'remove_users',
		    'read_private_posts' => 'remove_users'
		)
		)
	);
}

/**
 *  Install Add-ons
 *
 *  The following code will include all 4 premium Add-Ons in your theme.
 *  Please do not attempt to include a file which does not exist. This will produce an error.
 *
 *  The following code assumes you have a folder 'add-ons' inside your theme.
 *
 *  IMPORTANT
 *  Add-ons may be included in a premium theme/plugin as outlined in the terms and conditions.
 *  For more information, please read:
 *  - http://www.advancedcustomfields.com/terms-conditions/
 *  - http://www.advancedcustomfields.com/resources/getting-started/including-lite-mode-in-a-plugin-theme/
 */

// Add-ons
// include_once('add-ons/acf-repeater/acf-repeater.php');
// include_once('add-ons/acf-gallery/acf-gallery.php');
// include_once('add-ons/acf-flexible-content/acf-flexible-content.php');
// include_once( 'add-ons/acf-options-page/acf-options-page.php' );


/**
 *  Register Field Groups
 *
 *  The register_field_group function accepts 1 array which holds the relevant data to register a field group
 *  You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_slideshow-front-page',
		'title' => 'Slideshow (Front Page)',
		'fields' => array (
			array (
				'key' => 'field_520ae1a00b36e',
				'label' => 'Slide Caption',
				'name' => 'slideshow_caption',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_520ae1490b36c',
				'label' => 'Slide Image',
				'name' => 'slideshow_image',
				'type' => 'image',
				'required' => 1,
				'instructions' => 'Crop image to 520px x 347px',
				'save_format' => 'object',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_520ae16f0b36d',
				'label' => 'Slide Link',
				'name' => 'slideshow_link',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_520ae3d4a429e',
				'label' => 'Slide Order',
				'name' => 'slide_order',
				'type' => 'number',
				'instructions' => 'Order for slide to appear',
				'default_value' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'max' => 4,
				'step' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'frontpage_slideshow',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'the_content',
				1 => 'excerpt',
				2 => 'custom_fields',
				3 => 'discussion',
				4 => 'comments',
				5 => 'revisions',
				6 => 'slug',
				7 => 'author',
				8 => 'format',
				9 => 'featured_image',
				10 => 'categories',
				11 => 'tags',
				12 => 'send-trackbacks',
			),
		),
		'menu_order' => 0,
	));
}
// Render slideshow on front page based on 'Front page slide' custom fiely type...
function render_frontpage_slideshow($atts){
	extract( shortcode_atts( array(
		'id' => 'wooslider-id-1', // slider id
		'size' => 'square',		// square, rectangle...
		'count' => '',
		'nav' => 'false',
		'cat' => 'frontpage-slide'
	), $atts ) );

	$return = '';
	ob_start();

	$slideimagesize = 'slideshow-rectangle';
	if($size=='square') {
		$slideimagesize = 'slideshow-rectangle';
	}
	elseif($size=='rectangle') {
		$slideimagesize = 'wpbs-featured';
	}
	else {
		$slideimagesize = $size;
	}

	$slideCount = 3;

	$slideType = of_get_option('slider_options_type');

	if(of_get_option('slider_options') != "")
		$slideCount = (of_get_option('slider_options') + 1);

	if(strlen($count))
		$slideCount = $count;


	if(  $cat != 'frontpage-slide' ) {
		$slideType = '0';
	}

	if($slideType == '0' || $cat != 'frontpage-slide'){

		$args = array( 'post_type' => 'post', 'posts_per_page' => $slideCount,'orderby' => 'date','order' => 'DESC' );

		$catobj = get_category_by_slug($cat);

		if(isset($catobj))
			$args = $args = array( 'post_type' => 'post', 'posts_per_page' => $slideCount,'cat'=>$catobj->term_id,'orderby' => 'date','order' => 'DESC' );
		else
			$args = array( 'posts_per_page' => $show_posts );


	}
	else {
		if($slideType == '1') {
			$args = array( 'post_type' => 'frontpage_slideshow', 'posts_per_page' => $slideCount,'orderby' => 'meta_value_num','meta_key' => 'slide_order', 'order' => 'asc' );
		}
	}

	$loop = new WP_Query( $args );
	$videoSet = false;

	if($loop->post_count > 0){
		echo '<div id="'.$id.'" class="wooslider '.$id.' wooslider-type-slides wooslider-theme-default "><div class="loading"></div><ul class="slides">';
		while ( $loop->have_posts() ) : $loop->the_post();
			$embedcode = '';

			if($slideType=='1'){
				$slideImage = get_field('slideshow_image');
				$slideURL = get_field('slideshow_link');
				$caption = cutatlocation(get_field('slideshow_caption'),310);
			}

			if($slideType == '0'){
				$slideImage['id'] = get_post_thumbnail_id();
				$slideURL = get_field('link_url');

				if( !strlen($slideURL)) {
					$slideURL = get_the_permalink();
				}

				if(!strlen($slideURL))
					$slideURL = '#';

				if(strpos($slideURL,'://')===false && $slideURL != '#')
					$slideURL = '//'.trim($slideURL);

				$caption = the_excerpt_max_charlength(240);
			}

			if(strlen(trim($caption)) && $size == 'rectangle')
				$caption = ' - '.$caption;

			if(true){

				if(strpos($slideURL,'youtube.com')!==false || strpos($slideURL,'vimeo.com') !== false){
					$videoSet = true;
					$embedcode =  wp_oembed_get($slideURL,array('width' => 600,'autoplay' =>1));
					$embedcode = str_replace('http://', '//', $embedcode);
				}

				?>
				<li class="slide<?php echo strlen($embedcode)?' videoEmbed':'';?>">
					<div class="slide-content">
					<?php if(strlen(trim($slideURL)) && !$embedcode){
					echo '<a href="'.$slideURL.'">';
				}?>
				<?php
				$slideSize = 'orig';

				if($slideImage['width'] > 600 || $slideImage['height'] > 400){
					$slideSize = array(600,400);
				}

				if(!$embedcode) {
					$src = wp_get_attachment_image_src( $slideImage['id'], $slideimagesize );
					$src = str_replace('http://','//',$src[0]);

					echo '<img src="'.$src.'" alt="'.$slideTitle.'" />'; //$slideSize
				}
				else
					echo $embedcode;

				$slideTitle = cutatlocation(get_the_title(),53);

				?>
				<div class="slide-text">
					<h3 class="slide-title"><?php echo $slideTitle;?></h3>
					<p class="slide-caption"><?php echo $caption;?></p>
				</div>
				<div class="slide-text-arrow"></div>

				<?php
				if(strlen(trim($slideURL)) && !$embedcode)
					echo '</a>';
				echo '</div></li>';
			}
		endwhile;
		echo "</ul></div>";
		?>
		<script>
		jQuery(window).load(function() {

			jQuery( '#<?php echo $id;?>' )
				.flexslider2({namespace: "wooslider-",
					 animation: 'fade',
					 slideshowSpeed: 9500,
					 animationSpeed: 800,
					 <?php
					 if(!$videoSet)
						 echo 'slideshow: true,';
				     else
				     	echo 'slideshow: false,';?>
					 directionNav: <?php echo $nav;?>,
					 prevText: "<",
					 nextText: ">",
					 keyboard: true,
					 pausePlay: false,
					 randomize: false,
					 animationLoop: true,
					 pauseOnAction: true,
					 pauseOnHover: false,
					 smoothHeight: true,
					 touch: true,
					 controlNav: true,
					 start: function(slider) {
			               jQuery( '#<?php echo $id;?> .loading' ).remove();
			               jQuery( '#<?php echo $id;?> img' ).fadeIn();
			           }});
			});
		</script>
		<?php
	}

	$return = ob_get_contents();
	ob_end_clean();
	return $return;
}

add_shortcode('frontpage-slideshow','render_frontpage_slideshow');

function cutatlocation($string,$location){
	return (strlen($string) > $location) ? substr($string, 0, $location) . '...' : $string;
}
