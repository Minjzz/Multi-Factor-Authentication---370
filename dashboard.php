<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

echo "<h2>Welcome to Secure Dashboard</h2>";
?>
