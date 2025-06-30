<?php
// Assuming you have a session started and a way to determine user_type
session_start();

if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['super_admin'])) {
    header("Location: login.php");
    exit();
}

$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: right;
        }
        .header .user-info {
            float: left;
        }
        .header a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            flex-direction: column;
        }
        .option {
            background-color: #fff;
            padding: 20px;
            margin: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 200px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .option:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="user-info">
            <span>Role: <?php echo htmlspecialchars($user_type); ?></span>
        </div>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <div class="option" onclick="window.location.href='view_bookings.php'">
            See Yatra Bookings
        </div>
        <div class="option" onclick="window.location.href='view_orders.php'">
            See Hyperlocal Orders
        </div>
        <div class="option" onclick="window.location.href='view_users.php'">
           Users
        </div>
    </div>

</body>
</html>
