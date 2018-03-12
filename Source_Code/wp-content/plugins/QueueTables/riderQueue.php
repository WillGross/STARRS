<?php
/**
 * @package riderQueue
 */
/*
Plugin Name: riderQueue
Description: Generates a table from the database including all of the requests that are currently in this drivers jitney
Version: 1.0.0
Author: Will Gross
Text Domain: QueueTables
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_shortcode('RiderQueue',function( $atts ){
    ?>
ugh
    <table class="riderQueue">
        <tr>
            <th>Location</th>
            <th>Destination</th>
            <th># Ppl.</th>
            <th>Comments</th>
            <th>Request Time</th>
            <th>Action</th>
        </tr>
        <?php

        ?>
    </table>

    <?php
});