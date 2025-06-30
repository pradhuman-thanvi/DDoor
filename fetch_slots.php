<?php
include 'database.php';

if (isset($_GET['date']) && isset($_GET['company_id']) && isset($_GET['period'])) {
    $date = $_GET['date'];
    $company_id = $_GET['company_id'];
    $period = $_GET['period'];

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch all time slots for the selected company and period
    $stmt = $conn->prepare("SELECT slot_time FROM time_slots WHERE company_id = ? AND period = ?");
    $stmt->bind_param("is", $company_id, $period);
    $stmt->execute();
    $result = $stmt->get_result();

    $allSlots = [];
    while ($row = $result->fetch_assoc()) {
        $allSlots[] = $row['slot_time'];
    }

    // Fetch booked slots for the selected date and company
    $stmt = $conn->prepare("SELECT time FROM cricket_1 WHERE date = ? AND company_id = ?");
    $stmt->bind_param("si", $date, $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookedSlots = [];
    while ($row = $result->fetch_assoc()) {
        $bookedSlots[] = $row['time'];
    }

    $stmt->close();
    $conn->close();

    $availableSlots = array_diff($allSlots, $bookedSlots);

    echo json_encode(array_values($availableSlots));
}
?>
