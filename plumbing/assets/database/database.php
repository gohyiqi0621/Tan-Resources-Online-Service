<?php
// Replace with your actual database details
$host = 'localhost';   // Usually localhost
$username = 'root';    // Default username for XAMPP
$password = '';        // Default password for XAMPP (blank)
$dbname = 'tros';      // Name of your database

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log the error to a file instead of displaying it
    error_log("Connection failed: " . $conn->connect_error, 3, 'errors.log');
    die("Connection failed. Please try again later.");
}
?>
