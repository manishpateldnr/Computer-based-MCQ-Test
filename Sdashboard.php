
<?php
include_once "./db/db.php";
session_start();
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'student') {
    // Redirect to login page or show error message
    header("Location: Slogin.php");
    exit;
}

if (isset($_SESSION['msg'])) {
    $message = $_SESSION['msg'];
    echo "<script>alert('$message');</script>";
}
unset($_SESSION['msg']);

$sec = $_SESSION['sec'];
$dept = $_SESSION['dept']; // Assuming you store dept in session
$course = $_SESSION['course']; // Assuming you store course in session
$subj_registered = explode(",", $_SESSION['sub_registred']);


// Prepare placeholders for IN clause dynamically
$placeholders = implode(',', array_fill(0, count($subj_registered), '?')); // ?, ?, ?, ...
$types = str_repeat('s', count($subj_registered)); // 's' for each parameter

// SQL query
$sql = "
    SELECT *
    FROM tests
    WHERE CONCAT(start_date, ' ', start_time) <= NOW()
    AND DATE_ADD(CONCAT(start_date, ' ', start_time), INTERVAL duration MINUTE) >= NOW()
    AND subj_code IN ($placeholders)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$subj_registered);

$stmt->execute();
$result = $stmt->get_result();

$live_tests = array();

// Check if any results are returned
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    $live_tests[] = $row; // Store each test row in the $live array
}
}

$sql2 = "SELECT * 
        FROM tests 
        WHERE CONCAT(start_date, ' ', start_time) > NOW()
        AND subj_code IN ($placeholders) ";

$stmt = $conn->prepare($sql2);
$stmt->bind_param($types, ...$subj_registered);

$stmt->execute();
$result = $stmt->get_result();
$upcoming_tests= array();
    // Loop through each row and add it to the $live array
    if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    $upcoming_tests[] = $row; // Store each test row in the $live array
}
    }

$sql3 = "SELECT * 
        FROM tests 
        WHERE CONCAT(start_date, ' ', start_time) + INTERVAL duration MINUTE < NOW()
        AND subj_code IN ($placeholders)";

$stmt = $conn->prepare($sql3);
$stmt->bind_param($types, ...$subj_registered);

$stmt->execute();
$result = $stmt->get_result();
$past_tests= array();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    $past_tests[] = $row; // Store each test row in the $live array
}
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "./layout/hlink.php"?>
    <title>Student Dashboard</title>
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
            <div class="container d-flex flex-column mt-5">
                <a class="btn btn-primary mt-3" href="#">Material</a>
                <a class="btn btn-success mt-3" href="./Sdashboard.php">Test(live)</a>
                <a class="btn btn-primary mt-3" href="./Resultlist.php">Result</a>
            </div>
        </div>
        <div class="right-side col-9 ">
            <div class="container d-flex flex-column"> 
                <h3 class="text-center mt-4">Live tests</h3>
                <p>===live===</p>
                <?php foreach ($live_tests as $test): ?>
                    <div class="row bg-success-subtle m-4 p-2 rounded shadow">
                        <div class="col-4">
                        <p>Test ID: <?php echo $test['id']; ?></p>
                        <p>Test Name: <?php echo $test['name']; ?></p>
                        </div>
                        <div class="col-4">
                        <p>Total Questions: 10</p>
                        <p>Duration: <?php echo $test['duration']; ?> minutes</p>
                        </div>
                        <div class="col-4 ">
                        <p>Time : <?php echo $test['start_time'],", ", $test['start_date']; ?></p>
                        <a href="test.php?test_id=<?php echo $test['id']; ?>" class="btn btn-primary">Start</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <p>===upcoming===</p>
                <?php foreach ($upcoming_tests as $test): ?>
                    <div class="row bg-success-subtle m-4 p-2 rounded shadow">
                        <div class="col-4">
                        <p>Test ID: <?php echo $test['id']; ?></p>
                        <p>Test Name: <?php echo $test['name']; ?></p>
                        </div>
                        <div class="col-4">
                        <p>Total Questions: 10</p>
                        <p>Duration: <?php echo $test['duration']; ?> minutes</p>
                        </div>
                        <div class="col-4 ">
                        <p>Time : <?php echo $test['start_time'],", ", $test['start_date']; ?></p>
                        <a href="#" class="btn btn-primary bg-dark-subtle text-black" style="cursor: not-allowed">Start</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <p>===past===</p>
                <?php foreach ($past_tests as $test): ?>
                    <div class="row bg-success-subtle m-4 p-2 rounded shadow">
                        <div class="col-4">
                        <p>Test ID: <?php echo $test['id']; ?></p>
                        <p>Test Name: <?php echo $test['name']; ?></p>
                        </div>
                        <div class="col-4">
                        <p>Total Questions: 10</p>
                        <p>Duration: <?php echo $test['duration']; ?> minutes</p>
                        </div>
                        <div class="col-4 ">
                        <p>Time : <?php echo $test['start_time'],", ", $test['start_date']; ?></p>
                        <a href="#" class="btn btn-primary bg-dark-subtle text-black" style="cursor: not-allowed">Start</a>
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