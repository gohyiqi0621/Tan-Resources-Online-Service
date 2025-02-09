<?php
session_start();
require 'database.php'; // Database connection
require 'vendor/autoload.php'; // Include SendGrid SDK

use SendGrid\Mail\Mail;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['errors'] = ["Email not found."];
        header("Location: ../../forget_password.php");
        exit();
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $expiry = time() + 300; // OTP expires in 5 minutes

    // Store OTP in the database
    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?");
    $stmt->bind_param("iis", $otp, $expiry, $email);
    $stmt->execute();

    if ($stmt->affected_rows == 0) {
        // Log error if OTP is not stored
        error_log("Failed to store OTP in database for email: $email");
        $_SESSION['errors'] = ["Failed to store OTP in the database."];
        header("Location: ../../forget_password.php");
        exit();
    }

    // Send OTP via email (testing simplified email)
    $emailMessage = new Mail();
    $emailMessage->setFrom("tanresourcesonlineservice@gmail.com", "Tan Resources Online Service");
    $emailMessage->setSubject("Test Email");
    $emailMessage->addTo($email);
    $emailMessage->addContent("text/plain", "This is a test email to check OTP functionality. Your OTP is: $otp. It expires in 5 minutes.");

    $sendgrid = new \SendGrid("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"); // Replace with your actual SendGrid API Key

    try {
        $response = $sendgrid->send($emailMessage);

        if ($response->statusCode() == 202) {
            $_SESSION['email'] = $email;
            header("Location: ../../verify_otp.php");
            exit();
        } else {
            // Log the response for debugging
            error_log("SendGrid error: " . $response->statusCode() . " - " . $response->body());
            $_SESSION['errors'] = ["Failed to send OTP. Please try again."];
            header("Location: ../../forget_password.php");
            exit();
        }
    } catch (Exception $e) {
        // Log exception for troubleshooting
        error_log("SendGrid Exception: " . $e->getMessage());
        $_SESSION['errors'] = ["Mail error: " . $e->getMessage()];
        header("Location: ../../forget_password.php");
        exit();
    }
} else {
    header("Location: ../forget_password.php");
    exit();
}
?>
