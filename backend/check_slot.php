<?php

include_once '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['start_date'] ?? '';
    $time = $_POST['start_time'] ?? '';

    // Basic validation
    if (empty($date) || empty($time)) {
        echo "Invalid input.";
        exit;
    }

    // Convert the new test's start date and time to a DateTime object
    $start_datetime = new DateTime("$date $time");

    // Prepare and execute the SQL query to get existing tests for the given start_date
    $sql = "SELECT start_time, duration FROM tests WHERE start_date = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $date); // Bind parameters
        $stmt->execute();
        $result = $stmt->get_result();

        // Loop through each test and check if the new test overlaps with any existing test
        while ($row = $result->fetch_assoc()) {
            $existing_start_time = $row['start_time'];
            $existing_duration = $row['duration'];

            // Convert existing start time to a DateTime object
            $existing_start_datetime = new DateTime("$date $existing_start_time");

            // Calculate the end time of the existing test by adding its duration
            $existing_end_datetime = clone $existing_start_datetime;
            $existing_end_datetime->modify("+$existing_duration minutes");

            // Check if the new test starts before or during the existing test's end time
            if ($start_datetime <= $existing_end_datetime) {
                // The new test starts before the existing test's end time
                echo "unavailable";
                $stmt->close();
                $conn->close();
                exit;
            }
        }

        // If no overlap is found, the slot is available
        echo "available";

        $stmt->close();
    } else {
        echo "Error in query: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
