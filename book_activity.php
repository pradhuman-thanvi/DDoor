<?php
    session_start();
    include 'database.php';
    if (!isset($_SESSION['logged_in'])) {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Spot - DDoor</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Josefin Sans', sans-serif;
        }
        .title {
            text-align: center;
            margin: 20px 0;
            color: #FFC107; /* Yellow UI shade */
            font-weight: 700;
        }
        .spot-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .spot-box:hover {
            transform: scale(1.05);
        }
        .spot-box img {
         
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <?php include 'top_navbar.php'; ?>
    
    <div class="container">
        <br><br>
        <h1 class="title">Select Activity</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <a href="cricket_activity.php" class="text-decoration-none">
                    <div class="spot-box p-3">
                        <img src="images/book_1.png" class="img-fluid rounded" alt="Spot 1">
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="spot2.php" class="text-decoration-none">
                    <div class="spot-box p-3">
                        <img src="images/book_2.png" class="img-fluid rounded" alt="Spot 2">
                    </div>
                </a>
            </div>
            <!-- Add more spot boxes as needed -->
        </div>
    </div>
    
    <!-- Bottom Navigation Bar -->
    <?php include 'bottom_navbar.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
