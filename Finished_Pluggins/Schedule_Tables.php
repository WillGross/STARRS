<?php
/* 
Plugin Name: Schedule Table
Description: Display relevent schedule information - two views, one for general users and one for drivers
Author: Jacob Tower
*/

// called by shortcode - dynamically fills table elements from DB
function createTable($atts){
	$atts = shortcode_atts(array('case' => "general"), $atts);	//store shortcode argument
	$case = $atts['case'];
	global $wpdb;
	$template = tableFormat($case);	//builds basic table layout

	if($case == "driver"){	//driver SQL query
		$scheduleInfo = $wpdb->get_results("SELECT date, dayOfWeek, startTime, endTime, drivers.name AS name1, vehicles.name AS name2 FROM shifts INNER JOIN drivers ON shifts.driver_ID = drivers.id INNER JOIN vehicles ON shifts.vehicle_ID = vehicles.id");
	}

	else{	//general user SQL query
		$scheduleInfo = $wpdb->get_results("SELECT date, dayOfWeek, startTime, endTime FROM shifts");
	}
	
	//populate table
	foreach ($scheduleInfo as $row){	
		$date = $row->date;
		$start = ltrim($row->startTime, $date);	//remove date from time information
		$end = ltrim($row->endTime, $date);
		$template .= "<tr>";
		if($case == "driver"){		//driver-only data
			$template.="<td> $row->name1</td>
						<td> $row->name2</td>";
		}
		//this data will always be included
		$template.=		"<td> $date </td>
						<td>$row->dayOfWeek</td>
						<td>$start</td>
						<td>$end</td>
						</tr>";
	}

	$template .= "</table>";
	echo $template;
}

//creates the table headers
function tableFormat($user){
	$myTable = "<table><tr>";
	
	//add columns for driver view
	if($user == "driver"){		
		$myTable .= "<th>Driver</th>
					<th>Vehicle</th>";
	}
	
	//these columns are always displayed
	$myTable .= "<th>Date</th>		
				<th>Day of the Week</th>
				<th>Start Time</th>
				<th>End Time</th></tr>";
		return $myTable;
}

//shortcode to put inside WordPress page: [schedule] OR [schedule case = "driver"]
add_shortcode('schedule', 'createTable');
?>