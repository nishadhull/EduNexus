<?php

function getDesignationRules() {
    return [
        'IRF' => ['priority' => 1, 'max_duty' => 9],
        'URF' => ['priority' => 1, 'max_duty' => 9],
        'Teaching Assistant' => ['priority' => 2, 'max_duty' => 9],
        'Assistant Professor' => ['priority' => 3, 'max_duty' => 9],
        'Assistant Professor (Research Track Faculty)' => ['priority' => 4, 'max_duty' => 8],
        'Assistant Professor(Senior Scale)' => ['priority' => 5, 'max_duty' => 8],
        'Assistant Professor Senior Scale  (Research Track Faculty)' => ['priority' => 5, 'max_duty' => 8],
        'Assistant Professor(Selection Grade)' => ['priority' => 6, 'max_duty' => 8],
        'Associate Professor' => ['priority' => 7, 'max_duty' => 8],
        'Professor' => ['priority' => 8, 'max_duty' => 7],
        'Professor (Research Track Faculty)' => ['priority' => 8, 'max_duty' => 7]
    ];
}

function getFacultyPriority($designation) {
    $rules = getDesignationRules();
    return $rules[$designation]['priority'] ?? 999;
}

function getFacultyMaxDuty($designation) {
    $rules = getDesignationRules();
    return $rules[$designation]['max_duty'] ?? 7;
}

function getAvailableFacultyByPriority($conn, $date, $slot) {
    $date = mysqli_real_escape_string($conn, $date);
    $slot = mysqli_real_escape_string($conn, $slot);

    $result = mysqli_query($conn, "
        SELECT * FROM faculty_real
        WHERE availability = 'available'
          AND id NOT IN (
              SELECT a.faculty_id
              FROM allocations a
              JOIN duties d ON a.duty_id = d.duty_id
              WHERE d.date = '$date' AND d.slot = '$slot'
          )
    ");

    $facultyList = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $designation = trim($row['designation']);
        $allowedMaxDuty = getFacultyMaxDuty($designation);

        if ((int)$row['duty_count'] < $allowedMaxDuty) {
            $row['priority'] = getFacultyPriority($designation);
            $row['allowed_max_duty'] = $allowedMaxDuty;
            $facultyList[] = $row;
        }
    }

    usort($facultyList, function ($a, $b) {
        if ($a['priority'] == $b['priority']) {
            if ($a['duty_count'] == $b['duty_count']) {
                return $a['id'] <=> $b['id'];
            }
            return $a['duty_count'] <=> $b['duty_count'];
        }
        return $a['priority'] <=> $b['priority'];
    });

    return $facultyList;
}
?>
