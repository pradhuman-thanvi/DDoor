<?php
// Start the session
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the username and user_type of the logged-in user
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type']; // Assuming user_type is stored in session

// Check if the user is an admin
if (!in_array($user_type, ['super_admin', 'delivery'])) {
    header("Location: login.php");
    exit();
}


// Fetch hyperlocal orders
$sql = "SELECT * FROM hyperlocal";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Hyperlocal Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            background-color: #343a40;
            color: white;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-update-form {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Hyperlocal Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dash.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Hyperlocal Orders</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Item Name</th>
                    <th>Receiver Name</th>
                    <th>Receiver Phone</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['pickup'] . "</td>";
                        echo "<td>" . $row['dropoff'] . "</td>";
                        echo "<td>" . $row['item_name'] . "</td>";
                        echo "<td>" . $row['receiver_name'] . "</td>";
                        echo "<td>" . $row['receiver_phone'] . "</td>";
                        echo "<td>" . $row['payment'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>
                        <form class='status-update-form' action='update_status.php' method='POST'>";
                // Check if the current status is not "Delivered"
                if ($row['status'] != 'Delivered') {
                    echo "<input type='hidden' name='order_id' value='" . $row['id'] . "'>
                          <select name='status' class='form-select me-2'>
                          <option value='Accepted'" . ($row['status'] == 'Accepted' ? ' selected' : '') . ">Accepted</option>
                              <option value='Assigning Delivery Partner'" . ($row['status'] == 'Assigning Delivery Partner' ? ' selected' : '') . ">Assigning Delivery Partner</option>
                              <option value='On The Way'" . ($row['status'] == 'On Way' ? ' selected' : '') . ">On The Way</option>
                              <option value='Delivered'" . ($row['status'] == 'Delivered' ? ' selected' : '') . ">Delivered</option>
                          </select>
                          <button type='submit' class='btn btn-primary'>Update</button>";
                } else {
                    // If the status is "Delivered", display the status without the form
                    echo "<input type='hidden' name='order_id' value='" . $row['id'] . "'>
                          <input type='text' class='form-control' value='Delivered' readonly>
                          <input type='hidden' name='status' value='Delivered'>";
                }
                echo "</form>
                      </td>";
                
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close();
?>
