<?php
/*
Plugin Name: STARRS ride request form
Description: A plugin that allows a user to fill out a form to request a ride
Version: 1.0
Author: Paige Hanssen
*/

//talks to ride_requests

// function that sets up the form with html
function jitney_request_form($pickup, $destination, $num_people, $comments) {
    global $wpdb;
    $databaseCall = $wpdb -> get_results('SELECT * from vehicles');

    // place for any styling we want to add
    echo '
        <style>
        </style>
    ';

    // building the actual form - fields for pickup, destination, number of people and comments
    $pluginContent =
        '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <div>
            <label for="pickup">Enter pickup location <strong>*</strong></label>
            <input type="textarea" name="pickup" value="' . ( isset( $_POST['pickup'] ) ? $pickup : null ) . '">
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
        </div>
    
        <input type="submit" name="submit" value="Register"/>
        </form>';
    
    print_r($pluginContent);
}

// validate function for the request form
function request_validation($pickup, $destination, $num_people, $comments) {
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

// assign values to global variables, display message when registration complete
function complete_request() {
    global $reg_errors, $pickup, $destination, $num_people, $comments;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'pickup'    =>   $pickup,
        'destination'    =>   $destination,
        'num_people'     =>   $num_people,
        'comments'      =>   $comments
        );
        $user = wp_insert_user( $userdata );
        echo 'Registration complete.';
    }
}

function custom_request_function() {
    if ( isset($_POST['submit'] ) ) {
        // call validation function
        request_validation(
            $_POST['pickup'],
            $_POST['destination'],
            $_POST['num_people'],
            $_POST['comments']
        );
            
        // sanitize form input to ensure safe data
        global $pickup, $destination, $num_people, $comments;
        $pickup   =   sanitize_text_field( $_POST['pickup'] );
        $destination   =   sanitize_text_field( $_POST['destination'] );
        $num_people      =   sanitize_option( $_POST['num_people'] );
        $comments    =   sanitize_textarea_field( $_POST['comments'] );
    
        // call complete_request function to create the user
        // only when no WP_error is found
        complete_request(
            $pickup,
            $destination,
            $num_people,
            $comments
        );
    }
    
    // call function to display request form
    jitney_request_form(
        $pickup,
        $destination,
        $num_people,
        $comments
    );
}

add_shortcode('request_form', 'custom_request_function')
// add_action(place, function)
// add_action( 'wp_head', 'custom_request_function')
?>