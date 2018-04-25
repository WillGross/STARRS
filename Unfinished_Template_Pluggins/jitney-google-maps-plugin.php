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
	global $wpdb;
	$vehicleLocations = $wpdb -> get_results('SELECT vehicle_ID, latitude, longitude from location');
	$vehicleStatus = $wpdb -> get_results('SELECT id, isDriving from vehicles');
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
	var markers = new Array();
	var ids = new Array(<?php echo $vehicleStatus['id'] ?>);
	var center = { lat: <?php echo $args['lat'] ?>, lng: <?php echo $args['lng'] ?> }
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			center: center,
			zoom: <?php echo $args['zoom'] ?>
		});
	}
	function initMarkers(){
		var i = 0;
		var drivingArray = new Array(<?php echo $vehicleStatus['isDriving'] ?>);
		for (i = 0; i < ids.length; i++):
			if drivingArray[i] == 'yes':
				marker = new google.maps.Marker({
					position: jitneyLocation,
					map: map
    				});
				markers.push(marker);
	}
	
	var cycle = setInterval(updateMarker, 5000);
	//placeholder for actual latitude and longitude from php script
	var latitude = <?php echo $vehicleLocations['latitude'] ?>;
	var longitude = <?php echo $vehicleLocations['longitude'] ?>;
	function updateMarkers(){
		var i;
		for (i = 0; i < markers.length; i++):
			marker = markers[i];
			marker.setMap(null);
			marker.setPosition({ lat: latitude, lng: longitude });
			marker.setMap(map);
	}
	</script>

	<?php
	$output = ob_get_clean();
	return $output;
}

//activates the google map api when wordpress loads header
add_action( 'wp_head', 'jitney_enqueue_assets' );
function jitney_enqueue_assets() {
	wp_enqueue_script( 
	  'google-maps', 
	  '//maps.googleapis.com/maps/api/js?key=AIzaSyD7EpcuhwegwyYstrosisrWSMCu8vqiKCE&callback=initMap', 
	  array(), 
	  '1.0', 
	  true 
	);
}
