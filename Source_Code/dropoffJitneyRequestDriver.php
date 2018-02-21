<?php
/**
 * dropoffJitneyRequestDriver.php -- Handles when driver clicks "dropoff" on a request.
 * User: zhzhang
 * Date: 1/28/18
 * Time: 8:24 PM
 */
// We need to find a way to get the username later.
// Right now, just use this.
// Update (01/30/18): I think username should be sent from JitneyUserPage instead.
//$username = "mulecolby18";
date_default_timezone_set('EST');
try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["entryID"])) {
        $entryID = $_POST["entryID"];
        $username = htmlspecialchars($_POST["username"]);
        $rows = $db->query("SELECT * FROM jitney_current_request WHERE queueID = $entryID;");

        if ($rows->rowCount() !== 0) {
            foreach ($rows as $row) {
                # Get the time when this request is dropped off
                $dropoffTime = date('g:i:s a, m/d, D');

                # Modify the corresponding entry in jitney_log to update the information
                $db->query("UPDATE jitney_log 
                    SET dropOffTime = '$dropoffTime'
                    WHERE entryID = $entryID;");

                # Remove the corresponding entries from the other two queues
                $db->query("DELETE FROM jitney_current_request WHERE queueID = $entryID;");
                $db->query("DELETE FROM jitney_queue WHERE entryID = $entryID;");
            }
        }
    }

} catch (PDOException $ex) {

}
header("Location: JitneyDriverPage.php");
?>