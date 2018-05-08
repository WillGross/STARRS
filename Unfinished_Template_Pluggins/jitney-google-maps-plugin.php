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
	var shuttleMarker;
	var jitneyLocation = { lat: <?php echo $args['lat'] ?>, lng: <?php echo $args['lng'] ?> }
	
	// Locations of the four stop offs of the Shuttle
	var mainStLatLng = {lat: 44.550999, lng: -69.632022};
	var gilmanStLatLng = {lat: 44.553614, lng: -69.637182};
	var diamondLatLng = {lat: 44.562241, lng: -69.659671};
	var davisLatLng = {lat: 44.564428, lng: -69.658850};
	
	// Locations of critical vertices on the path from Main St. to Gilman St & back.
	var mgLatLng_001 = {lat: 44.551679, lng: -69.635225};
	var mgLatLng_002 = {lat: 44.553167, lng: -69.634346};

	// Locations of critical vertices on the path from Gilman St. to Diamond & back.
	var gdLatLng_001 = {lat: 44.556345, lng: -69.654988};
	var gdLatLng_002 = {lat: 44.557197, lng: -69.657624};
	var gdLatLng_003 = {lat: 44.557434, lng: -69.658141};
	var gdLatLng_004 = {lat: 44.557957, lng: -69.658622};
	var gdLatLng_005 = {lat: 44.561336, lng: -69.660115};
	var gdLatLng_006 = {lat: 44.561908, lng: -69.660352};
	var gdLatLng_007 = {lat: 44.561983, lng: -69.659704};
	var gdLatLng_008 = {lat: 44.562064, lng: -69.659634};

	// Locations of critical vertices on the path from Diamond to Davis & back.
	var ddLatLng_001 = {lat: 44.562267, lng: -69.659589};
	var ddLatLng_002 = {lat: 44.562118, lng: -69.659593};
	var ddLatLng_003 = {lat: 44.561975, lng: -69.659549};
	var ddLatLng_004 = {lat: 44.561865, lng: -69.660306};
	var ddLatLng_005 = {lat: 44.562531, lng: -69.660461};
	var ddLatLng_006 = {lat: 44.562982, lng: -69.660462};
	var ddLatLng_007 = {lat: 44.563372, lng: -69.660422};
	var ddLatLng_008 = {lat: 44.564047, lng: -69.660254};
	var ddLatLng_009 = {lat: 44.564460, lng: -69.660065};
	var ddLatLng_010 = {lat: 44.565225, lng: -69.659534};
	var ddLatLng_011 = {lat: 44.564955, lng: -69.659012};
	var ddLatLng_012 = {lat: 44.564701, lng: -69.658627};
	var ddLatLng_013 = {lat: 44.564518, lng: -69.658531};

	// Locations of critical vertices on the path from Diamond to Gilman St & back.
	var dgLatLng_001 = {lat: 44.562064, lng: -69.659634};
	var dgLatLng_002 = {lat: 44.561983, lng: -69.659704};
	var dgLatLng_003 = {lat: 44.561908, lng: -69.660352};
	var dgLatLng_004 = {lat: 44.561336, lng: -69.660115};
	var dgLatLng_005 = {lat: 44.557957, lng: -69.658622};
	var dgLatLng_006 = {lat: 44.557434, lng: -69.658141};
	var dgLatLng_007 = {lat: 44.557197, lng: -69.657624};
	var dgLatLng_008 = {lat: 44.556345, lng: -69.654988};

	// A list of the locations above, arranged in order so that the map can draw the route.
	var shuttleCoords = [
		mainStLatLng,
		mgLatLng_001,
		mgLatLng_002,
		gilmanStLatLng,
		gdLatLng_001,
		gdLatLng_002,
		gdLatLng_003,
		gdLatLng_004,
		gdLatLng_005,
		gdLatLng_006,
		gdLatLng_007,
		gdLatLng_008,
		diamondLatLng,            
		ddLatLng_001,
		ddLatLng_002,
		ddLatLng_003,
		ddLatLng_004,
		ddLatLng_005,
		ddLatLng_006,
		ddLatLng_007,
		ddLatLng_008,
		ddLatLng_009,
		ddLatLng_010,
		ddLatLng_011,
		ddLatLng_012,
		ddLatLng_013,
		davisLatLng,
		ddLatLng_013,
		ddLatLng_012,
		ddLatLng_011,
		ddLatLng_010,
		ddLatLng_009,
		ddLatLng_008,
		ddLatLng_007,
		ddLatLng_006,
		ddLatLng_005,
		dgLatLng_003,
		dgLatLng_004,
		dgLatLng_005,
		dgLatLng_006,
		dgLatLng_007,
		dgLatLng_008,
		gilmanStLatLng,
		mgLatLng_002,
		mgLatLng_001,
		mainStLatLng
	];
	
	//initializes the map, the test marker, the shuttle path, and the drop-off location markers
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			center: jitneyLocation,
			zoom: <?php echo $args['zoom'] ?>
		});
		
		//creates a test marker for the shuttle
		shuttleMarker = new google.maps.Marker({
			position: mainStLatLng,
			label: {
				text: 'T',
				color: '#0000FF',
				fontWeight: 'bold'
			},
			map: map
		});
		
		// outlines the path of the shuttle given in shuttleCoords
		var shuttlePath = new google.maps.Polyline({
			path: shuttleCoords,
			geodesic: true,
			strokeColor: '#00FF00',
			strokeOpacity: 0.6,
			strokeWeight: 5
		});
		
		shuttlePath.setMap(map);

		var markerImageAnchorPt = new google.maps.Point(10, 10);

		var markerIcon = {
			url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
			labelOrigin: markerImageAnchorPt
		};
		
		//set up markers for each drop off point
		var mainStMarker = new google.maps.Marker({
			position: mainStLatLng,
			map: map,
			icon: markerIcon,
			label: {
				text: 'M',
				color: '#0000FF',
				fontWeight: 'bold'
			}
		});

		var gilmanStMarker = new google.maps.Marker({
			position: gilmanStLatLng,
			map: map,
			icon: markerIcon,
			label: {
				text: 'G',
				color: '#0000FF',
				fontWeight: 'bold'
			}
		});

		var diamondStMarker = new google.maps.Marker({
			position: diamondLatLng,
			map: map,
			icon: markerIcon,
			label: {
				text: 'Di',
				color: '#0000FF',
				fontWeight: 'bold'
			}
		});

		var davisStMarker = new google.maps.Marker({
			position: davisLatLng,
			map: map,
			icon: markerIcon,
			label: {
				text: 'Da',
				color: '#0000FF',
				fontWeight: 'bold'
			}
		});
	}
	//cycle through shuttleCoords
	var cycle = setInterval(updateMarker, 5000);
	var arrayLength = shuttleCoords.length;
	var i = 0;
	function updateMarker(){
		shuttleMarker.setMap(null);
		//below is just for testing updating
		latLng = shuttleCoords[i];
		//set position should use latitude and longitude from logLocation
		shuttleMarker.setPosition(latLng);
		shuttleMarker.setMap(map);
		i = i + 1;
		if (i == arrayLength){
			i = 0;
		}
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