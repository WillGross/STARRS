<?php
/**
 * Created by PhpStorm.
 * User: zhzhang
 * Date: 1/24/18
 * Time: 3:06 PM
 */
$shiftContents = file('driver_shift_file.txt', FILE_IGNORE_NEW_LINES);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Jitney Time Schedule</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
        </script>
        <script src="JitneySchedule.js"></script>
        <link rel="stylesheet"
              href="JitneySchedule.css"
              type="text/css" />
    </head>
    <body>
    <table id="schedule" border="1">
        <!-- Horizontal header -->
        <tr>
            <th></th>
            <th>Sunday</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
        </tr>
        <?php
        $timeOfRow = 7;
        $amIndicator = 1;
        foreach ($shiftContents as $line) {
            $hours = explode(', ', $line);
            $timeOfRow += 1;
            ?>
<!--        Add the first column where we put the hour.-->
            <tr><th><br>
            <?= $amIndicator === 0 ? "<em>".$timeOfRow.":00</em>" : $timeOfRow.":00" ?>
            <br><br></th>
            <?php
            # Alternate the A.M./P.M. indicator value once we reached 12 o'clock.
            if ($timeOfRow === 12) {
                $amIndicator = 1-$amIndicator;
                $timeOfRow -= 12;
            }

            # Produce each row, slot-by-slot
            foreach ($hours as $slot) {
                ?>
                <td> <?= $slot ?> </td>
                <?php
            }
        }
        ?>
    </table>
    </body>
</html>