<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'daily';

if ($filter == 'daily') {
    $query = "SELECT * FROM sales WHERE DATE(sale_date) = CURDATE()";
} elseif ($filter == 'monthly') {
    $query = "SELECT * FROM sales WHERE MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
} elseif ($filter == 'yearly') {
    $query = "SELECT * FROM sales WHERE YEAR(sale_date) = YEAR(CURDATE())";
} else {
    $query = "SELECT * FROM sales";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
</head>
<body>
    <h2>Sales Report</h2>
    <a href="?filter=daily">Daily</a> | 
    <a href="?filter=monthly">Monthly</a> | 
    <a href="?filter=yearly">Yearly</a> | 
    <a href="?filter=all">All</a>
    
    <table border="1">
        <tr>
            <th>Sale ID</th>
            <th>Cashier ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Sale Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['sale_id']; ?></td>
                <td><?php echo $row['cashier_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo number_format($row['total'], 2); ?></td>
                <td><?php echo $row['sale_date']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
