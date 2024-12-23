<?php
session_start();
include_once '../db/db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $reg = trim($_POST['reg']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $sec = trim($_POST['sec']);
    $dept = trim($_POST['dept']);
    $course = trim($_POST['course']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmpassword']);

    // Basic validation
    if (
        empty($reg) || empty($name) || empty($email) || empty($sec) ||
        empty($dept) || empty($course) || empty($password) || empty($confirmPassword)
    ) {
        $_SESSION['msg'] = "All fields are required.";
        header("Location: ../Addstudent.php");
        exit;
    }

    // Password confirmation check
    if ($password !== $confirmPassword) {
        $_SESSION['msg'] = "Passwords do not match.";
        header("Location: ../Addstudent.php");
        exit;
    }

    // Check if registration number already exists
    $check_sql = "SELECT * FROM students WHERE reg_no = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $reg);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['msg'] = "Registration number already exists.";
        $stmt_check->close();
        header("Location: ../Addstudent.php");
        exit;
    }
    $stmt_check->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the student details into the database
    $insert_sql = "INSERT INTO students (reg_no, name, email, sec, dept, course, password) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);

    if ($stmt_insert) {
        $stmt_insert->bind_param("sssssss", $reg, $name, $email, $sec, $dept, $course, $hashedPassword);
        if ($stmt_insert->execute()) {
            $_SESSION['msg'] = "Student added successfully.";
            header("Location: ../Adashboard.php");
        } else {
            $_SESSION['msg'] = "Failed to add student. Try again.";
            header("Location: ../Addstudent.php");
        }
        $stmt_insert->close();
    } else {
        $_SESSION['msg'] = "Error preparing query: " . $conn->error;
        header("Location: ../Addstudent.php");
    }
} else {
    $_SESSION['msg'] = "Invalid request.";
    header("Location: ../Addstudent.php");
}

$conn->close();
?>