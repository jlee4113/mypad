<?php
$servername = "aalru9jxj1267s.cjq8tyagmhjm.us-west-2.rds.amazonaws.com";
$username = "revAdmin";
$password = "RevTech1";
$db = "mypad";
$port = "3306";

// Create connection
$con = new mysqli("$servername", "$username", "$password", "$db", "$port") or die(mysql_error());

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
  



