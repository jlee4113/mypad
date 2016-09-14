<?php
// this is a test
include 'connect.php';
$email = $_GET["email"];

$select = mysqli_query($conn, "SELECT * FROM users WHERE email ='$email'") or exit(mysql_error());

if(mysqli_num_rows($select))
    exit("true");
?>