<?php
/**
 * JitneyDispatcherPage -- Only accessed by the dispatcher.
 * Functionalities include adding a request, canceling a request, seeing current Jitney
 * location, checking current queue and previous recent request histories.
 * User: zhzhang
 * Date: 1/30/18
 * Time: 4:09 PM
 */

# The dispatcher's ID?
$username = "admin";

?>

<!DOCTYPE html>
<html>
<head>
    <title>STARRS for Colby -- Jitney managing page</title>
    <!--    <meta name="viewport" content="initial-scale=1.0">-->
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    </script>
    <!--    <script type="text/javascript" src="GoogleMapsVariables.js"></script>-->
    <script src="JitneyUserPage.js"></script>
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
    <h1>Jitney Dispatcher Page</h1>
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
            <h2>Passengers on the Jitney right now</h2>
        </div>

        <table id="onboardQueue">
            <tr>
                <th id="onboardQueueLocation">Location</th>
                <th id="onboardQueueDestination">Destination</th>
                <th id="onboardQueuePassengers"># Ppl.</th>
                <th id="onboardQueueComment">Comments</th>
                <th id="onboardQueueTime">Request Time</th>
            </tr>
            <?php
            try {
                $db = new PDO("mysql:dbname=starrs;host=localhost", "starrs", "Wher3Bus@?");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                        <td><?= $row["comments"] ?></td>
                        <td><?= $row["requestTime"] ?></td>
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

                # Put each comment into a comment box division, and help form the website.
                foreach ($rows as $row) {
                    ?>
                    <tr>
                        <th class="queueLocation">Location</th>
                        <th class="queueDestination">Destination</th>
                        <th class="queuePassengers"># Ppl.</th>
                        <th class="queueTime">Request time</th>
                    </tr>
                    <tr>
<!--            Add IDs for each row's locations to make it easy for confirmation.
The ID starts with the entryID of request, the user name, and then a keyword. -->
                        <td id="<?= $row['entryID'].$username."pickup"
                        ?>"><?= $row["pickupLocation"] ?></td>
                        <td id="<?= $row['entryID'].$username."dropoff"
                        ?>"><?= $row["dropoffLocation"] ?></td>
                        <td><?= $row["numOfPassenger"] ?></td>
                        <td><?= $row["requestTime"] ?></td>
                    </tr>
                    <tr>
                        <th class="queueComments" colspan="3">Comments</th>
                        <th class="queueAction">Action</th>
                    </tr>
                    <tr>
                        <td colspan="3"><br>
                            <strong>Request issuer:
                                <span class="appearedUser"><?= $row["username"] ?></span>
                            </strong><br>
                            <?= $row["comments"] ?></td>
                        <td>
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
            <h2>Order Jitney Request</h2>
        </div>

        <div id="requestForm">
            <form action="submitJitneyRequest.php" method="post">
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

                <label><p><span class="requestPrompt">Caller's myColby username:</span>
                        <textarea rows="1" cols="60" name="username" maxlength="16"
                                  id="usernameText" class="requestText" required
                                  placeholder="E.g. djskrien"></textarea>
                        <br><span class="textboxCounter">
                            <span id="usernameCharLimit">0</span>/16
                        </span>
                    </p></label><br>


                <div id="requestButtons">
                    <input type="submit" value="Submit" id="submitRequest"/>
                    <input type="reset" />
                </div>
            </form>
        </div>
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
                Colby Transportation Services (security office webpage)</a></li>
        <li><a href="http://www.colby.edu/securitydept/wp-content/uploads/sites/151/2017/09/Downtown-Shuttle-Schedule-and-Map-Sept2017.pdf">
                Colby Shuttle Schedule and Map</a></li>
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
