<?php
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if($username == "admin" && $password == "1475"){
    $_SESSION['user'] = $username;
    $_SESSION['role'] = 'admin';
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: index.php");
    exit();
}
?>
