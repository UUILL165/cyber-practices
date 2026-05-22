<?php
$host = "localhost";
$user = "banhmi";
$pass = "khiem123";
$dbname = "users";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
