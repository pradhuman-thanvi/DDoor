<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $bookingId = $_POST['id'];
        $newStatus = $_POST['status'];

        // Update booking status
        $stmt = $conn->prepare("UPDATE booking_yatra SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $bookingId);

        if ($stmt->execute()) {
            echo "Status updated successfully.";
        } else {
            echo "Error occurred while updating status: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Method not allowed.";
}
?>
