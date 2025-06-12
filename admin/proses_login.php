<?php
session_start();

// Set kredensial tetap
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    error_log("Username input: " . $username);
    error_log("Password input: " . $password);

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['login_error'] = "Username atau password salah!";
        header('Location: ../index.php');
        exit();
    }
}
?> 