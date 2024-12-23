<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'faculty') {
    // Redirect to login page or show error message
    header("Location: Alogin.php");
    exit;
}

// Include your database connection file
include_once '../db/db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $test_type = $_POST['test_type'];
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $start_time = $_POST['start_time'];
    $dept = $_POST['dept'];
    $sec = $_POST['sec'];
    $course = $_POST['course'];
    $subj_code = $_POST['subj_code'];
    $duration = $_POST['duration'];

    // Validate data (add more validation as needed)
    if (empty($test_type) || empty($name) || empty($start_date) || empty($start_time) || empty($duration)) {
        $_SESSION['msg']="All fields required!";
        header("Location: http://localhost/cbtest/Addtest.php");
        exit;
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO tests (test_type, start_date, start_time, dept, sec, course, duration, name, subj_code, createdAt) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiss", $test_type, $start_date, $start_time, $dept, $sec, $course, $duration, $name,$subj_code);

    if ($stmt->execute()) {
        $_SESSION['msg']="Test added successfully!";
        header("Location: http://localhost/cbtest/Addtest.php");
    exit;
    } else {
        $_SESSION['msg']="Error: " . $conn->error;
        header("Location: http://localhost/cbtest/Addtest.php");
    exit;
        
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    $_SESSION['msg']="Invalid request!";
    header("Location: http://localhost/cbtest/Addtest.php");
    exit;
}
?>
