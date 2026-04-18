<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");
include("functions.php");

$date = $_GET['date'] ?? '';

if($date == '') {
    http_response_code(400);
    exit("PLease select a date first.");
}

$duties = mysqli_query($conn, "
SELECT * FROM duties
WHERE date = '$date'
ORDER BY slot, room_number
");

if(!$duties) {
    http_response_code(500);
    exit("COuld not load duties: " . mysqli_error($conn));
}

if(mysqli_num_rows($duties) == 0) {
    http_response_code(404);
    exit("No duties found for the selected date!");
}

$totalAllocated = 0;

while($duty = mysqli_fetch_assoc($duties)){

    $dutyId = (int)$duty['duty_id'];
    $slot = $duty['slot'];

    $alreadyAssigned = mysqli_query($conn, "
    SELECT * FROM allocations
    WHERE duty_id = $dutyId
    ");

    if(mysqli_num_rows($alreadyAssigned) > 0){
        continue;
    }

    $facultyList = getAvailableFacultyByPriority($conn, $date, $slot);

    if(count($facultyList) == 0){
        continue;
    }

    $faculty = $facultyList[0];
    $facultyId = (int)$faculty['id'];

    $insert = mysqli_query($conn, "
    INSERT INTO allocations (faculty_id, duty_id)
    VALUES ($facultyId, $dutyId)
    ");

    if($insert){
        mysqli_query($conn, "
        UPDATE faculty_real
        SET duty_count = duty_count + 1
        WHERE id = $facultyId
        ");

        $room_number = mysqli_real_escape_string($conn, $duty['room_number']);
        $safeSlot = mysqli_real_escape_string($conn, $slot);

        mysqli_query($conn, "
        INSERT INTO notifications (faculty_id, message, status)
        VALUES (
            $facultyId,
            'You have been assigned invigilation duty for Room number $room_number on $date during $safeSlot.',
            'unread'
        )
        ");

        $totalAllocated++;
    }
}

echo "Allocation completed for " . $date . ". Total faculty allocated: " . $totalAllocated;
?>