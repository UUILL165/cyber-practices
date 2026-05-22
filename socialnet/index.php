<?php
session_start();
include "db.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

$current_user_id = $_SESSION["user_id"];

$sql = "SELECT id, username, fullname FROM account WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
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

        .menubar a:hover {
            text-decoration: underline;
        }

        .signout {
            color: #ffd6d6 !important;
        }

        .container {
            width: 750px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        h1, h2 {
            color: #0077b6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #00a8e8;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .btn {
            background: #00a8e8;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn:hover {
            background: #0077b6;
        }
    </style>
</head>
<body>

<?php include "menubar.php"; ?>

<div class="container">
    <h1>Home Page</h1>
    <h2>Your Information</h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
    <p><strong>Full name:</strong> <?php echo htmlspecialchars($_SESSION["fullname"]); ?></p>

    <h2>Other Users</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Profile</th>
        </tr>

        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user["id"]); ?></td>
                <td><?php echo htmlspecialchars($user["username"]); ?></td>
                <td><?php echo htmlspecialchars($user["fullname"]); ?></td>
                <td>
                    <a class="btn" href="profile.php?owner=<?php echo urlencode($user["username"]); ?>">
                        View Profile
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
