<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['errors'] = ["Passwords do not match."];
        header("Location: ../../verify_otp.php");
        exit();
    }

    // Check OTP validity
    $stmt = $conn->prepare("SELECT otp_code, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $user['otp_code'] != $otp || time() > $user['otp_expiry']) {
        $_SESSION['errors'] = ["Invalid or expired OTP."];
        header("Location: ../../verify_otp.php");
        exit();
    }

    // Update the password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    $_SESSION['message'] = "Password successfully reset. You can now log in.";
    header("Location: ../../login.php");
    exit();
} else {
    header("Location: ../../verify_otp.php");
    exit();
}
?>
