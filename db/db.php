<?php
$servername = "localhost"; // Change as needed
$username = "root";        // Change as needed
$password = "";            // Change as needed
$dbname = "cbtest"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>