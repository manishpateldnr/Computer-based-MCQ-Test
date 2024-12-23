<?php
session_start();
include_once '../db/db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = trim($_POST['name']);
    $subj_code = trim($_POST['subj_code']);
    $credit = intval($_POST['credit']); // Ensure credit is an integer

    // Basic validation
    if (empty($name) || empty($subj_code) || $credit <= 0) {
        $_SESSION['msg'] = "All fields are required, and credit must be greater than 0.";
        header("Location: ../Addsubject.php");
        exit;
    }

    // Check if subject code already exists
    $check_sql = "SELECT * FROM subjects WHERE subj_code = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $subj_code);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['msg'] = "Subject code already exists.";
        $stmt_check->close();
        header("Location: ../Addsubject.php");
        exit;
    }
    $stmt_check->close();

    // Insert the new subject
    $insert_sql = "INSERT INTO subjects (name, subj_code, credit) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);

    if ($stmt_insert) {
        $stmt_insert->bind_param("ssi", $name, $subj_code, $credit);
        if ($stmt_insert->execute()) {
            $_SESSION['msg'] = "Subject added successfully.";
            header("Location: ../Subjectlist.php");
        } else {
            $_SESSION['msg'] = "Failed to add subject. Try again.";
            header("Location: ../Addsubject.php");
        }
        $stmt_insert->close();
    } else {
        $_SESSION['msg'] = "Error preparing query: " . $conn->error;
        header("Location: ../Addsubject.php");
    }
} else {
    $_SESSION['msg'] = "Invalid request.";
    header("Location: ../Addsubject.php");
}

$conn->close();
?>
