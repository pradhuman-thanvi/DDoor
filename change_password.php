<?php
session_start();
include 'database.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user = $_SESSION['username'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Fetch the current password hash from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the current password
        if (password_verify($current_password, $row['password'])) {
            // Check if new password and confirm new password match
            if ($new_password === $confirm_new_password) {
                // Hash the new password
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the password in the database
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmt->bind_param("ss", $new_password_hashed, $user);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Password changed successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error updating password.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>New passwords do not match.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Current password is incorrect.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>User not found.</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
