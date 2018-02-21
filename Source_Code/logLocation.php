<?php
/**
 * Created in PhpStorm.
 * User: Will Gross
 * Date: 1/30/2018
 * Time: 16:32
 */

//receives data
$vehicleName=$_GET['vehicle'];
$latitude=$_GET['lat'];
$longitude=$_GET['lon'];
$sourceTime=$_GET['deviceTime'];

//sets up database variables
$servername = "localhost";
$username = "starrs";
$password = 'Wher3Bus@?';
$dbname = "starrs";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //prepares the statement to retrieve the vehicle id based on the vehicle name
    $stmt = $conn->prepare("SELECT id FROM vehicles WHERE vehicles.name='".$vehicleName."'");


    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_NUM);
    $result=$stmt->fetch();

    $vehicleID=$result[0];


    //prepares and executes statement to insert a new element into the location table
    $sql = $conn->prepare( "INSERT INTO location(vehicle_ID, latitude, longitude, time)
            VALUES (:vehicle_ID, :latitude, :longitude, :time)");
    $sql->bindParam(':vehicle_ID', $vehicleID);
    $sql->bindParam(':latitude',$latitude);
    $sql->bindParam(':longitude',$longitude);
    $sql->bindParam(':time',$sourceTime);
    // use exec() because no results are returned
    $sql->execute();
}
catch(PDOException $e)
{
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;


