<?php
require_once 'db_conn.php'; // Database connection

session_start();
$errors = [];

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['full_name']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $created_at = date('Y-m-d H:i:s');
    $role = 'customer';  // Default role for new users

    // Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    }
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        $errors['user_exist'] = 'Email is already registered';
    }

    // If there are errors, redirect back to register page
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../register.php');
        exit();
    }

    // Hash password and insert user
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password, name, created_at, role) VALUES (:email, :password, :name, :created_at, :role)");
    $stmt->execute(['email' => $email, 'password' => $hashedPassword, 'name' => $name, 'created_at' => $created_at, 'role' => $role]);

    header('Location: ../login.php'); // Redirect to login page after registration
    exit();
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $errors['login'] = 'Invalid email or password';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../login.php'); // Redirect to login page if error
        exit();
    }

    // Set session and role-based redirection
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role']; // Set role in session

    // Redirect based on user role
    if ($_SESSION['user_role'] === 'customer') {
        header('Location: ../../index.html'); // Redirect to customer page
    } elseif ($_SESSION['user_role'] === 'staff') {
        header('Location: ../../../staff&adminPage/index.html'); // Redirect to staff page
    } elseif ($_SESSION['user_role'] === 'admin') {
        header('Location: ../../../staff&adminPage/index.html'); // Redirect to admin page
    }

    exit();
}
?>
