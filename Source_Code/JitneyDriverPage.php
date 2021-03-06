<?php
/**
 * Created by PhpStorm.
 * User: zhzhang
 * Date: 1/28/18
 * Time: 7:22 PM
 */

# The user's Colby ID would be stored in $username.
# Right now we don't know how to obtain it, so we keep this function disabled.
# Desirably, we would be able to use this ID to see if the user is a driver,
# and then decide if we should allow the user to view this page.
# More ideally, we should be able to check if the user is the only driver who
# is supposed to be driving the Jitney right now.
$username = "";
$isCurrentDriver = false;

$db = null;
try {
    $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # Obtain all entries in the current queue that has the username.
    $rows = $db->query("SELECT * FROM driver_list WHERE username = '$username';");

    # See if the user is one of the stored drivers.
    # Ideally we should also check if the user is the current driver.
    if ($rows->rowCount() !== 0) {
        $isCurrentDriver = true;
    }

} catch (PDOException $ex) {
    ?>
    <p>Error: <?= $ex->getMessage() ?></p>
    <p>Please contact Security Office for assistance.</p>
    <?php
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>STARRS for Colby -- Jitney driver page</title>
<!--    <meta name="viewport" content="initial-scale=1.0">-->
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    </script>
    <script src="logLocation.js"></script>
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
    <h1>Jitney Driver Request Handling Page</h1>
</div>

<div id="main">

    <div id="linksBanner">
        <div class="linkBlock">
            <a href="index.html">
                <span class="linkBlockText">Colby shuttle tracker</span></a>
        </div>
        <div class="linkBlock">
            <a href="JitneyUserPage.php">
                <span class="linkBlockText">Order Jitney pickup</span></a>
        </div>
        <div class="linkBlock">
            <a href="https://www.colby.edu/securitydept/colby-transportation-services">
                <span class="linkBlockText">Security office</span></a>
        </div>
    </div>

    <div id="map">

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXLCCaUcKU-3hW_63p_op4CnEg8axVZgY&callback=initMap"
                async defer></script>

    </div>

    <div id="onboard">
        <div class="sectionTitle">
            <h2>Requests Currently Being Processed</h2>
        </div>

        <table id="onboardQueue">
            <tr>
                <th id="onboardQueueLocation">Location</th>
                <th id="onboardQueueDestination">Destination</th>
                <th id="onboardQueuePassengers"># Ppl.</th>
                <th id="onboardQueueComment">Comments</th>
                <th id="onboardQueueTime">Request Time</th>
                <th id="onboardQueueAction">Action</th>
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
                        <td id="<?= $row['entryID'].$row['username']."dropoffLocation"
                        ?>"><?= $row["dropoffLocation"] ?></td>
                        <td><?= $row["numOfPassenger"] ?></td>
                        <td><?= $row["comments"] ?></td>
                        <td><?= $row["requestTime"] ?></td>
                        <td>
                            <form action="dropoffJitneyRequestDriver.php" method="post"
                                  class="requestDropoff"
                                  id="<?= $row['entryID'].$row['username']."Dropoff" ?>">
                                <input name="entryID" readonly type="hidden"
                                       value="<?= $row['entryID'] ?>" />
                                <input type="submit" value="Dropoff">
                            </form>
                        </td>
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
            <?php
            try {
                # Obtain all entries in the current queue and sort by ID.
                # Hopefully we don't allow anyone to mess up with IDs.
                $rows = $db->query('SELECT * FROM jitney_queue 
                                    WHERE entryID NOT IN 
                                        (SELECT queueID FROM jitney_current_request)
                                    ORDER BY entryID ASC;');

                # Keep track of whether the row has the earliest request.
                $earliest = "earliestRequest";

                # Each request occupies 4 rows, 2 of them for column titles.
                # The driver has the freedom to choose which one to pickup next
                # at their own discretion, though if the earliest one is not the chosen
                # one there will be some warnings.
                foreach ($rows as $row) {
                    ?>
                    <tr>
                        <th class="queueLocation">Location</th>
                        <th class="queueDestination">Destination</th>
                        <th class="queuePassengers"># Ppl.</th>
                        <th class="queueTime">Request time</th>
                    </tr>
                    <tr>
                        <td id="<?= $row['entryID'].$row['username']."pickupLocation"
                        ?>"><?= $row["pickupLocation"] ?></td>
                        <td><?= $row["dropoffLocation"] ?></td>
                        <td><?= $row["numOfPassenger"] ?></td>
                        <td><?= $row["requestTime"] ?></td>
                    </tr>
                    <tr>
                        <th class="queueComments" colspan="3">Comments</th>
                        <th class="queueAction">Action</th>
                    </tr>
                    <tr>
                        <td colspan="3"><?= $row["comments"] ?></td>
                        <td>
                            <form action="pickupJitneyRequestDriver.php" method="post"
                                  class="requestPickup <?= $earliest ?>"
                                  id="<?= $row['entryID'].$row['username']."Pickup" ?>">
                                <input name="entryID" readonly type="hidden"
                                       value="<?= $row['entryID'] ?>" />
                                <input type="submit" value="Pickup" />
                            </form>
                        </td>
                    </tr>
                    <?php
                    $earliest = "notEarliestRequest";
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

    <div id="scheduleRequestPage">
        <div class="sectionTitle">
            <h2><?= date('D')?>'s schedule</h2>
            <table id="dailySchedule">
            	<tr>
            		<th>Time</th>
            		<th>Schedule</th>
            	</tr>
            	
	
                <?php
                // Find a way to represent the schedule, and make a table here.
                date_default_timezone_set('US/Easter');
                $my_date = date('D');
                // echo $my_date;
                $arr = array(
                	0 => "Sun",
                	1 => "Mon",
                	2 => "Tue",
                	3 => "Wed",
                	4 => "Thu",
                	5 => "Fri",
                	6 => "Sat"
                );
                // echo $arr[$my_date];
                $myfile = fopen("daily_schedule.txt", "r") or die("Unable to open file!");
                $week = array();
                $int = 0;
                while(! feof($myfile)){
                	$week[$arr[$int]]= fgets($myfile);
                	$int ++;
                }
                fclose($myfile);  
               	//echo $week[$my_date];
                $daily = explode(" ",$week[$my_date]);
                $time = 1;
                $class = "none";
                foreach ($daily as $one){
                	$one = str_replace(' ','',$one);
                	$time = ($time + 1) % 12;
                	if ($one !== "None"){
                	
                		$class = "driver_shift";
                	} else {
                		$class = "none";
                		$one = '';
                    }
                ?>
                	<tr>
                		<td><?=$time?>:00</td>
                		<td class="<?=$class?>" ><?=$one?></td>
                	</tr>
                <?php	
                }
                
                ?>
            </table>
        </div>
    </div>

</div>

<!-- Useful links provided to the user below the map. -->
<div id="Links">
    <ul><span id="linkHeader">Useful Links:</span>
        <li><a href="https://www.colby.edu/securitydept/colby-transportation-services/">
            Colby Transportation Services (security office webpage)</a> </li>
        <li><a href="http://www.colby.edu/securitydept/wp-content/uploads/sites/151/2017/09/Downtown-Shuttle-Schedule-and-Map-Sept2017.pdf">
            Colby Shuttle Schedule and Map</a> </li>
        <li><a href="JitneySchedule.php">
            Jitney driver shifts</a></li>
        *The shuttle only runs from Monday to Friday during school sessions.
    </ul>
</div>

<!--bottom of the page-->
<div id="footer">
    <a href="https://www.colby.edu/">
        <img src="images/colbybanner1.jpg" class="banner1" ALT="Colby Banner" height="80"/>
    </a>
    <a href="https://www.colby.edu/">
        <img src="images/colbybanner2.jpg" class="banner2" ALT="Colby Banner"  >
    </a>
</div>

</body>
</html>
