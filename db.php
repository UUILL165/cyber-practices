<?php
$host = "localhost";
$user = "your_mysql_username";
$pass = "you_mysql_pass";
$dbname = "users";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
