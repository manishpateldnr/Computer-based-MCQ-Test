<?php
include_once '../db/db.php';
session_unset(); 
session_start();
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and retrieve form inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Consider hashing passwords before saving them in the database

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['msg']="email password required!";
        header("Location: /cbtest/Flogin.php");
        exit;
    }

    // Query to check user credentials
    $stmt = $conn->prepare("SELECT id,name, email, password FROM faculties WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) { // Assuming passwords are hashed using password_hash()
            // Store user data in the session
            
            $_SESSION['loged_in'] = true;
            $_SESSION['faculty_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = "faculty";

            header("Location: http://localhost/cbtest/Fdashboard.php");
            exit;
        } else {
            $_SESSION['msg']="Invalid credentials!";
            header("Location: /cbtest/Flogin.php");
            exit;
        }
    } else {
        $_SESSION['msg']="Invalid credentials!";
        header("Location: /cbtest/Flogin.php");
        exit;
    }

    $stmt->close();
}
?>