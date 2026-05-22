<?php
session_start();
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM account WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["fullname"] = $user["fullname"];

            header("Location: index.php");
            exit();
        } else {
            $message = "Wrong password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7ff;
        }

        .box {
            width: 400px;
            margin: 90px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        h1 {
            color: #0077b6;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #90e0ef;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #00a8e8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        button:hover {
            background: #0077b6;
        }

        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Sign In</h1>

    <p class="message"><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username">

        <label>Password:</label>
        <input type="password" name="password">

        <button type="submit">Sign In</button>
    </form>
</div>

</body>
</html>
