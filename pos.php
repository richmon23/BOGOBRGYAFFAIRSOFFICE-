<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cashier') {
    header("Location: login.php");
    exit();
}
?>




<!DOCTYPE html>
<html>
<head>
    <title>POS Terminal</title>
</head>
<body>
    <h2>POS Terminal</h2>
    <p>Process sales here.</p>
    <a href="dashboard.php">Back to Dashboard</a>
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


