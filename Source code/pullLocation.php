<?php
/**
 * pullLocation: Gets the most recent location of the vehicles
 * User: zhzhang
 * Date: 1/31/18
 * Time: 11:19 AM
 */
header("Access-Control-Allow-Origin: *"); // Allow CORS (Cross-Origin Resource Sharing)
// so that Chrome and Firefox and Safari etc. are able to pull the recent location.
try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $vehicleType = $_GET["vehicleType"];

    // Get only the most recent location information
    // Assuming that there's only one vehicle per type right now.
    $rows = $db->query("SELECT * FROM vehicles v JOIN location l
                              WHERE v.name LIKE '$vehicleType%' AND v.id = l.vehicle_ID
                              ORDER BY l.time DESC LIMIT 1;");

    // Put each row's data into the table
    foreach ($rows as $row) {
        $locationResult->id = $row["id"];
        $locationResult->name = $row["name"];
        $locationResult->operationalStatus = $row["operationalStatus"];
        $locationResult->latitude = $row["latitude"];
        $locationResult->longitude = $row["longitude"];
    }

    echo json_encode($locationResult);

} catch (PDOException $exception) {
    echo json_encode([]);
}

?>