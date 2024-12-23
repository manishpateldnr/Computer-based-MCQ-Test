<?php
session_start();

// Check if the user is logged in and is an admin
// if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'admin') {
//     // Redirect to login page or show error message
//     header("Location: Alogin.php");
//     exit;
// }

// Include your database connection file
include_once '../db/db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subj_code = $_POST['subject'];
// Check if the file is uploaded
if (isset($_FILES['mcqs']) && $_FILES['mcqs']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['mcqs']['tmp_name'];
    $fileName = $_FILES['mcqs']['name'];

    // Check file extension (must be .csv)
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    if ($fileExtension !== 'csv') {
        $_SESSION['msg']="Please upload csv file!";
        header("Location: http://localhost/cbtest/Fdashboard.php");
        exit;
    }

    // Open the file
    if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
        $isHeader = true;

        // Read the CSV file row by row
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Skip the header row
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            // Assuming CSV format: Question, Option1, Option2, Option3, Answer
            $question = $row[0];
            $option1  = $row[1];
            $option2  = $row[2];
            $option3  = $row[3];
            $option4  = $row[4];
            $answer   = $row[5];

            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO mcq (subj_code, question, option1, option2, option3,option4, right_ans) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $subj_code, $question, $option1, $option2, $option3,$option4, $answer);
            $stmt->execute();
        }
        fclose($handle);

        $_SESSION['msg']="File uploaded and data inserted successfully.";
        header("Location: http://localhost/cbtest/Fdashboard.php");
        exit;
    } else {
        $_SESSION['msg']="Error opening the file.";
        header("Location: http://localhost/cbtest/Fdashboard.php");
        exit;
    }
} else {
    $_SESSION['msg']="File upload error.";
    header("Location: http://localhost/cbtest/Fdashboard.php");
    exit;
}

// Close database connection
$conn->close();
}
?>