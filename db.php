<?php
$conn = mysqli_connect("YOUR_HOST", "YOUR_USERNAME", "YOUR_PASSWORD", "YOUR_DATABASE");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
