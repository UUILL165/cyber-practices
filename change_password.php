<?php
ini_set("session.cookie_httponly", 0);
ini_set("session.cookie_secure", 0);
ini_set("session.cookie_samesite", "");

session_start();
include "db.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_user_id = $_POST["user_id"];
    $new_password = $_POST["new_password"];

    if ($new_password === "") {
        $message = "New password cannot be empty.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE account SET password = '$hashed' WHERE id = $target_user_id";

        if ($conn->query($sql)) {
            $message = "Password changed.";
        } else {
            $message = "Password change failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
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
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        h1 {
            color: #0077b6;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #90e0ef;
            border-radius: 8px;
            box-sizing: border-box;
            margin-bottom: 16px;
        }

        button {
            padding: 12px 18px;
            background: #00a8e8;
            color: white;
            border: none;
            border-radius: 8px;
        }

        button:hover {
            background: #0077b6;
        }

        .message {
            color: green;
        }

        .hint {
            color: #555;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php include "menubar.php"; ?>

<div class="container">
    <h1>Change Password</h1>

    <p class="message"><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <label>Target user ID:</label><br>
        <input type="text" name="user_id" value="<?php echo htmlspecialchars($_SESSION["user_id"]); ?>">
        <p class="hint">Vulnerable lab field. Changing this allows password changes for another account.</p>

        <label>New password:</label><br>
        <input type="password" name="new_password">

        <button type="submit">Change Password</button>
    </form>
</div>

</body>
</html>
