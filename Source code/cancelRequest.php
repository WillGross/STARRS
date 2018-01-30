<?php
/**
 * cancelRequest.php -- Cancels one request from the unprocessed queue
 * (Any request in the current_request queue can't be cancelled but can be dropped off)
 * User: zhzhang
 * Date: 1/30/18
 * Time: 1:06 PM
 */
// We need to find a way to get the username later.
// Right now, just use this.
// Update (01/30/18): I think username should be sent from JitneyUserPage instead.
// Would do the same modifications to dropoffJRD.php, submitJR.php, and pickupJRD.php.
//$username = "mulecolby18";
date_default_timezone_set('EST');
try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["entryID"])) {
        $entryID = $_POST["entryID"];
        $username = htmlspecialchars($_POST["username"]);
        $db->query("DELETE FROM jitney_queue WHERE queueID = $entryID;");
        // A safer query would be:
//        $db->query("DELETE FROM jitney_queue WHERE queueID = $entryID
//                                             AND (username = '$username'
//                                             OR '$username' = '<dispatcher's myColby username>');");
    }

} catch (PDOException $ex) {

}
header("Location: JitneyUserPage.php");
?>