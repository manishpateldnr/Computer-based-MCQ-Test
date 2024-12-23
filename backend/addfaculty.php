<?php
session_start();
include_once '../db/db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmpassword']);

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $_SESSION['msg'] = "All fields are required.";
        header("Location: ../Addfaculty.php");
        exit;
    }

    // Password confirmation check
    if ($password !== $confirmPassword) {
        $_SESSION['msg'] = "Passwords do not match.";
        header("Location: ../Addfaculty.php");
        exit;
    }

    // Check if email already exists
    $check_sql = "SELECT * FROM faculties WHERE email = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['msg'] = "Email already registered.";
        $stmt_check->close();
        header("Location: ../Addfaculty.php");
        exit;
    }
    $stmt_check->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert faculty into the database
    $insert_sql = "INSERT INTO faculties (name, email, password) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);

    if ($stmt_insert) {
        $stmt_insert->bind_param("sss", $name, $email, $hashedPassword);
        if ($stmt_insert->execute()) {
            $_SESSION['msg'] = "Faculty added successfully.";
            header("Location: ../Facultylist.php");
        } else {
            $_SESSION['msg'] = "Failed to add faculty. Try again.";
            header("Location: ../Addfaculty.php");
        }
        $stmt_insert->close();
    } else {
        $_SESSION['msg'] = "Error preparing query: " . $conn->error;
        header("Location: ../Addfaculty.php");
    }
} else {
    $_SESSION['msg'] = "Invalid request.";
    header("Location: ../Addfaculty.php");
}

$conn->close();
?>
