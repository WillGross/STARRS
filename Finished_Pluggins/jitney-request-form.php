<?php
/*
Plugin Name: STARRS ride request form
Description: A plugin that allows a user to fill out a form to request a ride
Version: 1.0
Author: Paige Hanssen
*/

// function that sets up the form with html
function jitney_request_form($pickup, $destination, $num_people, $comments, $dispatcher) {
    // extra field created in the form if a dispatcher is logged in
    if ($dispatcher == "True") {
        $myColbyUser = 
            '<div>
                <label for="username">Caller\'s myColby username:<strong>*</strong></label>
                <input type="textarea" name="username" value="' . ( isset( $_POST['username'] ) ? $dispatcher : null ) . '">
            </div>';
    }

    if($driver = "True") {
        $driverLocation = "Spa";
    }

    // building the actual form - fields for pickup, destination, number of people and comments
    $pluginContent =
        '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <div>
            <label for="pickup">Enter pickup location <strong>*</strong></label>
            <input type="textarea" name="pickup" value="' . (isset($driverLocation) ? $driverLocation : (isset( $_POST['pickup'] ) ? $pickup : null )) . '">
        </div>
        
        <div>
            <label for="destination">Enter dropoff location <strong>*</strong></label>
            <input type="textarea" name="destination" value="' . ( isset( $_POST['destination'] ) ? $destination : null ) . '">
        </div>
        
        <div>
            <label for="num_people">How many people are traveling? <strong>*</strong></label>
            <input type="select" name="num_people" value="' . ( isset( $_POST['num_people']) ? $num_people : null ) . '">
        </div>
        
        <div>
            <label for="comments">Additional comments:</label>
            <input type="textarea" name="comments" value="' . ( isset( $_POST['comments']) ? $comments : null ) . '">
        </div>'
        . ( isset($myColbyUser) ? $myColbyUser : '' ) .
        '<input type="submit" name="submit" value="Request"/>
        </form>';
    
    // print out form
    print_r($pluginContent);
}

// validate function for the request form
function request_validation($pickup, $destination, $num_people, $comments, $username) {
    global $reg_errors;
    $reg_errors = new WP_Error;

    //display error is required field is not filled out
    if ( empty( $pickup ) || empty( $destination ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    if ( is_wp_error( $reg_errors ) ) {
        foreach ( $reg_errors->get_error_messages() as $error ) {
            echo '<div>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';
            echo '</div>';
        }
    }
}

// submit data to ride_requests table
function complete_request() {
    global $wpdb, $reg_errors, $pickup, $destination, $num_people, $comments;

    //access device time of request -> timeOfCall, shift_ID, pickupTime, dropoffTime
    $data = array(
        // 1,1,'wlgross',CURRENT_TIMESTAMP ,CURRENT_TIMESTAMP , CURRENT_TIMESTAMP ,'here','there','pending request 1 from wlgross', 2, 'pending'
        'shift_ID' => 1,
        'userName' => 'phans',
        'timeOfCall' => current_time('mysql', 1),
        'pickupTime' => current_time('mysql', 1),   // - originally set to null?
        'dropoffTime' => current_time('mysql', 1),  // - originally set to null?
        'pickupLocation' => $pickup,
        'dropoffLocation' => $destination,
        'numPeople' => $num_people,
        'rideStatus' => 'pending'            // if a driver fills out form, it is 'En route' and not pending
    );
    $wpdb->insert( 'ride_requests', $data );    //insert function in WP takes in table and array of data
    echo "Request completed.";                  //send user a message when request is done
}

function custom_request_function($atts) {
    $a = shortcode_atts( array(
        'dispatcher' => null,
        'driver' => null
    ), $atts );
    $dispatcher = $a['dispatcher'];
    $driver = $a['driver'];

    //when submit button is clicked, call request complete function
    if ( isset($_POST['submit'] ) ) {
        if($dispatcher) {
            $username = ( $_POST['username'] );
        } else {
            $username = null;
        }
    
        global $pickup, $destination, $num_people, $comments, $username;
        $pickup   =   ( $_POST['pickup'] );
        $destination   =  ( $_POST['destination'] );
        $num_people      =   ( $_POST['num_people'] );
        $comments    =  ( $_POST['comments'] );

        // call complete_request function
        complete_request(
            $pickup,
            $destination,
            $num_people,
            $comments,
            $username
        );
    }
    
    // call function to display request form
    jitney_request_form(
        $pickup,
        $destination,
        $num_people,
        $comments,
        $dispatcher,
        $driver
    );
}

//shortcode to put inside WordPress page: [request_form] OR [request_form dispatcher=True driver=True]
add_shortcode('request_form', 'custom_request_function')
?>