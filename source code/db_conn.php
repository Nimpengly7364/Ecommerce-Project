<?php

$sname= "localhost";
$uname= "root";
$pass = "Peng7364";

$db_name = "ecom_web";

$conn = mysqli_connect($sname, $uname, $pass, $db_name);

if (!$conn) {
	echo "Connection failed!";
}