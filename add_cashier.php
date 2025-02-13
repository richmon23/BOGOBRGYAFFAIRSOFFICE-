<?php
session_start();
include 'db.php';

// Ensure only the admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "cashier"; // Ensure this is fixed to 'cashier'

    $stmt = $conn->prepare("INSERT INTO users (firstname, surname, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $surname, $email, $password, $role);

    if ($stmt->execute()) {
        echo "Cashier added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Cashier</title>
</head>
<body>
    <h2>Add New Cashier</h2>
    <form method="POST">
        <input type="text" name="firstname" placeholder="First Name" required><br>
        <input type="text" name="surname" placeholder="Surname" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Add Cashier</button>
    </form>
    <a href="manage_users.php">Back to Users</a>
</body>
</html>
