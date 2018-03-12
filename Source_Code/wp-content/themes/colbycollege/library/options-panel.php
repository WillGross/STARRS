<?php

if ( !function_exists( 'optionsframework_init' ) ) {

/*-----------------------------------------------------------------------------------*/
/* Options Framework Theme
/*-----------------------------------------------------------------------------------*/

/* Set the file path based on whether the Options Framework Theme is a parent theme or child theme */

if ( get_stylesheet_directory() == get_template_directory_uri() ) {
	define('OPTIONS_FRAMEWORK_URL', get_template_directory()  . '/admin/');
	define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/');
} else {
	define('OPTIONS_FRAMEWORK_URL', get_stylesheet_directory() . '/admin/');
	define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/');
}

require_once (get_template_directory() . '/admin/options-framework.php');

}

// *** ADD CUSTOM OPTIONS TO OPTIONS PANEL...
add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#showhidden_gradient').click(function() {
  		jQuery('#section-top_nav_bottom_gradient_color').fadeToggle(400);
	});

	if (jQuery('#showhidden_gradient:checked').val() !== undefined) {
		jQuery('#section-top_nav_bottom_gradient_color').show();
	}

	jQuery('#showhidden_themes').click(function() {
			jQuery('#section-wpbs_theme').fadeToggle(400);
	});

	if (jQuery('#showhidden_themes:checked').val() !== undefined) {
		jQuery('#section-wpbs_theme').show();
	}

	jQuery('#showhidden_slideroptions').click(function() {
		if(jQuery(this).attr("checked")=="checked"){
			// Show the slider options, if necessary...
			jQuery('#section-slider_options_type').show();
			if(jQuery("#slider_options_type").val()=='Slideshow'){
				jQuery('#section-slideshow-slug').show();
				jQuery('#section-slider_options').hide();
			}

			if(jQuery("#slider_options_type").val()=='Posts' || jQuery("#slider_options_type").val()==0){
				jQuery('#section-slider_options').show();
				jQuery('#section-slideshow-slug').hide();
			}
		}
		else{
			jQuery('#section-slider_options_type').hide();
			jQuery('#section-slider_options').hide();
			jQuery('#section-slideshow-slug').hide();
		}
	});

	jQuery("#slideshow-slug").change(function(){
		jQuery("#slideshow-name").val(jQuery("#slideshow-slug option:selected").text());
	});

	jQuery("#slider_options_type").change(function(){
		showHideSlideshowOptions();
	})

	if (jQuery('#showhidden_slideroptions:checked').val() !== undefined) {

		jQuery('#section-slider_options_type').show();
		showHideSlideshowOptions();

	}

	// Academic options show/hide...
	jQuery('#site_type').change(function() {
		if(jQuery(this).val() == 'academic')
			jQuery('#section-academic_options').fadeIn(400);
		else{
			jQuery('#academic_options').val('');
			jQuery('#section-academic_options').fadeOut(400);
		}
	});

	if (jQuery('#site_type').val() == 'academic') {
		jQuery('#section-academic_options').show();
	}

});

function showHideSlideshowOptions(){
	if(true){
			// Show any post-type options...
			jQuery('#section-slider_options').show();
			jQuery('#section-slideshow-slug').hide();
		}

}

</script>

<?php
}
