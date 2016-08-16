<?php
include 'connect.php';
$email = $_POST["email"];
$pass = $_POST["password"];

$sql = "INSERT INTO users (email, pass) VALUES ('$email','$pass')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>