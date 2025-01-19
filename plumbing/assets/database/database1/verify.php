<?php
session_start();

// Database connection
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Generate a random OTP
    $otp = rand(100000, 999999);

    // Save the OTP and email in the session or database
    $_SESSION['email'] = $email;
    $_SESSION['otp'] = $otp;

    // Send the OTP via email
    $to = $email;
    $subject = "Your Email Verification Code";
    $message = "Your verification code is: $otp";
    $headers = "From: no-reply@yourwebsite.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "OTP sent successfully to $email.";
    } else {
        echo "Failed to send OTP.";
    }
}
?>
