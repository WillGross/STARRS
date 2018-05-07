<?php
/*
Plugin Name: STARRS complete driving plugin
Description: A plugin for the driver to fill out info before they finish a shift
Version: 1.0
Author: Paige Hanssen
*/

// creates a form for the driver to fill out after driving is done
function driver_form() {
    // form with 5 fields and a submit button
    $textDisplayed = '<form method="post">
        <div>
            <label for="mileage">Enter finished mileage</label>
            <input type="textarea" name="mileage" value="">
        </div>

        <div>
            <label for="gallons">Enter gallons filled</label>
            <input type="textarea" name="gallons" value="">
        </div>

        <div>
            <label for="oilstatus">Oil Status</label>
            <input type="radio" name="oilstatus" value="ok" checked>Ok 
            <input type="radio" name="oilstatus" value="low">Low
        </div>

        <div>
            <label for="antifreeze">Antifreeze Status</label>
            <input type="radio" name="antifreeze" value="ok" checked>Ok 
            <input type="radio" name="antifreeze" value="low">Low
        </div>

        <div>
            <label for="comments">Comments</label>
            <input type="textarea" name="comments" value="">
        </div>
        <input type="submit" name="submit" value="Submit"/>
        </form>';
    print_r($textDisplayed);
}

// function to send data from form into database
function complete_driving_request() {
    global $wpdb, $mileageFinish, $fillUpGallons, $oilStatus, $antifreezeStatus, $comments;

    // array of values maps to database insert fields
    $data = array(
        'shift_ID' => 1,                            // get shift id from cookies
        'mileageStart' => 3,                        // get mileage start from cookies
        'mileageFinish' => $mileageFinish,
        'fillUpGallons' => $fillUpGallons,
        'oilStatus' => $oilStatus,
        'antifreezeStatus' => $antifreezeStatus,
        'comments' => $comments
    );

    $wpdb->insert( 'shift_info', $data );       // insert function in WP takes in table and array of data
    echo "Request completed.";                  // send completed message when request is done
    
    // redirect to to end of shift page
}

// puts everything together - checks if submit has been clicked and populates the page with the html form
function complete_driving() {
    if ( isset($_POST['submit'] ) ) {
        global $mileageFinish, $fillUpGallons, $oilStatus, $antifreezeStatus, $comments;
        $mileageFinish    = ( $_POST['mileage'] );
        $fillUpGallons    = ( $_POST['gallons'] );
        $oilStatus        = ( $_POST['oilstatus'] );
        $antifreezeStatus = ( $_POST['antifreeze'] );
        $comments         = ( $_POST['comments'] );

        // call function to insert data into database
        complete_driving_request(
            $mileageFinish,
            $fillUpGallons,
            $oilStatus,
            $antifreezeStatus,
            $comments
        );
    }

    driver_form();  // function to set up html on page
}

// shortcode call: [complete_driving_form]
add_shortcode('complete_driving_form', 'complete_driving');
?>