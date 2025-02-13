<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cashier') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cashier_id = $_SESSION['user_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $total = $quantity * $price;

    $stmt = $conn->prepare("INSERT INTO sales (cashier_id, product_name, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isidd", $cashier_id, $product_name, $quantity, $price, $total);

    if ($stmt->execute()) {
        echo "Sale recorded successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Sale</title>
</head>
<body>
    <h2>Record a Sale</h2>
    <form method="POST">
        <input type="text" name="product_name" placeholder="Product Name" required><br>
        <input type="number" name="quantity" placeholder="Quantity" required><br>
        <input type="number" step="0.01" name="price" placeholder="Price per Unit" required><br>
        <button type="submit">Record Sale</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
