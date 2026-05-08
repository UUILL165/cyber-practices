<?php
session_start();
include "db.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

if (isset($_GET["owner"])) {
    $owner = $_GET["owner"];

    $sql = "SELECT username, fullname, description FROM account WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $owner);
} else {
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT username, fullname, description FROM account WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Profile owner not found.");
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
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

        .profile-content {
            background: #f0fbff;
            padding: 18px;
            border-radius: 10px;
            min-height: 100px;
            border-left: 5px solid #00a8e8;
        }
    </style>
</head>
<body>

<?php include "menubar.php"; ?>

<div class="container">
    <h1>Profile Page</h1>

    <p><strong>Owner:</strong> <?php echo htmlspecialchars($user["username"]); ?></p>
    <p><strong>Full name:</strong> <?php echo htmlspecialchars($user["fullname"]); ?></p>

    <h2>Profile Content</h2>

    <div class="profile-content">
        <?php
        if (empty($user["description"])) {
            echo "This user has no profile content yet.";
        } else {
            echo $user["description"];
        }
        ?>
    </div>
</div>

</body>
</html>
