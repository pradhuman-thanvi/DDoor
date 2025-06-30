<?php
require 'config.php';

$date = $_GET['date'] ?? null;
$companyId = $_GET['company_id'] ?? null;
$period = $_GET['period'] ?? null;

if (!$date || !$companyId || !$period) {
    echo json_encode(['error' => 'Invalid input.']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT time FROM bookings WHERE date = :date AND company_id = :company_id AND period = :period");
    $stmt->execute(['date' => $date, 'company_id' => $companyId, 'period' => $period]);

    $bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $allTimes = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00']; // Example times
    $availableTimes = array_diff($allTimes, $bookedTimes);

    echo json_encode(array_values($availableTimes));
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
