<?php
session_start();
include("db.php");

$employee_code = $_POST['employee_code'] ?? "";

$result = mysqli_query($conn, "SELECT * FROM faculty_real WHERE employee_code='$employee_code'");

if($result && mysqli_num_rows($result) > 0){
    $faculty = mysqli_fetch_assoc($result);

    $_SESSION['faculty_id'] = $faculty['id'];
    $_SESSION['faculty_name'] = $faculty['name'];

    header("Location: faculty_dashboard.php");
    exit();
} else {
    $_SESSION['faculty_error'] = "Invalid employee code. Please try again.";
    header("Location: faculty_login.php");
    exit();
}
?>
