<?php
session_start();
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'student') {
    // Redirect to login page or show error message
    header("Location: Slogin.php");
    exit;
}
include_once "./db/db.php";
$student_id = $_SESSION['student_id'];

if (isset($_SESSION['msg'])) {
    $message = $_SESSION['msg'];
    echo "<script>alert('$message');</script>";
}
unset($_SESSION['msg']);

// SQL query
$sql = "SELECT t.duration, t.name AS test_name,r.test_id, r.student_id, SUM(r.mark) AS total_marks
        FROM results r
        LEFT JOIN tests t ON r.test_id = t.id
        WHERE r.student_id = ?
        GROUP BY r.test_id, r.student_id";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("i", $student_id); // "i" for integer type

// Execute query
$stmt->execute();

// Get result
$result = $stmt->get_result();

// Initialize an empty array to store the results
$liveTests = [];

// Fetch data and store in the array
while ($row = $result->fetch_assoc()) {
    $liveTests[] = [
        'test_id' => $row['test_id'],
        'test_name' => $row['test_name'],
        'student_id' => $row['student_id'],
        'duration' => $row['duration'],
        'total_marks' => $row['total_marks']
    ];
}

// Close the statement
$stmt->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "./layout/hlink.php"?>
    <title>Student Results</title>
    <style>
        .left-side{
            height: 89vh;
        }
        .right-side{
            height: 89vh;
            overflow: auto;
            overflow-x:hidden;
        }
    </style>
</head>

<body>
    <?php include_once './layout/navbar.php'; ?>

    <section class="body d-flex w-100">
        <div class="left-side col-3 bg-success-subtle">
            <div class="container d-flex flex-column  mt-5">
                <a class="btn btn-primary mt-3" href="#">Material</a>
                <a class="btn btn-primary mt-3" href="./Sdashboard.php">Test(live)</a>
                <a class="btn btn-success mt-3" href="./Resultlist.php">Result</a>
            </div>
        </div>
        <div class="right-side col-9 ">
            <div class="container d-flex flex-column"> 
                <h3 class="text-center mt-4">Results</h3>
            <?php foreach ($liveTests as $test): ?>
                <div class="row bg-success-subtle m-4 p-2 rounded shadow">
                    <div class="col-4">
                        <p>Test ID: <?php echo $test['test_id']; ?></p>
                        <p>Test Name: <?php echo $test['test_name']; ?></p>
                    </div>
                    <div class="col-4">
                        <p>Total Questions: 10</p>
                        <p>Duration: <?php echo $test['duration']; ?> minutes</p>
                    </div>
                    <div class="col-4">
                        <p>Marks: <?php echo $test['total_marks']; ?>/10</p>
                        <button class="btn btn-primary">See details</button>
                    </div>
                </div>
            <?php endforeach; ?>
                
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>