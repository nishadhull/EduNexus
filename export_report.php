<?php
include("db.php");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=invigilation_report.csv');

$output = fopen("php://output", "w");

fputcsv($output, array('Faculty Name', 'Room Number', 'Date', 'Shift'));

$query = mysqli_query($conn, "
SELECT f.name AS faculty_name, d.room_number, d.date, d.slot
FROM allocations a
JOIN faculty_real f ON a.faculty_id = f.id
JOIN duties d ON a.duty_id = d.duty_id
ORDER BY d.date DESC, d.slot ASC
");

while($row = mysqli_fetch_assoc($query)){
    fputcsv($output, array(
        $row['faculty_name'],
        $row['room_number'],
        $row['date'],
        $row['slot']
    ));
}

fclose($output);
exit();
?>
