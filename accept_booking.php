<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['acceptor']) && isset($_POST['status'])) {
        $bookingId = $_POST['id'];
        $acceptorName = $_POST['acceptor'];
        $status = $_POST['status'];

        // Update booking with acceptor's name and status
        $stmt = $conn->prepare("UPDATE booking_yatra SET acceptor_name = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $acceptorName, $status, $bookingId);

        if ($stmt->execute()) {
            echo "Booking accepted successfully.";
        } else {
            echo "Error occurred while accepting booking: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Method not allowed.";
}
?>
