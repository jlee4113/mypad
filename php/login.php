<?php
include 'connect.php';

$email = $_POST['email'];
$pass = $_POST['password'];

$sql = "SELECT '*' FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
echo $result;
$rows = mysqli_num_rows($result);

if ($rows = 1) {
    echo 1;
} else {

    echo 0;
}
?>