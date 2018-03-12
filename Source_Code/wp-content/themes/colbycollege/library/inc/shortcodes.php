<?php
// Colby theme-specific shortcodes...

// loadcustomcf	: Loads a file from colby.edu via cURL, passing POST/GET parameters and rendering on the page.
// [loadcustomcf file="file-value" view="curl,iframe"]
function loadcustomcf_func( $atts ) {
	
	extract( shortcode_atts( array(
		'file' => '',				// passed URL
		'view' => 'curl',			// curl,iFrame
		'attributes' => ''			// Comma-delimited list of attributes that are added via GET to request
	), $atts ) );
	
	if(!strlen($file)) {
		return false;
	}
	
	$return = '';
	ob_start();

	if($attributes != ''){
		$attributesarray = explode(',',$attributes);
		
		// Build URL string based on passed attributes...
		foreach($attributesarray as $attribute){
			if(strpos($file,"?")===false)
				$file .= "?";
			else
				$file .= "&";
				
			$file .= $attribute;
		}

	}
	switch($view){
		case 'curl':
			if(isset($_POST) && count($_POST)){			// Form submitted...pass values to CURL in case this script needs them.
				$output = post_to_url($file,$_POST);
				echo $output;
			}
			else{
				$queryString = http_build_query($_GET, '', '|');
				
				if(strlen($queryString)) {
					$prependChar = '?';

					if(strpos($file,'?') !== false) {
						$prependChar = '&';
					}
					
					$file .= $prependChar.$queryString;
				}
				$ch = curl_init($file);
				$output = curl_exec($ch);
			}
			
			break;
		
		case 'iframe':
			echo '<iframe style="border: none;" src="'.$file.'" height="1800" width="100%"></iframe>';
			break;
		
	}
	
	$return = ob_get_contents();
	ob_end_clean();
	return $return;
}
add_shortcode( 'loadcustomcf', 'loadcustomcf_func' );

function post_to_url($url, $data) {
   $fields = '';
   foreach($data as $key => $value) {
	   if(is_array($value)) {
		   /*
		   echo $key;
		   print_r($value);
		   echo count($value);
		   echo '<br />';
			*/
		   foreach($value as $value2) {
			   $fields .= $key . '[]=' . $value2 . '&'; 	
			}
	   }
	   else {
 			$fields .= $key . '=' . $value . '&'; 	   
	   }
      
   }
   rtrim($fields, '&');
   $post = curl_init();

   curl_setopt($post, CURLOPT_URL, $url);
   curl_setopt($post, CURLOPT_POST, count($data));
   curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
   curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

   $result = curl_exec($post);

   curl_close($post);
   return $result;
}


// Buttons
function buttons( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'type' => 'default', /* primary, default, info, success, danger, warning, inverse */
	'size' => 'default', /* mini, small, default, large */
	'url'  => '',
	'text' => '', 
	), $atts ) );
	
	if($type == "default"){
		$type = "";
	}
	else{ 
		$type = "btn-" . $type;
	}
	
	if($size == "default"){
		$size = "";
	}
	else{
		$size = "btn-" . $size;
	}
	
	$output = '<a href="' . $url . '" class="btn '. $type . ' ' . $size . '">';
	$output .= $text;
	$output .= '</a>';
	
	return $output;
}

add_shortcode('button', 'buttons'); 

// Alerts
function alerts( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'type' => 'alert-info', /* alert-info, alert-success, alert-error */
	'close' => 'false', /* display close link */
	'text' => '', 
	), $atts ) );
	
	$output = '<div class="fade in alert alert-'. $type . '">';
	if($close == 'true') {
		$output .= '<a class="close" data-dismiss="alert">x</a>';
	}
	$output .= $text . '</div>';
	
	return $output;
}

add_shortcode('alert', 'alerts');

// Block Messages
function block_messages( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'type' => 'alert-info', /* alert-info, alert-success, alert-error */
	'close' => 'false', /* display close link */
	'text' => '', 
	), $atts ) );
	
	$output = '<div class="fade in alert alert-block alert-'. $type . '">';
	if($close == 'true') {
		$output .= '<a class="close" data-dismiss="alert">x</a>';
	}
	$output .= '<p>' . $text . '</p></div>';
	
	return $output;
}

add_shortcode('block-message', 'block_messages'); 

// Block Messages
function blockquotes( $atts, $content = null ) {
	extract( shortcode_atts( array(
	'float' => '', /* left, right */
	'cite' => '', /* text for cite */
	), $atts ) );
	
	$output = '<blockquote';
	if($float == 'left') {
		$output .= ' class="pull-left"';
	}
	elseif($float == 'right'){
		$output .= ' class="pull-right"';
	}
	$output .= '><p>' . $content . '</p>';
	
	if($cite){
		$output .= '<small>' . $cite . '</small>';
	}
	
	$output .= '</blockquote>';
	
	return $output;
}

add_shortcode('blockquote', 'blockquotes'); 

add_shortcode('posts-in-category', function($atts) {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $category = $atts['category'];
    $posts_per_page = $atts['posts_per_page'];
    $plural_name = $atts['next_prev_name'];

    if (!$plural_name) {
      $plural_name = 'Entries';
    }

    $query_args = [
        'category_name' => $category,
        'posts_per_page' => $posts_per_page,
        ];

    $orig_query = $wp_query;

    $wp_query = new WP_Query($query_args);

    if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {
            $wp_query->the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
  
	  <header>
	    
	    <div class="page-header"><h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3></div>
	    <p class="meta">
    	    <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate="pubdate"><?php the_date(); ?></time>
        </p>
	  
	  </header> 

	  <section class="post_content clearfix">
	    <?php the_content( __("Read more &raquo;","bonestheme") ); ?>
	  </section> 
	</article>
	<hr /><?php

        }

      $prev_link = get_next_posts_link('Older ' . $plural_name, $wp_query->max_num_pages);
      $next_link = get_previous_posts_link( 'Newer ' . $plural_name  );
    ?>
    <nav class="wp-prev-next">
      <div class="clearfix"><?php
      if ($prev_link) echo '<span class="prev-link" style="float: right;">' . $prev_link . '</span>';
      if ($next_link) echo '<span class="next-link" style="float: left;">' . $next_link . '</span>'; ?>
      </div>
    </nav><?php

    wp_reset_postdata();
  }
  $wp_query = $orig_query;

});

add_shortcode('list-posts-in-category', function($atts) {

    $display_post_id = $atts['display_post_id'];
    if (!$display_post_id or $display_post_id != get_the_id()) {
        return;
    }

    $category = $atts['category'];
    if (!$category) return;

    $widgettitle = $atts['widgettitle'];

    if (!$widgettitle) {
    	$widgettitle = '';
    }

    $posts_per_page = $atts['number_of_posts'] ? $atts['number_of_posts'] : -1;

    $query_args = [
        'category_name' => $category,
        'posts_per_page' => $posts_per_page,
        ];

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        $output = '<h4 class="widgettitle">' . $widgettitle . '</h4>';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<h5 style="font-weight: 500;"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h5>';
            $output .= '<time class="meta" datetime=' . get_the_time('Y-m-j') . ' pubdate="pubdate">' .  get_the_date() . '</time>';

        }
    }
    return $output;

});
