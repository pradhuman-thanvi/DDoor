<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT name, phone, address FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $phone = $row['phone'];
    $address = $row['address'];
    $stmt->close();
} else {
    echo "User not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $people = $_POST['people'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $company_id = $_POST['company'];

    $stmt = $conn->prepare("INSERT INTO cricket_1 (username, name, phone, people, date, time, company_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $username, $name, $phone, $people, $date, $time, $company_id);
    if ($stmt->execute()) {
        header("Location: my_activity_bookings.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDoor - Activity Booking</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body { margin: 0; padding: 0; background-color: #f8f8f8; color: #333; font-family: 'Josefin Sans', sans-serif; }
        .header { background-color: white; padding: 15px; display: flex; align-items: center; border-bottom: 1px solid #e0e0e0; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .header h1 { margin: 0; font-size: 18px; font-weight: 500; }
        .header .back-arrow { margin-right: 15px; cursor: pointer; font-size: 24px; color: #f09e05; }
        .container { padding: 20px; text-align: center; }
        .container h2 { font-family: 'Josefin Sans', sans-serif; color: #f09e05; font-size: 24px; margin-top: 20px; }
        .form-group { margin-top: 20px; text-align: left; }
        .form-group label { font-size: 16px; color: #666; }
        .form-group input, .form-group select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-book { background-color: #f09e05; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; margin-top: 20px; cursor: pointer; }
        .footer { background-color: white; padding: 15px; text-align: center; color: #999; border-top: 1px solid #e0e0e0; margin-top: 20px; box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1); }
        .footer strong { color: #333; }
        .time-slots { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 20px; }
        .time-slot { background-color: #fff; border: 1px solid #ddd; border-radius: 5px; padding: 10px 20px; cursor: pointer; transition: background-color 0.3s, color 0.3s; }
        .time-slot.booked { background-color: #ddd; color: #999; cursor: not-allowed; }
        .time-slot:not(.booked):hover { background-color: #f09e05; color: #fff; }
        .time-slot.selected { background-color: #f09e05; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <a href="home.php"><i class="fas fa-arrow-left back-arrow"></i></a>
        <h1>DDoor - Activity Booking</h1>
    </div>

    <div class="container">
        <h2>Book Your Activity</h2>
        <form method="post" action="book_activity.php">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="people">Number of People:</label>
                <input type="number" id="people" name="people" required>
            </div>
            <div class="form-group">
                <label for="company">Select Your Box:</label>
                <select id="company" name="company" required>
                    <option value="">Select Company</option>
                    <option value="1">Near NK Cinema</option>
                    <option value="2">Sanjivani Box, Darjiyo Ki Bagechi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="period">Period:</label>
                <select id="period" name="period" required>
                    <option value="">Select Period</option>
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                    <option value="evening">Evening</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div id="time-slots" class="time-slots"></div>
            <input type="hidden" id="time" name="time" required>
            <button type="submit" class="btn-book">Book Now</button>
        </form>
    </div>

    <div class="footer">
        <strong>&copy; 2024 DDoor. All rights reserved.</strong>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('date');
            const companyInput = document.getElementById('company');
            const periodInput = document.getElementById('period');
            const timeSlotsContainer = document.getElementById('time-slots');
            const timeInput = document.getElementById('time');

            // Function to fetch time slots from the server
            function fetchTimeSlots() {
                const date = dateInput.value;
                const companyId = companyInput.value;
                const period = periodInput.value;

                if (date && companyId && period) {
                    const url = `fetch_time_slots.php?date=${date}&company_id=${companyId}&period=${period}`;

                    console.log(`Fetching slots for URL: ${url}`); // Debugging: log URL

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Fetched time slots:', data); // Debugging: log fetched data
                            updateUI(data);
                        })
                        .catch(error => console.error('Error fetching time slots:', error));
                } else {
                    console.log('Missing date, company or period');
                }
            }

            // Function to update the UI with fetched data
            function updateUI(data) {
                timeSlotsContainer.innerHTML = '';
                if (data.error) {
                    timeSlotsContainer.innerHTML = `<p>${data.error}</p>`;
                } else if (data.length === 0) {
                    timeSlotsContainer.innerHTML = '<p>No available time slots.</p>';
                } else {
                    data.forEach(slot => {
                        const slotElement = document.createElement('div');
                        slotElement.textContent = slot;
                        slotElement.className = 'time-slot';
                        slotElement.onclick = () => selectTimeSlot(slotElement, slot);
                        timeSlotsContainer.appendChild(slotElement);
                    });
                }
            }

            // Function to handle time slot selection
            function selectTimeSlot(element, slot) {
                document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('selected'));
                element.classList.add('selected');
                timeInput.value = slot;
            }

            // Event listeners for form inputs
            dateInput.addEventListener('change', fetchTimeSlots);
            companyInput.addEventListener('change', fetchTimeSlots);
            periodInput.addEventListener('change', fetchTimeSlots);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
