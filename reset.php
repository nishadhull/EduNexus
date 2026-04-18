<?php

include "db.php";

mysqli_query($conn, "DELETE FROM allocations");

mysqli_query($conn, "UPDATE faculty SET duty_count = 0");

echo "System reset successfully!";
?>