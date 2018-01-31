<?php
/**
 * Created by PhpStorm.
 * User: zhzhang
 * Date: 1/27/18
 * Time: 2:34 PM
 */

#If there's already one request issued by the user stored in the database,
#then the following code would try to find it out and change the variable to true.
#Users who has issued one existing request should not be allowed to request a second one.
$alreadyRequested = false;
# The user's Colby ID would be stored in $username.
# Right now we don't know how to obtain it, so we keep this function disabled.
$username = "mulecolby17";

try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # Obtain all entries in the current queue that has the username.
    $rows = $db->query("SELECT * FROM jitney_queue WHERE username = '$username';");

    # See if there's any entry
    if ($rows->rowCount() !== 0) {
        $alreadyRequested = true;
    }

} catch (PDOException $ex) {
    ?>
    <p>Error: <?= $ex->getMessage() ?></p>
    <p>Please try later.</p>
    <?php
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>STARRS for Colby -- Jitney request page</title>
<!--    <meta name="viewport" content="initial-scale=1.0">-->
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    </script>
<!--    <script type="text/javascript" src="GoogleMapsVariables.js"></script>-->
    <script src="JitneyUserPage.js"></script>
    <script src="pulLocation.js"></script>
    <script src="JitneyGoogleMaps.js"></script>
    <link rel="stylesheet"
          href="index.css<?php echo "?".time(); //To avoid server from caching CSS ?>"
          type="text/css" />
</head>
<body>

<div id="header">
    <a href="https://www.colby.edu/">
        <img src="images/colbybanner1.jpg" class="banner1" ALT="Colby Banner"/>
    </a>
    <div id="StarrsTitleContainer">
        <h1 id="StarrsTitle">STARRS - Shuttle Tracker And Ride Request Service</h1>
    </div>
    <a href="https://www.colby.edu/">
        <img src="images/colbybanner2.jpg" class="banner2" ALT="Colby Banner"  >
    </a>
</div>

<div id="pageTitle">
    <h1>Jitney Tracker and Request Page</h1>
</div>

<div id="main">

    <div id="linksBanner">
        <div class="linkBlock">
            <a href="index.html">
                <span class="linkBlockText">Colby Shuttle Tracker</span></a>
        </div>
        <div class="linkBlock">
            <a href="JitneyUserPage.php">
                <span class="linkBlockText">Order Jitney Pickup</span></a>
        </div>
        <div class="linkBlock">
            <a href="https://www.colby.edu/securitydept/colby-transportation-services">
                <span class="linkBlockText">Security Office</span></a>
        </div>
    </div>

    <div id="map">

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXLCCaUcKU-3hW_63p_op4CnEg8axVZgY&callback=initMap"
                async defer></script>

    </div>

    <div id="onboard">
        <div class="sectionTitle">
            <h2>Riders on Jitney right now</h2>
        </div>

        <table id="onboardQueue">
            <tr>
                <th id="onboardQueueLocation">Location</th>
                <th id="onboardQueueDestination">Destination</th>
                <th id="onboardQueuePassengers"># Ppl.</th>
            </tr>
            <?php
            try {
                # Obtain all entries in the current queue
                $rows = $db->query('SELECT * FROM jitney_current_request c
                    JOIN jitney_queue q ON c.queueID = q.entryID
                    ORDER BY c.pickupID ASC;');

                # Put each request into a row. Each comes with one button for dropoff.
                # I think the current approach might have some security issues...
                foreach ($rows as $row) {
                    ?>
                    <tr>
                        <td><?= $row["pickupLocation"] ?></td>
                        <td><?= $row["dropoffLocation"] ?></td>
                        <td><?= $row["numOfPassenger"] ?></td>
                    </tr>
                    <?php
                }

            } catch (PDOException $ex) {
                ?>
                <p>Error: <?= $ex->getMessage() ?></p>
                <p>Please call Security Office when you need to.</p>
                <?php
            }

            ?>
        </table>
    </div>

    <div id="queue">
        <div class="sectionTitle">
            <h2>Current Jitney request queue</h2>
        </div>

        <table id="requestQueue">
            <tr>
                <th class="queueLocation">Location</th>
                <th class="queueDestination">Destination</th>
                <th class="queuePassengers"># Ppl.</th>
                <th class="queueTime">Request time</th>
                <th class="queueAction">Action</th>
            </tr>
            <?php
            try {
                # Obtain all entries in the current queue and sort by ID.
                # Hopefully we don't allow anyone to mess up with IDs.
                # Update 01/29: Filtered out all entries that are in the current request
                $rows = $db->query('SELECT * FROM jitney_queue 
                                    WHERE entryID NOT IN 
                                        (SELECT queueID FROM jitney_current_request)
                                    ORDER BY entryID ASC;');

                # Put each comment into a comment box division, and help form the website.
                foreach ($rows as $row) {
                    ?>
                    <tr>
<!--            Add IDs for each row's locations to make it easy for confirmation.
The ID starts with the entryID of request, the user name, and then a keyword. -->
                        <td id="<?= $row['entryID'].$username."pickup"
                        ?>"><?= $row["pickupLocation"] ?></td>
                        <td id="<?= $row['entryID'].$username."dropoff"
                        ?>"><?= $row["dropoffLocation"] ?></td>
                        <td><?= $row["numOfPassenger"] ?></td>
                        <td><?= $row["requestTime"] ?></td>
                        <td><?php
                            if ($row["username"] === $username) {
                                ?>
<!--            Submit the username and entryID along with the request.-->
                                <form action="cancelRequest.php" method="post"
                                      class="requestCancel"
                                      id="<?= $row['entryID'].$username."cancel" ?>">
                                    <input name="entryID" readonly type="hidden"
                                           value="<?= $row['entryID'] ?>" />
                                    <input name="username" readonly type="hidden"
                                           value="<?= $username ?>" />
                                    <input type="submit" value="Cancel">
                                </form>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }

            } catch (PDOException $ex) {
                ?>
                <p>Error: <?= $ex->getMessage() ?></p>
                <p>Please try later.</p>
                <?php
            }

            ?>
        </table>
    </div>

    <div id="requestArea">
        <div class="sectionTitle">
            <h2>Order Jitney request</h2>
        </div>

        <div id="requestForm">
            <form action="submitJitneyRequest.php" method="post" id="userSubmitRequest">
                <label><p><span class="requestPrompt">Enter pickup location:</span>
                        <textarea rows="4" cols="60" name="pickup" maxlength="256"
                                  id="pickupText" class="requestText"
                                  required
                                  placeholder="E.g. Pugh Center, Flagship Cinema"></textarea>
                        <br><span class="textboxCounter">
                            <span id="pickupCharLimit">0</span>/256
                        </span>
                    </p></label><br>

                <label><p><span class="requestPrompt">Enter dropoff location:</span>
                        <textarea rows="4" cols="60" name="dropoff" maxlength="256"
                                  id="dropoffText" class="requestText"
                                  placeholder="E.g. Walmart, Opera House"></textarea>
                        <br><span class="textboxCounter">
                            <span id="dropoffCharLimit">0</span>/256
                        </span>
                    </p></label><br>

                <label><p class="requestPrompt">How many people are traveling?
                        <select name="number">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                        </select>
                    </p></label><br>

                <label><p><span class="requestPrompt">Additional comments:</span>
                        <textarea rows="6" cols="60" name="comment" maxlength="400"
                                  placeholder="E.g. Inconveniences, detailed location"
                                  id="commentText" class="requestText"></textarea>
                        <br><span class="textboxCounter">
                            <span id="commentCharLimit">0</span>/400
                        </span>
                    </p></label><br>
                <input name="username" readonly type="hidden"
                       value="<?= $username ?>" />

                <div id="requestButtons">
                <?php
                if ($alreadyRequested) {
                    ?>
                    <p class="warning">You already have an existing request.</p>
                    <input type="submit" value="Submit" disabled="disabled"/>
                <?php
                } else {
                    ?>
                    <input type="submit" value="Submit" id="submitRequest"/>
                <?php
                }
                ?>
                <input type="reset" />
                </div>
            </form>
        </div>
    </div>

    <?php
    include 'getJitneySchedule.php';
    ?>


</div>

<?php
include 'footer.html';
?>

</body>
</html>
