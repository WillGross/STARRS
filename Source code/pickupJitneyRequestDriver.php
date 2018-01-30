<?php
/**
 * pickupJitneyRequestDriver.php -- Handles when driver clicks "pickup" to a request.
 * User: zhzhang
 * Date: 1/28/18
 * Time: 8:40 PM
 */
// We need to find a way to get the username later.
// Right now, just use this.
$username = "mulecolby18";
date_default_timezone_set('EST');
try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["entryID"])) {
        $entryID = $_POST["entryID"];
        $rows = $db->query("SELECT * FROM jitney_queue WHERE entryID = $entryID;");

        if ($rows->rowCount() !== 0) {
            foreach ($rows as $row) {
                # Get the time when this pickup request is clicked on
                $pickupTime = date('g:i:s a, m/d, D');

                # Prepare to add the entry into the jitney request logs
                $command = "INSERT INTO jitney_log 
                    (entryID, timeOfCall, pickUpTime, pickupLocation, dropoffLocation, numPeople)
                    VALUES (:ID, :timeOfCall, :pickUpTime, :pickup, :dropoff, :passengers);";
                $pdoObject = $db->prepare($command);

                # Add it to the logs
                $pdoObject->execute(array(
                    ':ID' => $entryID,
                    ':timeOfCall' => $row["requestTime"],
                    ':pickUpTime' => $pickupTime,
                    ':pickup' => $row["pickupLocation"],
                    ':dropoff' => $row["dropoffLocation"],
                    ':passengers' => $row["numOfPassenger"],
                ));

                # Also, add the submitted pickUp request to jitney_current_request.
                $db->query("INSERT INTO jitney_current_request (queueID) 
                                        VALUES ($entryID);");
            }
        }
    }

} catch (PDOException $ex) {

}
header("Location: JitneyDriverPage.php");
?>