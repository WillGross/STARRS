<?php
/**
 * Created by PhpStorm.
 * User: zhzhang
 * Date: 1/27/18
 * Time: 8:50 PM
 */
// We need to find a way to get the username later.
// Right now, just use this.
$username = "mulecolby18";

try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["pickup"]) && isset($_POST["dropoff"])) {
        # We sanitize the inputs here.
        $pickup = $db->quote(htmlspecialchars($_POST["pickup"]));
        $dropoff = $db->quote(htmlspecialchars($_POST["dropoff"]));
        $passengers = $_POST["number"];
        $comment = $db->quote(htmlspecialchars($_POST["comment"]));

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
header("Location: JitneyUserPage.php");
?>