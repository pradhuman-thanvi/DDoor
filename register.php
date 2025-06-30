<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Server-side phone number validation and password match validation
    if (strlen($phone) !== 10) {
        $signup_error = "Phone number must be 10 digits.";
    } elseif ($password !== $confirm_password) {
        $signup_error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $signup_error = "Username already exists. Please choose a different username.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Prepared statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO users (name, address, username, phone, email, password, user_type) VALUES (?, ?, ?, ?, ?, ?, 'user')");
            $stmt->bind_param("ssssss", $name, $address, $username, $phone, $email, $password_hash);

            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = 'user';
                header("Location: home.php");
                exit();
            } else {
                $signup_error = "Failed to create account. Please try again.";
            }
        }

        $stmt->close();
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        }
        .signup-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }
        .signup-container h1 {
            margin: 0 0 20px 0;
            font-size: 28px;
            font-weight: 700;
            color: #f09e05;
        }
        .form-group {
            margin: 15px 0;
            position: relative;
        }
        .form-group input, .form-group select {
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
        .signup-button {
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
        .signup-button:hover {
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
        @media (max-height: 800px) {
            .signup-container {
                padding: 20px;
            }
            .signup-container .form-group {
                margin: 10px 0;
            }
            .signup-button {
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="logo">
            <img src="images/logo.png" alt="Delivery Service Logo">
        </div>
        <h1>Signup</h1>
        <?php if (!empty($signup_error)): ?>
            <div class="alert alert-danger"><?php echo $signup_error; ?></div>
        <?php endif; ?>
        <form id="signup-form" action="" method="POST" autocomplete="on">
            <div class="form-group">
                <input type="text" placeholder="Full Name" id="name" name="name" required autocomplete="name">
                <i class="fas fa-user"></i>
            </div>
            <div class="form-group">
                <input type="text" placeholder="Username" id="username" name="username" required autocomplete="username">
                <i class="fas fa-user"></i>
            </div>
            <div class="form-group">
                <div style="display: flex;">
                    <span style="padding: 15px; background-color: #e0e0e0; border-top-left-radius: 5px; border-bottom-left-radius: 5px; border: 1px solid #e0e0e0;">+91</span>
                    <input type="text" placeholder="Phone Number" id="phone" name="phone" required style="border-top-left-radius: 0; border-bottom-left-radius: 0;" autocomplete="tel">
                </div>
                <i class="fas fa-phone" style="left: 60px;"></i>
            </div>
            <div class="form-group">
                <input type="text" placeholder="Address" id="address" name="address" required autocomplete="street-address">
                <i class="fas fa-address-card"></i>
            </div>
            <div class="form-group">
                <input type="email" placeholder="Email (optional)" id="email" name="email" autocomplete="email">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" id="password" name="password" required autocomplete="new-password">
                <i class="fas fa-lock"></i>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required autocomplete="new-password">
                <i class="fas fa-lock"></i>
            </div>
            <button type="submit" class="signup-button">Signup</button>
        </form>
        <div class="footer">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
    <script>
        document.getElementById('signup-form').addEventListener('submit', function (event) {
            var phoneInput = document.getElementById('phone');
            var passwordInput = document.getElementById('password');
            var confirmPasswordInput = document.getElementById('confirm_password');

            if (phoneInput.value.length !== 10) {
                alert('Phone number must be 10 digits.');
                event.preventDefault(); // Prevent form submission
            } else if (passwordInput.value !== confirmPasswordInput.value) {
                alert('Passwords do not match.');
                event.preventDefault(); // Prevent form submission
            }
        });

        document.addEventListener('focusin', (event) => {
            const container = document.querySelector('.signup-container');
            container.style.paddingBottom = '200px'; // Increase bottom padding to ensure the last fields are visible when the keyboard is open
        });

        document.addEventListener('focusout', (event) => {
            const container = document.querySelector('.signup-container');
            container.style.paddingBottom = '40px'; // Reset bottom padding after the keyboard is closed
        });
    </script>
</body>
</html>
