<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/OTP.php';

if (!isset($_SESSION['mfa_user'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$pdo = $db->connect();
$otpHandler = new OTP($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $inputOtp = $_POST['otp'];

    if ($otpHandler->verify($_SESSION['mfa_user'], $inputOtp)) {

        $_SESSION['user'] = $_SESSION['mfa_user'];
        unset($_SESSION['mfa_user']);

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid or expired OTP.";
    }
}
?>

<form method="POST">
    Enter OTP: <input type="text" name="otp" required><br><br>
    <button type="submit">Verify</button>
</form>
