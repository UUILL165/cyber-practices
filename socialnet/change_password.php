<?php
ini_set("session.cookie_httponly", 1);
ini_set("session.cookie_samesite", "Lax");
ini_set("session.cookie_secure", 0);

session_start();
include "db.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

$message = "";
$user_id = $_SESSION["user_id"];

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

function verify_current_password($conn, $user_id, $current_password) {
    $sql = "SELECT password FROM account WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        return false;
    }

    $user = $result->fetch_assoc();
    return password_verify($current_password, $user["password"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF check
    if (
        !isset($_POST["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
    ) {
        http_response_code(403);
        exit("Invalid CSRF token.");
    }

    $current_password = $_POST["current_password"] ?? "";

    if (!verify_current_password($conn, $user_id, $current_password)) {
        $message = "Current password is incorrect.";
    } else {
        // Change password action
        if (isset($_POST["change_password"])) {
            $new_password = $_POST["new_password"] ?? "";

            if ($new_password === "") {
                $message = "New password cannot be empty.";
            } else {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);

                $sql = "UPDATE account SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $hashed, $user_id);

                if ($stmt->execute()) {
                    session_regenerate_id(true);
                    $message = "Password changed.";
                } else {
                    $message = "Password change failed.";
                }
            }
        }

        // Delete account action
        if (isset($_POST["delete_account"])) {
            $confirm_delete = $_POST["confirm_delete"] ?? "";

            if ($confirm_delete !== "DELETE") {
                $message = "To delete your account, type DELETE.";
            } else {
                $sql = "DELETE FROM account WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);

                if ($stmt->execute()) {
                    session_unset();
                    session_destroy();

                    header("Location: signin.php");
                    exit();
                } else {
                    $message = "Delete failed.";
                }
            }
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

        h1, h3 {
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

        .delete-button {
            background: #d90429;
        }

        .delete-button:hover {
            background: #9d0208;
        }

        .message {
            color: green;
        }

        .danger-zone {
            width: 500px;
            margin: 20px auto 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
            border: 2px solid #d90429;
        }
    </style>
</head>
<body>

<?php include "menubar.php"; ?>

<div class="container">
    <h1>Change Password</h1>

    <p class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8"); ?>">

        <label>Current password:</label><br>
        <input type="password" name="current_password" required>

        <label>New password:</label><br>
        <input type="password" name="new_password" required>

        <button type="submit" name="change_password" value="1">Change Password</button>
    </form>
</div>

<div class="danger-zone">
    <h3>Danger Zone</h3>
    <p>Delete your account permanently.</p>
    <p>To confirm deletion, type <strong>DELETE</strong> and enter your current password.</p>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8"); ?>">

        <label>Current password:</label><br>
        <input type="password" name="current_password" required>

        <label>Type DELETE to confirm:</label><br>
        <input type="text" name="confirm_delete" required>

        <button class="delete-button" type="submit" name="delete_account" value="1">
            Delete Account
        </button>
    </form>
</div>

</body>
</html>
