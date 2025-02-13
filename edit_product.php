<?php
session_start();
include 'db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE products SET product_name=?, category=?, price=?, stock=? WHERE product_id=?");
    $stmt->bind_param("ssdii", $product_name, $category, $price, $stock, $product_id);

    if ($stmt->execute()) {
        header("Location: manage_products.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="POST">
        <input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required><br>
        <input type="text" name="category" value="<?php echo $product['category']; ?>" required><br>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br>
        <button type="submit">Update Product</button>
    </form>
    <a href="manage_products.php">Back</a>
</body>
</html>
