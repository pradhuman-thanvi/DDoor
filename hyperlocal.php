<?php
// Start the session
session_start();
include 'database.php';
// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Database connection

    // Create connection
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
        exit();
    }

    // If the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $pickup = $_POST['pickup-location'];
        $dropoff = $_POST['drop-location'];
        $receiver_name = $_POST['receiver_name'];
        $receiver_phone = $_POST['receiver_phone'];
        $payment = $_POST['payment'];
        $item_name = $_POST['item-name']; // Retrieve item name from the form
        
        $username = $_SESSION['username']; // Get the username from session

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO hyperlocal (username, name, phone, pickup, dropoff, receiver_name, receiver_phone, item_name, payment, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssssssss", $username, $name, $phone, $pickup, $dropoff, $receiver_name, $receiver_phone, $item_name, $payment);
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
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pick Up or Send Anything</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap">
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
        #pickup-location {
            background-color: #f09e05;
            color: #fff;
        }
        #pickup-location:focus {
            background-color: #fff;
            color: #666;
            border-color: #f09e05;
            outline: none;
            box-shadow: 0 2px 8px rgba(175, 157, 157, 0.2);
        }
        #pickup-location + i {
            color: #fff;
        }
        #pickup-location:focus + i {
            color: #f09e05;
        }
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            transition: color 0.3s ease;
        }
        .dotted-line {
            border-left: 2px dashed #000000;
            height: 40px;
            position: absolute;
            left: 21.5px;
            top: 38px;
            z-index: 1; /* Ensure it's above other elements */
        }
        .info-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .info-box h2 {
            font-size: 18px;
            margin: 0 0 15px 0;
            color: #333;
            font-weight: 500;
        }
        .info-box ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .info-box ul li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            color: #666;
        }
        .info-box ul li i {
            margin-right: 15px;
            font-size: 22px;
            color: #f09e05;
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
        #submit-button {
            display: none; /* Initially hidden */
            background-color: #f09e05;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #submit-button:hover {
            background-color: #ff9000;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding: 15px;
            text-align: center;
            color: #999;
            border-top: 1px solid #e0e0e0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-around; /* Adjust alignment */
        }
        .footer strong {
            color: #333;
            margin-left: 5px;
        }
        .footer i {
            font-size: 24px;
            color: #f09e05;
            margin-right: 5px;
        }
        #pickup-location::placeholder {
            color: white; /* Initial color */
        }
        #pickup-location:focus::placeholder {
            color: #666; /* Color when input is focused */
        }
        .additional-fields {
            display: none;
        }
        /* Dropdown menu styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 1;
        }
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>

    <div class="header">
        <a href="home.php">
            <i class="fas fa-arrow-left back-arrow"></i>
        </a>
        <h1>Pick up or Send anything</h1>
    </div>

    <div class="container">
        <form id="delivery-form" method="POST">
            <input type="hidden" name="subject" value="New Hyperlocal Order with the order ID ">
            <div class="form-group">
                <input type="text" placeholder="Set pick up location" value="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>" id="pickup-location" name="pickup-location" required>
                <i class="fas fa-location-arrow"></i>
                <div class="dotted-line"></div>
            </div>
            <div class="form-group">
                <input type="text" placeholder="Set drop location" id="drop-location" name="drop-location" required>
                <i class="fas fa-map-marker-alt"></i>
            </div>

            <!-- Additional fields initially hidden -->
            <div class="additional-fields" id="additional-fields">
                <div class="form-group">
                    <input type="text" placeholder="Receiver's Name" id="receiver_name" name="receiver_name" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Receiver's Phone Number" id="receiver_phone" name="receiver_phone" required>
                    <i class="fas fa-phone"></i>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Item Name" id="item-name" name="item-name" required>
                    <i class="fas fa-box"></i>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Sender's Name" id="sender-name" name="sender-name" value="<?php echo isset($name) ? $name : ''; ?>" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Sender's Phone Number" id="sender-phone" name="sender-phone" value="<?php echo isset($phone) ? $phone : ''; ?>" required>
                    <i class="fas fa-phone"></i>
                </div>
                <!-- Payment dropdown menu -->
                <div class="form-group">
                    <select id="payment" class="form-select" name="payment" required>
                        <option value="" disabled selected>₹ Select Payment Method</option>
                        <option value="pay_on_delivery">Pay on Delivery</option>
                        <option value="pay_on_pickup">Pay on Pickup</option>
                        <option value="pay_online_whatsapp">Pay Online by WhatsApp</option>
                    </select>
                </div>
            </div>

            <!-- Submit button initially hidden -->
            <button type="submit" id="submit-button" style="display: none;">Submit <i class="fa-solid fa-box"></i></button>
        </form>

        <div class="info-box">
            <h2>Things to keep in mind</h2>
            <ul>
                <li><i class="fas fa-box"></i> Avoid sending expensive or fragile items</li>
                <li><i class="fas fa-motorcycle"></i> Items should fit in a backpack</li>
                <li><i class="fas fa-ban"></i> No alcohol, illegal or restricted items</li>
                <li><i class="fas fa-clock"></i> Order before 7PM to avoid delays in delivery</li>
            </ul>
        </div>

        <div class="footer">
            Delivery charges <br>
            <strong>Starting at ₹30 for every Order</strong>
        </div>
    </div>

    <script>
        // Function to generate a random order ID
        function generateOrderId() {
            return 'DD-PH-HY-' + Math.floor(Math.random() * 1000000);
        }

        // Function to get the order ID from storage or generate a new one
        function getOrderId() {
            let orderId = generateOrderId(); // Generate a new one
            return orderId;
        }

        // Get input elements
       
        const pickupInput = document.getElementById('pickup-location');
        const dropInput = document.getElementById('drop-location');
        const additionalFields = document.getElementById('additional-fields');
        const submitButton = document.getElementById('submit-button');
        const form = document.getElementById('delivery-form');

        // Function to check if both inputs have text
        function checkInputs() {
            if (pickupInput.value.trim() !== '' && dropInput.value.trim() !== '') {
                additionalFields.style.display = 'block'; // Show additional fields
                submitButton.style.display = 'block'; // Show submit button
            } else {
                additionalFields.style.display = 'none'; // Hide additional fields
                submitButton.style.display = 'none'; // Hide submit button
            }
        }

        // Event listeners to check inputs on input/change
        pickupInput.addEventListener('input', checkInputs);
        dropInput.addEventListener('input', checkInputs);

        // Get the order ID and set it in the form
      

        // Add animation when the form is submitted
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            form.submit(); // Submit the form
        });
    </script>
    
</body>
</html>
