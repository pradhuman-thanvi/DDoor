<?php
// Start the session
session_start();
include 'database.php';
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if not logged in
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

// Fetch orders for the logged-in user by username, ordered by id in descending order
$stmt = $conn->prepare("SELECT * FROM booking_yatra WHERE username = ? ORDER BY id DESC");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="css/home.css">
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
        .ticket {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .ticket-header {
            background-color: #f09e05;
            color: white;
            padding: 10px 15px;
            border-radius: 15px 15px 0 0;
            font-size: 18px;
        }
        .ticket-body {
            padding: 15px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .col-6 {
            width: 50%;
            padding: 5px;
        }
        .col-6 strong {
            display: inline-block;
            width: 120px;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            color: #5f6368;
        }
        .nav-link.active {
            color: #f09e05;
        }
        .navicon {
            height: 34px;
            width: 34px;
        }
        .navbar-nav .nav-item {
            text-align: center;
        }
        .navbar-nav .nav-link {
            display: inline-block;
            padding: 10px;
        }
        .navbar-fixed-bottom {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1030;
        }
        body{
     -webkit-user-select: none; /* Safari */
  -ms-user-select: none; /* IE 10 and IE 11 */
  user-select: none; /* Standard syntax */
  
}
::-webkit-scrollbar {
    display: none;
}
.container {
    overflow-x: scroll; /* For horiz. scroll, otherwise overflow-y: scroll; */

    -ms-overflow-style: none;
    overflow: -moz-scrollbars-none;
    scrollbar-width: none;
}


.container::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
}
    </style>
</head>
<body>
    <header>
      <nav class="navbar fixed-top rounded-bottom-3 bg-white shadow">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="images/logo.png" class="navimg" alt="Logo" width="47" height="47">
          </a>
          <form class="d-flex" role="search" style="width: 70%;">
            <input class="form-control me-1" type="search" placeholder="Search Your Thing" aria-label="Search">
            <button class="btn btn-outline-success bg-warning" style="border: none; color: white;" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </div>
      </nav>
    </header>
<br>
    <div class="container">
        <h1>My Bookings Yatra</h1>

        <?php if (!empty($orders)) : ?>
            <?php foreach ($orders as $order) : ?>
                <div class="ticket">
                    <div class="ticket-header">
                        Order #DD-PH-YT-<?php echo $order['id']; ?>
                    </div>
                    <div class="ticket-body">
                        <div class="row">
                            <div class="col-6"><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></div>
                            <div class="col-6"><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Pickup:</strong> <?php echo htmlspecialchars($order['pickup']); ?></div>
                            <div class="col-6"><strong>Dropoff:</strong> <?php echo htmlspecialchars($order['dropoff']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><strong>Date:</strong> <?php echo htmlspecialchars($order['date']); ?></div>
                            <div class="col-6"><strong>Time:</strong> <?php echo htmlspecialchars($order['time']); ?></div>
</div>
<div class="row">
    <div class="col-6"><strong>Status:</strong> <?php echo isset($order['status']) ? htmlspecialchars($order['status']) : ''; ?>
</div>
    <div class="col-6"><strong>Accepted By:</strong> <?php echo htmlspecialchars($order['acceptor_name']); ?></div>
</div>
</div>
</div>
<?php endforeach; ?>
<?php else : ?>
<div class="alert alert-warning" role="alert">You have no Bookings.</div>
<?php endif; ?>
</div>

<nav class="navbar navbar-expand navbar bg-white text-white fixed-bottom rounded-top-5 shadow-lg" style="top: 92%;">
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a href="home.php" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="navicon" height="34px" viewBox="0 -960 960 960" width="34px" fill="#5f6368">
                    <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/>
                </svg>
            </a>
        </li>
        <li class="nav-item">
            <a href="hyperlocal.php" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="navicon2" height="34px" viewBox="0 -960 960 960" width="34px" fill="#5f6368">
                    <path d="M440-183v-274L200-596v274l240 139Zm80 0 240-139v-274L520-457v274Zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11Zm200-528 77-44-237-137-78 45 238 136Zm-160 93 78-45-237-137-78 45 237 137Z"/>
                </svg>
            </a>
        </li>
        <li class="nav-item">
            <a href="booking.php" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="navicon1" height="40px" viewBox="0 -960 960 960" width="40px" fill="#5f6368">
                    <path d="M231.94-280q-41.06 0-73.16-24.83-32.11-24.84-42.78-64.5h-9.33q-27.5 0-47.09-19.59Q40-408.5 40-436v-337.33q0-27.5 19.58-47.09Q79.17-840 106.67-840h503.2q18.8 0 36.13 6.33 17.33 6.34 28.67 20.34l154 194.66q8.33 11 11.83 24.17 3.5 13.17 3.5 27.17V-514q34.33 14 55.17 44.68Q920-438.65 920-400.67 920-350 885-315t-85.67 35q-41 0-73.06-25.13-32.06-25.12-44.27-64.2H348.67q-12.67 39-44.17 64.16Q273-280 231.94-280ZM106.67-638.67H286v-134.66H106.67v134.66Zm246 202.67h224v-337.33h-224v134.66h134V-572h-134v136Zm290.66-158H760L643.33-742v148Zm-411.3 247.33q22.97 0 38.47-15.53 15.5-15.54 15.5-38.5 0-22.97-15.54-38.47-15.53-15.5-38.5-15.5-22.96 0-38.46 15.54-15.5 15.53-15.5 38.5 0 22.96 15.53 38.46 15.54 15.5 38.5 15.5Zm567.34 0q22.96 0 38.46-15.53 15.5-15.54 15.5-38.5 0-22.97-15.53-38.47-15.54-15.5-38.5-15.5-22.97 0-38.47 15.54-15.5 15.53-15.5 38.5 0 22.96 15.54 38.46 15.53 15.5 38.5 15.5ZM518-40 281.33-160.67H442v-76l236.67 116H518V-40ZM106.67-572v136h10q12-37 43.83-61.17 31.83-24.16 71.5-24.16 14.85 0 28.35 3.83 13.5 3.83 25.65 11.5v-66H106.67Zm536.66 136h40q10.34-32.67 35.5-55.5 25.17-22.83 58.5-25.83v-10h-134V-436ZM106.67-572H286 106.67Zm536.66 44.67h134-134Z"/>
                </svg>
            </a>
        </li>
        <li class="nav-item">
            <a href="maintenance.html" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="navicon3" height="
                34px" viewBox="0 -960 960 960" width="34px" fill="#5f6368">
                    <path d="M173-600h614l-34-120H208l-35 120Zm307-60Zm192 140H289l-11 80h404l-10-80ZM160-160l49-360h-89q-20 0-31.5-16T82-571l57-200q4-13 14-21t24-8h606q14 0 24 8t14 21l57 200q5 19-6.5 35T840-520h-88l48 360h-80l-27-200H267l-27 200h-80Z"/>
                </svg>
            </a>
        </li>
        <li class="nav-item">
            <a href="profile.php" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="navicon4 active1" height="34px" viewBox="0 -960 960 960" width="34px" fill="#5f6368">
                    <path d="M480-480.67q-66 0-109.67-43.66Q326.67-568 326.67-634t43.66-109.67Q414-787.33 480-787.33t109.67 43.66Q633.33-700 633.33-634t-43.66 109.67Q546-480.67 480-480.67ZM160-160v-100q0-36.67 18.5-64.17T226.67-366q65.33-30.33 127.66-45.5 62.34-15.17 125.67-15.17t125.33 15.5q62 15.5 127.28 45.3 30.54 14.42 48.96 41.81Q800-296.67 800-260v100H160Zm66.67-66.67h506.66V-260q0-14.33-8.16-27-8.17-12.67-20.5-19-60.67-29.67-114.34-41.83Q536.67-360 480-360t-111 12.17Q314.67-335.67 254.67-306q-12.34 6.33-20.17 19-7.83 12.67-7.83 27v33.33ZM480-547.33q37 0 61.83-24.84Q566.67-597 566.67-634t-24.84-61.83Q517-720.67 480-720.67t-61.83 24.84Q393.33-671 393.33-634t24.84 61.83Q443-547.33 480-547.33Zm0-86.67Zm0 407.33Z"/>
                </svg>
            </a>
        </li>
    </ul>
</nav>
<br>
<br>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
