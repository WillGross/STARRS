<div id="scheduleJitneyPage">
    <div class="sectionTitle">
        <h2>Jitney schedule for today (<?= date('l')?>)</h2>
    </div>
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
