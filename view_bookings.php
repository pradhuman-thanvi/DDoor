<?php
session_start();
include 'database.php';

// Check if user is logged in
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the username and user_type of the logged-in user
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type']; // Assuming user_type is stored in session

// Check if the user is an admin or driver
if (!in_array($user_type, ['super_admin', 'driver'])) {
    header("Location: login.php");
    exit();
}

// Fetch bookings from the database
if ($user_type === 'super_admin') {
    // If the user is a super admin, show all non-accepted bookings
    $sql = "SELECT * FROM booking_yatra WHERE acceptor_name IS NULL";
} else {
    // If the user is a driver, show bookings accepted by them
    $sql = "SELECT * FROM booking_yatra WHERE (acceptor_name IS NULL OR acceptor_name = '$username')";
}
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDoor - All Bookings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
    /* Add additional CSS styles here */
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #f8f9fa;
    }
    .container {
        padding: 20px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }
    .table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .action-buttons {
        display: flex;
        align-items: center;
    }
    .accept-btn {
        margin-right: 10px;
        padding: 5px 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .accept-btn:hover {
        background-color: #218838;
    }
    .accepted-btn {
        margin-right: 10px;
        padding: 5px 10px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: not-allowed;
    }
    .status-select {
        width: 100%;
        padding: 5px;
        border-radius: 5px;
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Bookings</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Pickup Location</th>
                    <th>Dropoff Location</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['pickup'] . "</td>";
                        echo "<td>" . $row['dropoff'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['time'] . "</td>";
                        echo "<td>";
                        echo "<select class='form-select status-select' data-id='" . $row['id'] . "'>";
                        echo "<option value='Pending'" . ($row['status'] == "Pending" ? " selected" : "") . ">Pending</option>";
                        echo "<option value='On Way'" . ($row['status'] == "On Way" ? " selected" : "") . ">On Way</option>";
                        echo "<option value='Arrived'" . ($row['status'] == "Arrived" ? " selected" : "") . ">Arrived</option>";
                        echo "<option value='Completed'" . ($row['status'] == "Completed" ? " selected" : "") . ">Completed</option>";
                        echo "</select>";
                        echo "</td>";
                        echo "<td class='action-buttons'>";
                        // If booking is accepted, display acceptor's name and change button appearance
                        if ($row['acceptor_name']) {
                            echo "<span>Accepted by: " . $row['acceptor_name'] . "</span>";
                            echo "<button class='accepted-btn' disabled>Accepted</button>";
                        } else {
                            echo "<button class='accept-btn' data-id='" . $row['id'] . "' data-driver='" . $username . "'>Accept</button>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener for status change
        document.querySelectorAll('.status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                var bookingId = this.getAttribute('data-id');
                var newStatus = this.value;
                // Send AJAX request to update booking status
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_status_yatra.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                        } else {
                            alert('Error occurred while updating status.');
                        }
                    }
                };
                xhr.send('id=' + bookingId + '&status=' + newStatus);
            });
        });

        // Add event listener for accept button click
        document.querySelectorAll('.accept-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var bookingId = this.getAttribute('data-id');
               
                var acceptorName = this.getAttribute('data-driver');
                // Send AJAX request to update booking status
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'accept_booking.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                            // Change button appearance and disable it
                            button.disabled = true;
                            button.classList.remove('accept-btn');
                            button.classList.add('accepted-btn');
                            button.innerText = 'Accepted';
                            // Add a span to display acceptor's name
                            var span = document.createElement('span');
                            span.innerText = 'Accepted by: ' + acceptorName;
                            button.parentNode.insertBefore(span, button.nextSibling);
                        } else {
                            alert('Error occurred while accepting booking.');
                        }
                    }
                };
                xhr.send('id=' + bookingId + '&acceptor=' + acceptorName + '&status=Accepted');
            });
        });
    });
    </script>
</body>
</html>
