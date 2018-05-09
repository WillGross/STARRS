<?php
/* 
Plugin Name: STARRS Edit Drivers Table Plugin
Description: Adds a table with driver information, as well as three forms;
one to add a new driver, one to remove an existing one, and one to modify information in the database
Author: Jacob Tower
*/

//returns output buffer containing html code for table and add/remove forms
//called by shortcode
function displayDriverTable(){
	global $wpdb;
	
	//adds new Driver row when submit button on addDriverForm is pressed
	if(isset($_POST['add']) ){	
        //store information from each form
		//create array of values to input to DB
		$insert = array('name' => $_POST['driverName'], 
						'userName' => $_POST['driverUserName'], 
						'email' => $_POST['driverEmail'], 
						'status' => $_POST['driverStatus']);
		
  		$wpdb->insert('drivers', $insert);	//insert new row into DB
	}
	
	
	//removes Driver row when submit button on removeDriverForm is pressed 
	if(isset($_POST['remove'])){
		$remove = array('id' => $_POST['removeSelection']);	//array of values to remove in column => value form
		$wpdb->delete('drivers', $remove);	//remove selected row from DB
	}
	
	
	//modifies Driver row when submit button on modifyDriverForm is pressed
	if(isset($_POST['modify'])){
		$change = array($_POST['selectInfo'] => $_POST['newInfo']);	//array of values to modify in column => value form
		$driver = array('id' => $_POST['selectDriver']);		//array with driver to be modified
		$wpdb->update('drivers', $change, $driver);	//remove selected row from DB
	}
	
	
	//use output buffer to store/return html code
	ob_start();
		echo createDriverTable();
		echo "</br>";
		echo modifyDriverForm();
		echo "</br>";
		echo addDriverForm();
		echo "</br>";
		echo removeDriverForm();
	$output = ob_get_clean();
	return $output;
}


//creates and returns table containing driver information from the DB
function createDriverTable(){
	global $wpdb;
	$drivers = $wpdb->get_results("SELECT * FROM drivers");		//get drivers from DB
	
	//create table
	$table = "<table><tr>
			<th>ID</th>
			<th>Name</th>
			<th>Username</th>
			<th>Email</th>
			<th>Status</th></tr>";
	foreach ($drivers as $row){				//Display all data from each driver
		$table.="<tr><td>$row->id</td>
				<td>$row->name</td>
				<td>$row->userName</td>
				<td>$row->email</td>
				<td>$row->status</td></tr>";
	}
	$table.="</table>";
	return $table;
}

//creates and returns form for modifying driver table information
function modifyDriverForm(){
	global $wpdb;
	$driverList = $wpdb->get_results("SELECT id, name, userName FROM drivers");		//pull driver identification from DB
	
	//create form
	$modifyForm ="<form id=modifyDriver method=post>	
					Select a Driver: <select name=selectDriver><option value=''></option>";
					
	foreach ($driverList as $row){
		$modifyForm.="<option value=$row->id>$row->id $row->name $row->userName</option>";	//Display each driver as an option
	}
	
	//menus to select a field to edit and input new information
	$modifyForm.="</select></br> Select Information to Edit: <select name=selectInfo><option value=''></option>
					<option value=name>Name</option>
					<option value=userName>Username</option>
					<option value=email>Email</option>
					<option value=status>Status</option></select></br>
					Please Enter a new Value: <input type=text name=newInfo></br>
					<input type=submit value='Edit Driver Information' name=modify></form>";	//submit button
					
	return $modifyForm;
}


//creates and returns form for adding a new driver to the DB
function addDriverForm(){
	$addForm = "<form id=addDriver method=post>
				Driver Name: <input type=text name=driverName></br>
				Username: <input type=text name=driverUserName></br>
				Email: <input type=text name=driverEmail></br>
				Status: <input type=text name=driverStatus></br>
				<input type=submit value='Add New Driver' name='add'/></form>";
	return $addForm;
}

//creates and returns form for removing an existing driver from the DB
function removeDriverForm(){
	global $wpdb;
	$drivers = $wpdb->get_results("SELECT id, name, userName FROM drivers");	//get driver identification info
	
	//create form
	$removeForm = "<form id=removeDriver method=post>
					Select a Driver: <select name=removeSelection><option value=''></option>";
	
	foreach ($drivers as $row){
		$removeForm.="<option value=$row->id>$row->id $row->name $row->userName</option>";	//Display each driver as an option
	}
	
	$removeForm.="</select></br><input type='submit' value='Remove Driver' name='remove'/></form>";		//submit button
	return $removeForm;
}

//shortcode to put inside WordPress page: [drivers_table] (generates table and forms for editing information)
add_shortcode('drivers_table', 'displayDriverTable');
?>