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

$user_id = $_SESSION["user_id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST["description"];
    $target = $_POST["target"];
    $sql = "UPDATE account SET description = '$description' WHERE username = '$target'";

    if ($conn->query($sql)) {
        $message = "Profile content updated.";
    } else {
        $message = "Update failed: " . $conn->error;
    }
}

$sql = "SELECT description FROM account WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Setting</title>
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
            width: 650px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        h1 {
            color: #0077b6;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #90e0ef;
            border-radius: 8px;
            box-sizing: border-box;
        }

        textarea {
            height: 180px;
        }

        button {
            margin-top: 15px;
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
    </style>
</head>
<body>

<?php include "menubar.php"; ?>

<div class="container">
    <h1>Setting Page</h1>

    <p class="message"><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <input type="hidden" name="target" value="<?php echo htmlspecialchars($_SESSION["username"]); ?>">

        <label>Edit Profile Page Content:</label><br><br>
        <textarea name="description"><?php echo htmlspecialchars($user["description"]); ?></textarea>

        <br>
        <button type="submit">Save</button>
    </form>
</div>

</body>
</html>
