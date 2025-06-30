<?php
session_start();
include 'database.php';

// Check if the user is logged in and is an admin or super_admin
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'super_admin') {
    header("Location: login.php");
    exit();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user type update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $user_type = $_POST['user_type'];

    $stmt = $conn->prepare("UPDATE users SET user_type = ? WHERE id = ?");
    $stmt->bind_param("si", $user_type, $user_id);
    if ($stmt->execute()) {
        echo "User type updated successfully.";
    } else {
        echo "Error updating user type: " . $stmt->error;
    }
    $stmt->close();
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all users
$result = $conn->query("SELECT id, name, username, phone, address, email, user_type FROM users");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }
        .table-container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f09e05;
            color: white;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
        .edit-form, .delete-form {
            display: inline;
        }
        .edit-form select, .delete-form button {
            margin-left: 10px;
        }
        .edit-form button {
            background-color: #f09e05;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .delete-form button {
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h1>All Users</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_type']); ?></td>
                        <td class="actions">
                            <form class="edit-form" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <select name="user_type">
                                    <option value="super_admin" <?php if ($row['user_type'] == 'super_admin') echo 'selected'; ?>>Super Admin</option>
                                    <option value="driver" <?php if ($row['user_type'] == 'driver') echo 'selected'; ?>>Driver</option>
                                    <option value="delivery" <?php if ($row['user_type'] == 'delivery') echo 'selected'; ?>>Delivery</option>
                                    <option value="user" <?php if ($row['user_type'] == 'user') echo 'selected'; ?>>User</option>
                                </select>
                                <button type="submit" name="update_user">Update</button>
                            </form>
                            <form class="delete-form" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_user">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
