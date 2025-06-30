<?php
include 'database.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_id = $_POST['company_id'];
    $period = $_POST['period'];
    $slot_time = $_POST['slot_time'];

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        $response['message'] = "Connection failed: " . $conn->connect_error;
    } else {
        $stmt = $conn->prepare("INSERT INTO time_slots (company_id, period, slot_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $company_id, $period, $slot_time);
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
}

echo json_encode($response);
?>
