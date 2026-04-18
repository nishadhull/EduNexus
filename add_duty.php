<?php
session_start();
include("db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}

$room_number = $_POST['room_number'] ?? '';
$date = $_POST['date'] ?? '';
$slot = $_POST['slot'] ?? '';

if($room_number == '' || $date == '' || $slot == ''){
    $_SESSION['error'] = "Please fill all duty details.";
    header("Location: dashboard.php");
    exit();
}

mysqli_query($conn, "
INSERT INTO duties (room_number, date, slot)
VALUES ('$room_number', '$date', '$slot')
");

$_SESSION['success'] = "New invigilation duty added successfully.";
header("Location: dashboard.php");
exit();
?>
