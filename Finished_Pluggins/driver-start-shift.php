<?php
/*
Plugin Name: STARRS driver start shift plygin
Description: A plugin where drivers will fill information at the beginning of a shift before they start driving
Version: 1.0
Author: Paige Hanssen
*/

// create form that drivers will fill out
function displayed_html() {
    $displayed_html = '<form method="post"><div>
                       <label for="vehicle">Vehicles not in use:</label><select name="vehicle">';

    // get unused vehicles from the database
    global $wpdb;    
    $vehicles = $wpdb->get_results('SELECT id, name, plateNum FROM vehicles WHERE isDriving="no"');
    
    // put vehicles into options for the select dropdown
	foreach ($vehicles as $car){
        $displayed_html.=	"<option value='$car->id'>" . $car->name . " " . $car->plateNum . "</option>";
	}

    // fields for mileage and submit button
    $displayed_html .= '</select></div>
                        <div><label for="mileage">Starting mileage:</label>
                        <input type="textarea" name="mileage" value=""></div>
                        <div><input type="submit" name="start" value="Start"/></form>';
    print_r($displayed_html);   // display form
}

function start_driving() {
    global $wpdb, $vehicleid, $mileage;

    if(empty($mileage)) {   // check to make sure mileage has be inputted
        print_r('*Mileage is a required field.');
    } else {
        $data = array(
            'vehicle_ID' => $vehicleid,
            'date' => current_time('Y-m-d'),
            'startTime' => current_time('mysql'),
            'endTime' => current_time('mysql')
        );
        $wpdb->update('shifts', $data, array('driver_ID' => '1'));  // update shifts table
        $wpdb->update('vehicles', array('isDriving' => 'yes', 'mileage' => $mileage), array('id' => $vehicleid));   // update vehicles table
        print_r('<p>Data submitted! <a href="https://starrs.colby.edu/STARRS-Home/">Start your shift.</a></p>');    // redirect link to start shift
    }
}

// initializing function
function driver_start_shift() {
    if(isset($_POST['start'])) {    // checking if form has been submitted
        global $vehicleid, $mileage;
        $vehicleid = $_POST['vehicle'];
        $mileage = $_POST['mileage'];
        start_driving();            // call function to input data into database
    }
    displayed_html();   // display form function
}

// shortcode function call is: [driver_start_shift]
add_shortcode('driver_start_shift', 'driver_start_shift');
?>