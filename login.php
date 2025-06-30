<?php
// Secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);

session_start(); // Start the session at the very beginning

session_regenerate_id(true); // Regenerate the session ID to prevent session fixation attacks

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($pass, $row['password'])) {
            // Set session variables upon successful login
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_type'] = $row['user_type'];

            // Debugging: Ensure session variables are set
            // print_r($_SESSION); exit();  // Uncomment for debugging to check session content

            // Redirect based on user type
            switch ($row['user_type']) {
                case 'super_admin':
                    header("Location: admin_dash.php"); // Redirect to admin dashboard
                    break;
                case 'driver':
                    header("Location: view_bookings.php"); // Redirect to driver bookings page
                    break;
                case 'delivery':
                    header("Location: view_orders.php"); // Redirect to delivery orders page
                    break;
                default:
                    header("Location: home.php"); // Redirect to home page if user type is unknown
            }
            exit(); // Ensure no further code is executed after the redirect
        } else {
            // Password incorrect, display an error message
            echo "<div class='alert alert-danger'>Invalid username or password.</div>";
        }
    } else {
        // User not found in the database
        echo "<div class='alert alert-danger'>Invalid username or password.</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            color: #333;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }
        .login-container h1 {
            margin: 0 0 20px 0;
            font-size: 28px;
            font-weight: 700;
            color: #f09e05;
        }
        .form-group {
            margin: 15px 0;
            position: relative;
        }
        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
            box-sizing: border-box;
            color: #666;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #f09e05;
            transition: color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #f09e05;
            outline: none;
            box-shadow: 0 2px 8px rgba(175, 157, 157, 0.2);
        }
        .form-group input:focus + i {
            color: #f09e05;
        }
        .form-group input::placeholder {
            color: #999;
        }
        .form-group input:focus::placeholder {
            color: #666;
        }
        .login-button {
            width: 100%;
            background-color: #f09e05;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 15px 0;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }
        .login-button:hover {
            background-color: #ff9000;
        }
        .footer {
            margin-top: 20px;
            color: #999;
        }
        .footer a {
            color: #f09e05;
            text-decoration: none;
            font-weight: 500;
        }
        .logo {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .logo img {
            width: 60px;
            height: 60px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo">
            <img src="images/logo.png" alt="Delivery Service Logo">
        </div>
        <h1>Login</h1>

        <form id="login-form" action="" method="POST" autocomplete="on">
            <div class="form-group">
                <input type="text" placeholder="Username" id="username" name="username" required autocomplete="username">
                <i class="fas fa-user"></i>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" id="password" name="password" required autocomplete="current-password">
                <i class="fas fa-lock"></i>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <div class="footer">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>

</body>
</html>
