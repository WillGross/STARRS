<?php
/**
 * Plugin Name: STARRS Map
 * Description: A plugin displays a map tracking the movement of the jitney and shuttle
 * Version: 1.5
 * Author: Ryan Sellar
 */

//shortcode for google map in post:
//[map lat='<latitude>' lng='longitude' zoom='<zoom>' height='<height>']
add_shortcode( 'map', 'jitney_map' );
function jitney_map( $args ) {
	$args = shortcode_atts( array(
		'lat'    => '44.556',
		'lng'    => '-69.646',
		'zoom'   => '14',
		'height' => '300px'
	), $args, 'map' );

	ob_start();
	?>
	<div class='map' style='height:<?php echo $args['height'] ?>; margin-bottom: 1.6842em' id='map'></div> 

	<script type='text/javascript'>
	var map;
	var marker;
	var jitneyLocation = { lat: <?php echo $args['lat'] ?>, lng: <?php echo $args['lng'] ?> }
	function initMap() {
	  map = new google.maps.Map(document.getElementById('map'), {
	    center: jitneyLocation,
	    zoom: <?php echo $args['zoom'] ?>
	  });
	
	  marker = new google.maps.Marker({
        position: jitneyLocation,
        map: map
    });
	}
	
	var cycle = setInterval(updateMarker, 5000);
	//placeholder for actual latitude and longitude from php script
	var latitude = <?php echo $args['lat'] ?>;
	var longitude = <?php echo $args['lng'] ?>;
	function updateMarker(){
		marker.setMap(null);
		//below is just for testing updating
		latitude = latitude + 0.001;
		longitude = longitude + 0.001;
		//set position should use latitude and longitude from logLocation
		marker.setPosition({ lat: latitude, lng: longitude });
		marker.setMap(map);
	}
	</script>

	<?php
	$output = ob_get_clean();
	return $output;
}

//activates the google map api when wordpress loads header
add_action( 'wp_head', 'mgms_enqueue_assets' );
function mgms_enqueue_assets() {
	wp_enqueue_script( 
	  'google-maps', 
	  '//maps.googleapis.com/maps/api/js?key=AIzaSyD7EpcuhwegwyYstrosisrWSMCu8vqiKCE&callback=initMap', 
	  array(), 
	  '1.0', 
	  true 
	);
}
