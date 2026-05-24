<?php
$host = "localhost";
$user = "your_username"; //replace this value with your own
$pass = "your_password"; //replace this value with your own
$dbname = "users";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
