<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";
include "functions.php";

$duties = mysqli_query($conn, "SELECT * FROM duties");

if (!$duties) {
    http_response_code(500);
    exit("Could not load duties: " . mysqli_error($conn));
}

while ($duty = mysqli_fetch_assoc($duties)) {
    $dutyId = (int) ($duty['id'] ?? $duty['duty_id'] ?? 0);
    $requiredFaculty = (int) ($duty['required_faculty'] ?? 0);

    if ($dutyId === 0 || $requiredFaculty === 0) {
        http_response_code(500);
        exit("Duty data is incomplete. Please check the duties table.");
    }

    $facultyResult = getAvailableFaculty($conn);
    $facultyList = [];

    while ($row = mysqli_fetch_assoc($facultyResult)) {
        $facultyList[] = $row;
    }

    if (count($facultyList) === 0) {
        http_response_code(400);
        exit("No faculty available for duty ID: " . $dutyId);
    }

    $selectedFaculty = selectFaculty($facultyList, $requiredFaculty);

    foreach ($selectedFaculty as $faculty) {
        $facultyId = (int) $faculty['id'];

        $insert = mysqli_query(
            $conn,
            "INSERT INTO allocations (faculty_id, duty_id)
             VALUES ($facultyId, $dutyId)"
        );

        if (!$insert) {
            http_response_code(500);
            exit("Could not save allocation: " . mysqli_error($conn));
        }

        $update = mysqli_query(
            $conn,
            "UPDATE faculty_real
             SET duty_count = duty_count + 1
             WHERE id = $facultyId"
        );

        if (!$update) {
            http_response_code(500);
            exit("Could not update faculty duty count: " . mysqli_error($conn));
        }
    }
}

echo "Duty allocation completed successfully!";
?>
