<?php
session_start();

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $email_verification_code = $_POST['email_verification_code'];
    $user_type = $_POST['user_type'];

    // Check if OTP matches
    if ($email_verification_code !== $_SESSION['otp']) {
        echo "Invalid email verification code.";
        exit;
    }

    // Additional validation (e.g., password match)
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Save user to the database
    $query = "INSERT INTO users (username, phone_number, password, email, user_type) VALUES ('$username', '$phone_number', '$hashed_password', '$email', '$user_type')";
    if (mysqli_query($conn, $query)) {
        echo "Registration successful.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
