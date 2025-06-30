<?php
session_start();
include 'database.php';
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

// Database connection

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user information
$user = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$userInfo = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="css/home.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            margin-top: 80px;
        }
        h1 {
            font-family: 'Josefin Sans', sans-serif;
            font-weight: 700;
            color: #f09e05;
            margin-bottom: 30px;
        }
        .profile-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .profile-info {
            text-align: left;
            margin-top: 20px;
        }
        .profile-info .info-label {
            font-weight: 700;
            color: #f09e05;
        }
        .profile-info p {
            font-size: 0.9rem;
        }
        .btn-custom {
            background-color: #f09e05;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-custom:hover {
            background-color: #ff9000;
        }
        .navbar-fixed-bottom {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1030;
        }
        .address {
            display: inline;
        }
        .read-more {
            color: #f09e05;
            cursor: pointer;
            text-decoration: underline;
        }
        .hidden-address {
            display: none;
        }
        @media (max-width: 768px) {
            .profile-card {
                padding: 15px;
            }
            .profile-avatar {
                width: 80px;
                height: 80px;
            }
            .profile-info p {
                font-size: 0.8rem;
            }
        }
    </style>
    <script>
        function toggleAddress() {
            const fullAddress = document.getElementById('full-address');
            const hiddenAddress = document.getElementById('hidden-address');
            const readMore = document.getElementById('read-more');

            if (hiddenAddress.style.display === 'none') {
                hiddenAddress.style.display = 'inline';
                readMore.innerHTML = 'Read less';
            } else {
                hiddenAddress.style.display = 'none';
                readMore.innerHTML = 'Read more';
            }
        }
    </script>
</head>
<body>  
    <header>
    <?php
      include 'top_navbar.php';
     ?>
    </header>

    <div class="container">
        <h1>User Profile</h1>
        <div class="profile-card">
            <img src="images/avatar.jpg" alt="Profile Avatar" class="profile-avatar">
            <h2><?php echo htmlspecialchars($userInfo['name']); ?></h2>
            <div class="profile-info">
                <p><span class="info-label">Username:</span> <?php echo htmlspecialchars($userInfo['username']); ?></p>
                <p><span class="info-label">Email:</span> <?php echo htmlspecialchars($userInfo['email']); ?></p>
                <p><span class="info-label">Phone:</span> <?php echo htmlspecialchars($userInfo['phone']); ?></p>
                <p><span class="info-label">Address:</span> <span id="full-address"><?php echo htmlspecialchars(substr($userInfo['address'], 0, 50)); ?></span><span id="hidden-address" class="hidden-address"><?php echo htmlspecialchars(substr($userInfo['address'], 50)); ?></span> <span id="read-more" class="read-more" onclick="toggleAddress()">Read more</span></p>
            </div>
            <a href="my_hyperlocal_orders.php" class="btn-custom">View Hyperlocal Orders</a>
            <a href="my_yatra_bookings.php" class="btn-custom">View Yatra Orders</a>
            <a href="logout.php" class="btn-custom" style="background-color: #dc3545; color: white;">Logout</a>
        </div>
        <div class="profile-card mt-4">
            <h3>Change Password</h3>
            <form action="change_password.php" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password:</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required>
                </div>
                <button type="submit" class="btn-custom">Change Password</button>
            </form>
        </div>
    </div>
    <br><br><br>

    <?php
      include 'bottom_navbar.php';
     ?>
</body>
</html>
