<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/OTP.php';

$db = new Database();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['mfa_user'] = $user['id'];

        $otpHandler = new OTP($pdo);
        $otp = $otpHandler->generate($user['id']);

        // In real system: send via email or SMS
        echo "Your OTP code is: <b>$otp</b><br>";
        echo "<a href='verify.php'>Verify OTP</a>";
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
