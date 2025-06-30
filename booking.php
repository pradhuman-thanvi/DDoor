<?php
// Start the session
session_start();
include 'database.php';

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query to get user's information
    $stmt = $conn->prepare("SELECT name, phone, address FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // Fetch user's information
        $row = $result->fetch_assoc();
       
        $name = $row['name'];
        $phone = $row['phone'];
        $address = $row['address'];

        // Close statement
        $stmt->close();
    } else {
        echo "User not found.";
    }

    // If the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $pickup = $_POST['pickup'];
        $dropoff = $_POST['dropoff'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $username = $_SESSION['username'];

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO booking_yatra (username, name, phone, pickup, dropoff, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $name, $phone, $pickup, $dropoff, $date, $time);
        if ($stmt->execute()) {
            header("Location: my_hyperlocal_orders.php"); // Redirect to My Orders section
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }

    // Close database connection
    $conn->close();
} else {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDoor - Rickshaw Booking</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }
        .header {
            background-color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .header .back-arrow {
            margin-right: 15px;
            cursor: pointer;
            font-size: 24px;
            color: #f09e05;
        }
        .container {
            padding: 20px;
            text-align: center;
        }
        .container h2 {
            font-family: 'Josefin Sans', sans-serif;
            color: #f09e05;
            font-size: 24px;
            margin-top: 20px;
        }
        .form-group {
            margin-top: 20px;
            text-align: left;
        }
        .form-group label {
            font-size: 16px;
            color: #666;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-book {
            background-color: #f09e05;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
        }
        .footer {
            background-color: white;
            padding: 15px;
            text-align: center;
            color: #999;
            border-top: 1px solid #e0e0e0;
            margin-top: 20px;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }
        .footer strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="home.php">
            <i class="fas fa-arrow-left back-arrow"></i>
        </a>
        <h1>DDoor - Rickshaw Booking</h1>
    </div>

    <div class="container">
        <h2>Book Your Rickshaw</h2>
        <form method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="pickup">Pickup Location:</label>
                <input type="text" id="pickup" name="pickup" value="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter pickup location" required>
            </div>
            <div class="form-group">
                <label for="dropoff">Dropoff Location:</label>
                <input type="text" id="dropoff" name="dropoff" placeholder="Enter dropoff location" required>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time:</label>
                <input type="time" id="time" name="time" required>
            </div>
            <button type="submit" class="btn-book">Book Now</button>
        </form>
    </div>

    <div class="footer">
        <strong>&copy; 2024 DDoor. All rights reserved.</strong>
    </div>

    <script>
        // Get the current time
        var currentTime = new Date();
        // Add 15 minutes to the current time
        currentTime.setMinutes(currentTime.getMinutes() - 15);

        // Format the time as HH:MM
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var formattedTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;

        // Set the minimum selectable time in the input field
        document.getElementById("time").setAttribute('min', formattedTime);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var disclaimer = document.querySelector("img[alt='www.000webhost.com']");
            if (disclaimer) {
                disclaimer.remove();
            }
        });
    </script>
</body>
</html>