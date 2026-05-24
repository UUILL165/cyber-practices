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

        .card {
            background: #f0fbff;
            padding: 18px;
            border-radius: 10px;
            border-left: 5px solid #00a8e8;
            margin-top: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #d9edf7;
        }

        th {
            background: #e8f7ff;
            color: #0077b6;
        }
    </style>
</head>
<body>

<?php include "menubar.php"; ?>

<div class="container">
    <h1>Home Page</h1>

    <div class="card">
        <h2>Your Information</h2>
        <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION["user_id"]); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
        <p><strong>Full name:</strong> <?php echo htmlspecialchars($_SESSION["fullname"]); ?></p>
    </div>

    <div class="card">
        <h2>Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
            </tr>

            <?php
            $users_sql = "SELECT id, username FROM account ORDER BY id ASC";
            $users_result = $conn->query($users_sql);

            if ($users_result && $users_result->num_rows > 0) {
                while ($row = $users_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No users found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
