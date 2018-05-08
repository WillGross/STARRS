<?php
/* 
Plugin Name: edit_shifts
Description: Adds two forms to a page to be used by security to create a new shift or remove an existing
one through the use of dropdown menus.
Author: Jacob Tower
*/



//called by the shortcode - creates/displays forms to both add and remove shifts
function createForms(){
	global $wpdb;
	
// 	call form creation methods and display forms on screen
// 	$newShift = newForm();
// 	echo $newShift . "</br>";
// 	$removeShift = removeForm();
// 	echo $removeShift;	
	
	
	
	//add new row when submit button on createShift form is pressed
    if(isset($_POST['submitNew']) ){	
        //store information from each form
  		$driver = $_POST['formDriver'];
  		$vehicle = $_POST['formVehicle'];
  		$dateY = $_POST['formYear'];
  		$dateM = $_POST['formMonth'];
  		$dateD = $_POST['formDay'];
  		$my_date = $dateY . '-' . $dateM . '-' . $dateD;
  		$dayOfWeek = $_POST['formDOW'];
  		$start = $my_date . ' ' . $_POST['formStartTime'] . ':00';
  		$end = $my_date . ' ' . $_POST['formEndTime'] . ':00';
  		  		
		//create array of values to input to DB
		$insert = array('driver_ID' => $driver, 
						'vehicle_ID' => $vehicle, 
						'date' => $my_date, 
						'dayOfWeek' => $dayOfWeek, 
						'startTime' => $start, 
						'endTime' => $end);
		
  		$wpdb->insert('shifts', $insert);	//insert new row into DB
	}
	
	
	//removes row when submit button on removeShift form is pressed 
	if(isset($_POST['submitRemove'])){
		global $wpdb;
		$wpdb->delete('ride_requests', array('shift_ID' => $_POST['formShift']));	//must delete child rows first
		$remove = array('id' => $_POST['formShift']);	//array of values to remove in column => value form
		$wpdb->delete('shifts', $remove);	//remove selected row from DB
	}
	
	//use output buffer to store/return html
	ob_start();
		echo newForm();
		echo removeForm();
	$output = ob_get_clean();
	return $output;
}

//creates the form to remove an existing shift
function removeForm(){
	global $wpdb;
	
	//query to select shift information from DB
	$shifts = $wpdb->get_results("SELECT shifts.id, shifts.date, shifts.startTime, drivers.name as driver_name, drivers.id as driver_id, vehicles.name as vehicle_name, vehicles.id as vehicle_id FROM shifts INNER JOIN drivers ON drivers.id = shifts.driver_ID INNER JOIN vehicles ON vehicles.id = shifts.vehicle_ID");

	//shift selection drop-down menu
	$template="<form id='removeShiftForm' method='post'>
				Select a Shift to Remove:<select name=formShift><option value=''></option>";
	foreach ($shifts as $row){	//populate options with each shift from database
		$template.="<option value=$row->id>Shift: $row->id Driver: $row->driver_name Vehicle: $row->vehicle_name Date: $row->date Start Time: $row->startTime</option>";
	}			
  	$template.="</select><input type='submit' value='Remove Shift' name='submitRemove'/></form>";	//submit button
  	return $template;
}


//creates the form to enter a new shift
function newForm(){
	global $wpdb;
	
	//query to select driver information
	$drivers = $wpdb->get_results("SELECT DISTINCT drivers.name as driver_name, drivers.id as driver_id FROM drivers");
	
	//driver selection drop-down menu
	$template ="<form id='newShiftForm' method='post'>
				Select A Driver: <select name=formDriver><option value=''></option>";
	foreach ($drivers as $row){	
		$template .= 	"<option value=$row->driver_id>$row->driver_name</option>";		//populate drivers from DB
	}			
  	
  	
  	//query to select vehicle information
  	$vehicles = $wpdb->get_results("SELECT DISTINCT vehicles.name as vehicle_name, vehicles.id as vehicle_id FROM vehicles"); 
  	
  	//vehicle selection drop-down menu
	$template.="</select></br>Select a Vehicle: <select name=formVehicle><option value=''></option>";
	foreach($vehicles as $row){
		$template .= "<option value=$row->vehicle_id>$row->vehicle_name</option>";		//populate vehicles from DB
	}
	
	
	//date selection menus
	$template.="</select></br>Shift Date: <select name=formYear><option value=''>Year</option>";	//generate year selector
  	for ($year = date('Y'); $year > date('Y')-100; $year--) { 
		$template.= "<option value=$year>$year</option>";		
	} 
	$template .="</select><select name=formMonth><option value=''>Month</option>";	//generate month selector
    for ($month = 1; $month <= 12; $month++) { 
    	$t1 = strlen($month)==1 ? '0'.$month : $month;
		$template.="<option value=$t1>$t1</option>";		
	}
	$template.="</select><select name=formDay><option value=''>Day</option>";		//generate day selector
	for ($day = 1; $day <= 31; $day++) { 
		$t2 = strlen($day)==1 ? '0'.$day : $day;
		$template.= "<option value = $t2>$t2</option>";		
	} 
	$template.="</select>,</br>";
	
	
	//day of week selection
	$template.= "Day of Week: <select name=formDOW>
				<option value=''></option>
				<option value='Monday'>Monday</option>
				<option value='Tuesday'>Tuesday</option>
				<option value='Wednesday'>Wednesday</option>
				<option value='Thursday'>Thursday</option>
				<option value='Friday'>Friday</option>
				<option value='Saturday'>Saturday</option>
				<option value='Sunday'>Sunday</option>
			</select>";
	
	
	//start-time selection
	$template.= "</br>Start Time: <select name=formStartTime>";
	for($hours=0; $hours<24; $hours++){ // the interval for hours is '1'
    	for($mins=0; $mins<60; $mins+=30){ // the interval for mins is '30'
     	    $stime = str_pad($hours,2,'0',STR_PAD_LEFT) .':'. str_pad($mins,2,'0',STR_PAD_LEFT);
     	    $template.="<option value=$stime>$stime</option>";
     	 }
    }
    
    
    //end-time selection
    $template.="</select></br>End Time: <select name=formEndTime>";
	for($hours=0; $hours<24; $hours++){ // the interval for hours is '1'
    	for($mins=0; $mins<60; $mins+=30){ // the interval for mins is '30'
     	    $etime = str_pad($hours,2,'0',STR_PAD_LEFT) .':'. str_pad($mins,2,'0',STR_PAD_LEFT);
     	    $template.="<option value=$etime>$etime</option>";
     	 }
    }
    $template.="</select></br><input type='submit' value='Create New Shift' name='submitNew'/></form>";

	//return completed form
	return $template;
}



//shortcode to put inside WordPress page: [edit_schedule] (generates both forms)
add_shortcode('edit_schedule', 'createForms');
?>