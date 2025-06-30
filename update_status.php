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

// Check if form data is present
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Database connection
    

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the order status
    $stmt = $conn->prepare("UPDATE hyperlocal SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to the orders page
    header("Location: view_orders.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
