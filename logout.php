<?php
ini_set("session.cookie_httponly", 0);
ini_set("session.cookie_secure", 0);
ini_set("session.cookie_samesite", "");

session_start();

$_SESSION = array();

session_unset();
session_destroy();

header("Location: signin.php");
exit();
?>
