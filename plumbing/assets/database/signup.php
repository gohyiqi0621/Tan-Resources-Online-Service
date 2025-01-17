<?php
require 'database.php';

function validateEmailWithMailboxLayer($email) {
    $api_key = '04a9e98e0e56a0e0e97062b70bae9e32'; // Replace with your actual API key
    $url = "https://apilayer.net/api/check?access_key=$api_key&email=$email";

    $response = @file_get_contents($url);
    if ($response === false) {
        die("Unable to validate email at this time. Please try again later.");
    }

    $data = json_decode($response, true);
    return isset($data['format_valid'], $data['smtp_check']) && $data['format_valid'] && $data['smtp_check'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = htmlspecialchars(trim($_POST['email']));
    $verification_code = rand(100000, 999999); // Generate 6-digit code

    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: signup.php?error=Passwords do not match");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=Invalid email format");
        exit();
    }

    // Validate email using Mailbox Layer API
    if (!validateEmailWithMailboxLayer($email)) {
        header("Location: signup.php?error=Invalid email address");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data and verification code into the database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, phone_number, password, email, verification_code, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
        if (!$stmt) {
            die("Database error: " . $conn->error);
        }
        $stmt->bind_param("ssssi", $username, $phone_number, $hashed_password, $email, $verification_code);
        $stmt->execute();
        header("Location: verify.php?success=Signup successful! Please check your email.");
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
