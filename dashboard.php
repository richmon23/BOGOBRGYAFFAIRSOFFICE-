<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$firstname = $_SESSION['firstname'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>POS Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $firstname; ?> (<?php echo ucfirst($role); ?>)</h1>

    <?php if ($role === 'admin') { ?>
        <a href="add_cashier.php">Add Cashier</a><br>
        <a href="manage_users.php">Manage Users</a><br>
        <a href="manage_products.php">Manage Products</a><br>
        <a href="sales_report.php">Sales Report</a><br>
    <?php } else { ?>
        <!-- <a href="pos.php">POS Terminal</a><br> -->
         <br>
         <a href="CASHIER/pos_terminal.php">POS TERMINAL</a>
         <br>
        <a href="record_sale.php">Record Sale</a><br>
    <?php } ?>

    <a href="logout.php">Logout</a>
</body>

<script>

const socket = new WebSocket("ws://localhost:8080");

socket.onopen = function() {
    console.log("Connected to WebSocket server.");
};

socket.onmessage = function(event) {
    console.log("New message received: " + event.data);
    // Update sales or UI with new data
};

socket.onerror = function(error) {
    console.error("WebSocket Error: ", error);
};

socket.onclose = function() {
    console.log("WebSocket connection closed.");
};


</script>
</html>
