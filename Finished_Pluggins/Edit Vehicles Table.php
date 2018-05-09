<?php
/* 
Plugin Name: STARRS Edit Vehicles Table Plugin
Description: Adds a table with vehicle information, as well as three forms;
one to add a new vehicle, one to remove an existing one, and one to modify information in the database
Author: Jacob Tower
*/


//called by shortcode - calls functions to create html strings for plugin and returns output buffer with html
function displayVehicleTable(){
	global $wpdb;
	
	//adds new vehicle row when submit button on addVehicleForm is pressed
	if(isset($_POST['add']) ){	
        //store information from each form
		//create array of values to input to DB
		$insert = array('name' => $_POST['vehicleName'], 
						'plateNum' => $_POST['plateNumber'], 
						'mileage' => $_POST['mileage'], 
						'oilStatus' => $_POST['oilStatus'],
						'antifreezeStatus' => $_POST['antifreezeStatus'],
						'isDriving' => $_POST['isDriving'],
						'operationalStatus' => $_POST['operationalStatus']);

  		$wpdb->insert('vehicles', $insert);	//insert new row into DB
  	}
	
	//modifies vehicle row when submit button on modifyVehicleForm is pressed
	if(isset($_POST['modify'])){
		$change = array($_POST['selectInfo'] => $_POST['newInfo']);	//array of values to modify in column => value form
		$vehicle = array('id' => $_POST['selectVehicle']);		//array with vehicle to be modified
		$wpdb->update('vehicles', $change, $vehicle);	//modify selected row in DB
	}
	
	
	//removes Driver row when submit button on removeDriverForm is pressed 
	if(isset($_POST['remove'])){
		$remove = array('id' => $_POST['removeSelection']);	//array of values to remove in column => value form
		$wpdb->delete('vehicles', $remove);	//remove selected row from DB
	}
	
	
	ob_start();
		echo vehicleTable();
		echo "</br>";
		echo modifyVehicleForm();
		echo "</br>";
		echo addVehicleForm();
		echo "</br>";
		echo removeVehicleForm();
	$output = ob_get_clean();
	return $output;
}

//creates and returns a string with html code for a form to remove a vehicle
function removeVehicleForm(){
	global $wpdb;
	$vehicles = $wpdb->get_results("SELECT id, name, plateNum FROM vehicles");	//get vehicle identification info
	
	//create form
	$removeForm = "<form id=removeVehicle method=post>
					Select a Vehicle: <select name=removeSelection><option value=''></option>";
	
	foreach ($vehicles as $row){
		$removeForm.="<option value=$row->id>$row->id $row->name $row->plateNum</option>";	//Display each vehicle as an option
	}
	
	$removeForm.="</select></br><input type='submit' value='Remove Vehicle' name='remove'/></form>";		//submit button
	return $removeForm;
}

//creates and returns a string with html code for a form to add a vehicle
function addVehicleForm(){
	$addForm = "<form id=addVehicle method=post>
				Vehicle Name: <input type=text name=vehicleName></br>
				Plate Number: <input type=text name=plateNumber></br>
				Mileage: <input type=text name=mileage></br>
				Oil Status: <select name=oilStatus>
					<option value=''></option>
					<option value='ok'>OK</option>
					<option value='low'>Low</option></select></br>
				Antifreeze Status: <select name=antifreezeStatus>
					<option value=''></option>
					<option value='ok'>OK</option>
					<option value='low'>Low</option></select></br>
				Is Driving? <select name=isDriving>
					<option value=''></option>
					<option value='yes'>Yes</option>
					<option value='no'>No</option></select></br>
				Operational Status: <input type=text name=operationalStatus></br>
				<input type=submit value='Add New Vehicle' name='add'/></form>";
	return $addForm;
}


//creates and returns a string with html code for a form to modify the vehicles table
function modifyVehicleForm(){
	global $wpdb;
	$vehicleList = $wpdb->get_results("SELECT id, name, plateNum, operationalStatus FROM vehicles");		//pull vehicle identification from DB
	
	//create form
	$modifyForm ="<form id=modifyVehicle method=post>	
					Select a Vehicle: <select name=selectVehicle><option value=''></option>";
					
	foreach ($vehicleList as $row){
		$modifyForm.="<option value=$row->id>$row->id $row->name $row->plateNum $row->operationalStatus</option>";	//Display each vehicle as an option
	}
	
	//menus to select a field to edit and input new information
	$modifyForm.="</select></br> Select Information to Edit: <select name=selectInfo><option value=''></option>
					<option value=name>Name</option>
					<option value=plateNum>Plate Number</option>
					<option value=mileage>Mileage</option>
					<option value=oilStatus>Oil Status</option>
					<option value=antifreezeStatus>Antifreeze Status</option>
					<option value=isDriving>Is Driving</option>
					<option value=operationalStatus>Operational Status</option></select></br>
					Please Enter a new Value: <input type=text name=newInfo></br>
					<input type=submit value='Edit Vehicle Information' name='modify'></form>";	//submit button
					
	return $modifyForm;
}

//creates and returns a string with html code to create a table with vehicles information from the DB
function vehicleTable(){
	global $wpdb;
	$vehicles = $wpdb->get_results("SELECT * FROM vehicles");		//get vehicle information from DB
	
	//create table
	$table = "<table><tr>
			<th>ID</th>
			<th>Name</th>
			<th>Plate Number</th>
			<th>Mileage</th>
			<th>Oil Status</th>
			<th>Antifreeze Status</th>
			<th>Is Driving</th>
			<th>Operational Status</th></tr>";
	foreach ($vehicles as $row){				//Display all data from each vehicle
		$table.="<tr><td>$row->id</td>
				<td>$row->name</td>
				<td>$row->plateNum</td>
				<td>$row->mileage</td>
				<td>$row->oilStatus</td>
				<td>$row->antifreezeStatus</td>
				<td>$row->isDriving</td>
				<td>$row->operationalStatus</td></tr>";
	}
	$table.="</table>";
	
	return $table;
}

//shortcode to put inside WordPress page: [vehicles_table] (generates table and forms for editing information)
add_shortcode('vehicles_table', 'displayVehicleTable');
?>