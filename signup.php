<?php
session_start();

// If a user is logged in but not an admin, prevent access
if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Remove form so users can't sign up themselves
echo "<h3>Signup is restricted. Please contact the admin.</h3>";
?>
