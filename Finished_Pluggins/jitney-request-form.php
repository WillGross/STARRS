<?php
/*
Plugin Name: STARRS ride request form
Description: A plugin that allows a user to fill out a form to request a ride
Version: 1.0
Author: Paige Hanssen
*/

// function that sets up the form with html
function jitney_request_form($pickup, $destination, $num_people, $comments, $dispatcher, $driverRequest) {
    // extra field created in the form if a dispatcher is logged in
    if ($dispatcher == "True") {
        $myColbyUser = 
            '<div>
                <label for="username">Caller\'s myColby username:<strong>*</strong></label>
                <input type="textarea" name="username" value="">
            </div>';
    }

    // if the driverRequest is making the request, then auto-fill pickup spot to Spa
    if($driverRequest == "True") {
        $driverLocation = "Spa";
    } else {
        $driverLocation = $pickup;
    }

    // building the actual form - fields for pickup, destination, number of people, username and comments
    $pluginContent =
        '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <div>
            <label for="pickup">Enter pickup location <strong>*</strong></label>
            <input type="textarea" name="pickup" value="' . $driverLocation . '">
        </div>
        
        <div>
            <label for="destination">Enter dropoff location <strong>*</strong></label>
            <input type="textarea" name="destination" value="">
        </div>
        
        <div>
            <label for="num_people">How many people are traveling? <strong>*</strong></label>
            <input type="select" name="num_people" value="">
        </div>
        
        <div>
            <label for="comments">Additional comments:</label>
            <input type="textarea" name="comments" value="">
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
    if ( empty( $pickup ) || empty( $destination || empty($num_people) || empty($username)) ) {
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
    global $wpdb, $reg_errors, $pickup, $destination, $num_people, $comments, $username;
    request_validation($pickup, $destination, $num_people, $comments, $username);

    // array of values maps to database insert fields
    $data = array(
        'shift_ID' => 1,                            // get shift id from who is currently driving
        'userName' => $username,
        'timeOfCall' => current_time('mysql', 1),
        'pickupTime' => current_time('mysql', 1),   // - originally set to null?
        'dropoffTime' => current_time('mysql', 1),  // - originally set to null?
        'pickupLocation' => $pickup,
        'dropoffLocation' => $destination,
        'comments' => $comments,
        'numPeople' => $num_people,
        'rideStatus' => 'pending'               // if a driverRequest fills out form, it is 'En route' and not pending
    );
    $wpdb->insert( 'ride_requests', $data );    // insert function in WP takes in table and array of data
    $pickup = '';                               // reset the pickup field to empty for non-driver forms
    echo "Request completed.";                  // send user a message when request is done
}

function custom_request_function($atts) {
    // map attributes to values
    $a = shortcode_atts( array(
        'dispatcher' => null,
        'driver' => "False"      // for some reason, driver is not being read as part of attributes if originally set to null
    ), $atts );
    $dispatcher = $a['dispatcher'];
    $driverRequest = $a['driver'];

    //when submit button is clicked, call request complete function
    if ( isset($_POST['submit'] ) ) {
    
        global $pickup, $destination, $num_people, $comments, $username;
        $pickup         = ( $_POST['pickup'] );
        $destination    = ( $_POST['destination'] );
        $num_people     = ( $_POST['num_people'] );
        $comments       = ( $_POST['comments'] );
        if($dispatcher) {
            $username   = ( $_POST['username'] );
        } else {
            $username   = 'user';   // get username from cookies
        }


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
        $driverRequest
    );
}

//shortcode to put inside WordPress page: [request_form] OR [request_form dispatcher=True driver=True]
add_shortcode('request_form', 'custom_request_function')
?>