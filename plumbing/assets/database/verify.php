<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $verification_code = $_POST['email_verification_code'];

    // Check if the email and code match
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ? AND is_verified = 0");
    $stmt->bind_param("si", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mark user as verified
        $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $updateStmt->bind_param("s", $email);
        $updateStmt->execute();

        echo "Email successfully verified!";
    } else {
        echo "Invalid verification code or email.";
    }
}
?>
