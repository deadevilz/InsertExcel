<?php

$hostname = "localhost";
$username = "root";
$password = "deadsiter";
$db_name = "slayer_project";

$objConnect = mysql_connect($hostname,$username,$password) or die (mysql_error());
mysql_select_db($db_name,$objConnect)or die (mysql_error());

//echo "completed";



?>