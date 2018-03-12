<?php

/*
	Front Page Slide Fields
	Author: bgreeley@colby.edu
	
	Creates custom post type of 'Front Page Slides' and adds the fields to be associated with slides.
	Front-end rendering is based off of Wooslider plugin.
*/
// Create custom post type....

if( is_admin() ) {

	add_action( 'init', 'create_slideshow_fields' );
	function create_slideshow_fields() {
		$term = term_exists('Frontpage Slide', 'category');
		
		if(!($term !== 0 && $term !== null)){
			// Create the category
			$category_id = wp_insert_term(
				'Frontpage Slide',
				'category',
				array(
				  'description'	=> 'Frontpage Slide, used for slideshows.',
				  'slug' 		=> 'frontpage-slide'
				));
		}
		
		$term = term_exists('News', 'category');
		$cnterm = term_exists('Colby News', 'category');
		
		if(!($term !== 0 && $term !== null) && !($cnterm !== 0 && $cnterm !== null) && get_bloginfo('name') != 'Athletics'){
			// Create the category
			$category_id = wp_insert_term(
				'News',
				'category',
				array(
				  'description'	=> 'News',
				  'slug' 		=> 'news'
				));
		}
	}
}

	
if( function_exists('register_field_group') ):

register_field_group(array (
	'key' => 'group_552d23a29e0b9',
	'title' => 'Front Page Slides',
	'fields' => array (
		array (
			'key' => 'field_552d2415063e8',
			'label' => 'Link URL',
			'name' => 'link_url',
			'prefix' => '',
			'type' => 'text',
			'instructions' => 'URL to link slide to',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_552d2459063ea',
			'label' => 'More Text',
			'name' => 'more_text',
			'prefix' => '',
			'type' => 'text',
			'instructions' => 'Only use this field if you wish to replace the "Read more" text',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_552d24ae063ec',
			'label' => 'Slide Type',
			'name' => 'slide_type',
			'prefix' => '',
			'type' => 'radio',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'image' => 'Image (featured image)',
				'video' => 'Background Video',
			),
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => '',
			'layout' => 'vertical',
		),
		array (
			'key' => 'field_552d252f93d24',
			'label' => 'Header Video (MP4)',
			'name' => 'header_video_mp4',
			'prefix' => '',
			'type' => 'file',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_552d24ae063ec',
						'operator' => '==',
						'value' => 'video',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array (
			'key' => 'field_552d259393d25',
			'label' => 'Header Video (WebM)',
			'name' => 'header_video_webm',
			'prefix' => '',
			'type' => 'file',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_552d24ae063ec',
						'operator' => '==',
						'value' => 'video',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array (
			'key' => 'field_552d25a493d26',
			'label' => 'Header Video (OGV)',
			'name' => 'header_video_ogv',
			'prefix' => '',
			'type' => 'file',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_552d24ae063ec',
						'operator' => '==',
						'value' => 'video',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
			array (
				'param' => 'post_category',
				'operator' => '==',
				'value' => 'category:frontpage-slide',
			),
		),
	),
	'menu_order' => 1,
	'position' => 'acf_after_title',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'excerpt',
		1 => 'discussion',
		2 => 'comments',
		3 => 'author',
		4 => 'format',
		5 => 'send-trackbacks',
	),
));

endif;
?>
