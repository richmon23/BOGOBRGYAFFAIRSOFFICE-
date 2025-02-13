<?php
session_start();
include 'db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $product_name, $category, $price, $stock);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h2>Add New Product</h2>
    <form method="POST">
        <input type="text" name="product_name" placeholder="Product Name" required><br>
        <input type="text" name="category" placeholder="Category" required><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br>
        <input type="number" name="stock" placeholder="Stock Quantity" required><br>
        <button type="submit">Add Product</button>
    </form>
    <a href="manage_products.php">View Products</a>
</body>
</html>
