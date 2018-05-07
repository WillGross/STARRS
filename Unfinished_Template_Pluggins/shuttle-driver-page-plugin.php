<?php
/*
Plugin Name: STARRS shuttle driver page plugin
Description: A plugin for the driver to fill out info before they drive
Version: 1.0
Author: Paige Hanssen
*/

$GLOBALS['count'];
function rider_info() {
    $textDisplayed = '<form method="post">';
    //text area to display how many riders are currently on
    $numRiders = '<div><h1>Passengers currently riding: <div id="num_people">' . (isset($GLOBALS['count']) ? $GLOBALS['count'] : 0) . '</div></h1></div>';
    
    //text area to display how many riders boarding or getting off
    $ridersBoarding = '<div><label for="boarding">Riders boarding:</label><select name="boarding">';
    for ($x = 0; $x <= 10; $x++) {
        $ridersBoarding .= '<option>' . $x .'</option>';
    }
    $ridersBoarding .= '</select></div>';

    $gettingOff = '<div><label for="boarding">Riders getting off:</label><select name="getting_off">';
    for ($x = 0; $x <= 10; $x++) {
        $gettingOff .= '<option>' . $x .'</option>';
    }
    $gettingOff .= '</select></div>';

    //button for depart
    $depart = '<input type="submit" name="depart" value="Depart"/>';

    $textDisplayed .= $numRiders . $ridersBoarding . $gettingOff . $depart . '</form>';
    print_r($textDisplayed);
}

// function to change count, returns number and assigns to count
function update_num_people($current) {
    $boarding = $_POST['boarding'];
    $getting_off = $_POST['getting_off'];
    $people = $boarding - $getting_off;
    return $people;
}

function complete_shuttle_request() {
    global $wpdb, $reg_errors, $num_people;

    // array of values maps to database insert fields
    $data = array(
        'shift_ID' => 1,                            // get shift id from cookies
        'userName' => 'shuttle',
        'timeOfCall' => current_time('mysql', 1),
        'pickupTime' => current_time('mysql', 1),
        'dropoffTime' => current_time('mysql', 1),
        'pickupLocation' => 'Davis',                // compare to logged vehicle location
        'dropoffLocation' => 'Diamond',                    // similar to pickup
        'comments' => 'Shuttle Route',
        'numPeople' => $num_people,
        'rideStatus' => 'complete'
    );

    // echo $num_people;
    $wpdb->insert( 'ride_requests', $data );    // insert function in WP takes in table and array of data
    echo "Request completed.";                  // send user a message when request is done
}

function init_shuttle_driver_page() {
    if ( isset($_POST['depart'] ) ) {
        global $num_people;
        $temp = (isset($GLOBALS['count']) ? $GLOBALS['count'] : 0);
        // shift_Id from cookies
        $GLOBALS['count'] = update_num_people($temp);
        $num_people = $GLOBALS['count'];

        // call complete_request function
        complete_shuttle_request(
            // shift_Id from cookies
            $num_people
        );
    }

    rider_info();
}

// shortcode call: [shuttle_driver_page]
add_shortcode('shuttle_driver_page', 'init_shuttle_driver_page');
?>