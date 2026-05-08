<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>About</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7ff;
            margin: 0;
        }

        .menubar {
            background: #0077b6;
            padding: 15px;
            text-align: center;
        }

        .menubar a {
            color: white;
            text-decoration: none;
            margin: 0 14px;
            font-weight: bold;
        }

        .container {
            width: 500px;
            margin: 70px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
            text-align: center;
        }

        h1 {
            color: #0077b6;
        }
    </style>
</head>
<body>

<?php include "menubar.php"; ?>

<div class="container">
    <h1>About Page</h1>

    <p><strong>Student Name:</strong> Luong The Khiem</p>
    <p><strong>Student Number:</strong> Student ID: 1695553</p>
</div>

</body>
</html>
