<?php
/**
 * Created by PhpStorm.
 * User: zhzhang
 * Date: 1/27/18
 * Time: 8:50 PM
 */
// We need to find a way to get the username later.
// Right now, just use this.
// Update (01/30/18): I think username should be sent from JitneyUserPage instead.
//$username = "mulecolby18";
date_default_timezone_set('EST');
try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["pickup"]) && isset($_POST["dropoff"])) {
        # We sanitize the inputs here.
        $pickup = htmlspecialchars($_POST["pickup"], ENT_QUOTES);
        $dropoff = htmlspecialchars($_POST["dropoff"], ENT_QUOTES);
        $passengers = $_POST["number"];
        $comment = htmlspecialchars($_POST["comment"], ENT_QUOTES);
        $username = htmlspecialchars($_POST["username"]);

        # Preserve all line breaks in all texts.
        $pickup = str_replace("\n", '<br>', $pickup);
        $dropoff = str_replace("\n", '<br>', $dropoff);
        $comment = str_replace("\n", '<br>', $comment);

        # Get the time when this pickup request is issued
        $time = date('g:i:s a, m/d, D');

        # Prepare to add the entry into the database
        $command = "INSERT INTO jitney_queue 
    (pickupLocation, dropoffLocation, numOfPassenger, requestTime, comments, username)
    VALUES (:pickup, :dropoff, :passengers, :time, :comment, :username)";
        $pdoObject = $db->prepare($command);

        $pdoObject->execute(array(
            ':pickup' => $pickup,
            ':dropoff' => $dropoff,
            ':passengers' => $passengers,
            ':time' => $time,
            ':comment' => $comment,
            ':username' => $username
        ));
    }

} catch (PDOException $ex) {

}

if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: ".$_SERVER['HTTP_REFERER']);
} else {
    header("Location: JitneyUserPage.php");
}
?>