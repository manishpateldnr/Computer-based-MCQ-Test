<?php
include_once '../db/db.php';
session_start();
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'student') {
    // Redirect to login page or show error message
    header("Location: Slogin.php");
    exit;
}
if (!isset($_SESSION['student_id']) || !isset($_SESSION['test_id'])) {
    // Redirect to sdashboard.php if either value is not set
    header("Location: http://localhost/cbtest/Sdashboard.php");
    exit; // Make sure the script stops executing after the redirect
}

$student_id = $_SESSION['student_id'];
$test_id = $_SESSION['test_id'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Iterate through submitted answer
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'q_') === 0) {
            $q_id = str_replace('q_', '', $key); // Extract question ID
            $student_ans = $value;              // Selected option

            // Fetch the correct answer and mark from the database
            $query = "SELECT right_ans, mark FROM mcq WHERE q_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $q_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $mcq = $result->fetch_assoc();

            // Determine the mark (1 if correct, 0 if incorrect)
            $mark = ($mcq['right_ans'] == $student_ans) ? $mcq['mark'] : 0;
            
            // Insert the result into the `results` table
            $insertQuery = "INSERT INTO results (test_id, student_id, q_id, student_ans, mark, createdAt)
                            VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iiisd", $test_id, $student_id, $q_id, $student_ans, $mark);
            $stmt->execute();
        }
    }
    $_SESSION['msg']="test submitted.";
}
// Redirect to a success or result page
header("Location: http://localhost/cbtest/Resultlist.php");
exit;
?>
