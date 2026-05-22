<?php
include "../socialnet/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $fullname = trim($_POST["fullname"]);
    $password = $_POST["password"];

    if (empty($username) || empty($fullname) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO account (username, fullname, password, description)
                VALUES (?, ?, ?, '')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $fullname, $hashed_password);

        if ($stmt->execute()) {
            $message = "New user created successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - New User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7ff;
        }

        .box {
            width: 400px;
            margin: 80px auto;
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
            color: #0077b6;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Create User</h1>

    <p class="message"><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username">

        <label>Full name:</label>
        <input type="text" name="fullname">

        <label>Password:</label>
        <input type="password" name="password">

        <button type="submit">Create User</button>
    </form>

    <p style="text-align:center;">
        <a href="../socialnet/signin.php">Go to Sign In</a>
    </p>
</div>

</body>
</html>
