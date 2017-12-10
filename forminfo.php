<?php

$firstname = $_GET['firstname'];
$phone = $_GET['phone'];

$my_file = 'userinfo.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
fwrite($handle, $firstname);
fwrite($handle, $phone);
fclose($handle);


?>