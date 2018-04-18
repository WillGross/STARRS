<?php
/**
 * @package queueTable
 */
/*
Plugin Name: queueTable
Description: Generates a table from the database including all of the requests that are currently in this drivers jitney
Version: 1.0.0
Author: Will Gross
Text Domain: QueueTables
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function queue_table($pending, $waiting, $active, $rider, $driver, $dispatcher){
    //ensures that the type of table is specified
    if($rider=="False" && $driver=="False" && $dispatcher=="False"){
        echo "No User Type Specified For Table";
    }
    if($pending=="False" && $waiting=="False" && $active=="False"){
        echo "No Ride Status Specified For Table";
    }

    global $wpdb;

    //variable used to hold all of the html, css, and js code to be
    $pluginContent=

    //css styling for queue
    '<style>
        .queue  {
            table-layout: fixed;
            width: 100%;
            text-align: center;
            border: 1px solid black;
            border-collapse: collapse;
        }
        tr:nth-child(odd) {
            background-color: #EEEEEE;
        }
        tr:nth-child(even) {
            background-color: #F8F8F8;
        }
    </style>
    <br/>';

    //determine the title of table based on table type
    if($active=="True"){
        $pluginContent=$pluginContent.
            '<h2>Current Riders</h2>';
    }elseif ($waiting=="True"){
        $pluginContent=$pluginContent.
            '<h2>Accepted, Waiting For Pickup</h2>';
    }else {
        $pluginContent=$pluginContent.
            '<h2>Pending Ride Requests</h2>';
    }

    //appends all universal headers to the table
    $pluginContent= $pluginContent.
    '<table class="queue">
        <tr>
            <th>Location</th>
            <th>Destination</th>
            <th># Ppl.</th>';

    if($active=="False"){
        $pluginContent=$pluginContent.
            '<th>Request Time</th>';
    }else{
        $pluginContent=$pluginContent.
            '<th>Pickup Time</th>';
    }

    //if the user is not a rider (driver or dispatch), include the header for comments
    if($rider!="True") {
        //if the user is a dispatcher, include the username of the requester
        if($dispatcher=="True") {
            //if the user is a dispatcher and the ride has been accepted, include what vehicle
            if($pending!= "True"){
                $pluginContent= $pluginContent.
                    '<th > Vehicle</th >';
            }
            $pluginContent= $pluginContent.
                '<th > User </th >';
        }
        $pluginContent= $pluginContent.
        '<th > Comments</th >';
    }


    //appends the last universal header to the table and ends the row of headers
    $pluginContent=$pluginContent.
            '<th>Action</th>
        </tr>';

    if($driver=="True"){
        //Collect the shift info from cookie created in before you drive
        //store as shift variable
        $shift=1;

        if($active=="True"){
            $requests=$wpdb->get_results("select * from ride_requests where shift_ID=".$shift." and rideStatus='enroute'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->pickupTime.'</td>
                        <td>'.$request->comments.'</td>
                        <td>DROP OFF</td>
                    </tr>';
            }
        }elseif ($waiting=="True"){
            $requests=$wpdb->get_results("select * from ride_requests where shift_ID=".$shift." and rideStatus='accepted'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->timeOfCall.'</td>
                        <td>'.$request->comments.'</td>
                        <td>PICK UP</td>
                    </tr>';
            }
        }else{
            $requests=$wpdb->get_results("select * from ride_requests where shift_ID=".$shift." and rideStatus='pending'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->timeOfCall.'</td>
                        <td>'.$request->comments.'</td>
                        <td>ACCEPT</td>
                    </tr>';
            }
        }
    }elseif ($dispatcher=="True"){
        if($active=="True"){
            $requests=$wpdb->get_results("select * from ride_requests as rr join shifts as s on rr.shift_ID=s.id join vehicles as v on s.vehicle_ID=v.id where rr.rideStatus='enroute'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->pickupTime.'</td>
                        <td>'.$request->name.'</td>
                        <td>ONCE USERNAME IS ADDED TO RIDE REQUESTS, ADD IT HERE</td>
                        <td>'.$request->comments.'</td>
                        <td>CANCEL</td>
                    </tr>';
            }
        }elseif ($waiting=="True"){
            $requests=$wpdb->get_results("select * from ride_requests as rr join shifts as s on rr.shift_ID=s.id join vehicles as v on s.vehicle_ID=v.id where rr.rideStatus='enroute'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->timeOfCall.'</td>
                        <td>'.$request->name.'</td>
                        <td>ONCE USERNAME IS ADDED TO RIDE REQUESTS, ADD IT HERE</td>
                        <td>'.$request->comments.'</td>
                        <td>CANCEL</td>
                    </tr>';
            }
        }else{
            $requests=$wpdb->get_results("select * from ride_requests where rideStatus='pending'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->timeOfCall.'</td>
                        <td>ONCE USERNAME IS ADDED TO RIDE REQUESTS, ADD IT HERE</td>
                        <td>'.$request->comments.'</td>
                        <td>CANCEL</td>
                    </tr>';
            }
        }
    }else{
        if($active=="True"){
            $requests=$wpdb->get_results("select * from ride_requests where rideStatus='enroute'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->pickupTime.'</td> 
                        <td></td>
                    </tr>';
            }
        }elseif ($waiting=="True"){
            $requests=$wpdb->get_results("select * from ride_requests where rideStatus='accepted'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->timeOfCall.'</td>
                        <td></td>
                    </tr>';
            }
        }else{
            $requests=$wpdb->get_results("select * from ride_requests where rideStatus='pending'");
            foreach ($requests as $request){
                $pluginContent=$pluginContent.
                    '<tr>
                        <td>'.$request->pickupLocation.'</td>
                        <td>'.$request->dropoffLocation.'</td>
                        <td>'.$request->numPeople.'</td>
                        <td>'.$request->timeOfCall.'</td>
                        <td></td>
                    </tr>';
            }
        }
    }


    //ends the table
    $pluginContent=$pluginContent.
    '</table>
    <br/>';

//    global $wpdb;
//    $results = $wpdb->get_results( "SELECT * FROM ride_requests" );
//    $pluginContent=$pluginContent.var_dump($results);
    return $pluginContent;
}

//function links attributes of shortcode to the main function, executes main function, then prints the plugin content
function queue_Table_Atts($atts){
    $a = shortcode_atts( array(
        'pending' => "False",
        'waiting' => "False",
        'active' => "False",
        'rider' => "False",
        'driver' => "False",
        'dispatcher' => "False",
        ), $atts );

    print_r(queue_table( $a['pending'], $a['waiting'], $a['active'], $a['rider'], $a['driver'], $a['dispatcher']));
}
add_shortcode('queue_Table', 'queue_Table_Atts');