<?php
session_start();
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'student') {
    // Redirect to login page or show error message
    header("Location: Slogin.php");
    exit;
}
include_once "./db/db.php";
$test_id = isset($_GET['test_id']) ? $_GET['test_id'] : null;
$_SESSION['test_id'] = $test_id;

$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ?");
$stmt->bind_param("i", $test_id);
$stmt->execute();
$result = $stmt->get_result();
$current_test = $result->fetch_assoc();

date_default_timezone_set('Asia/Kolkata');
$start_timestamp = strtotime($current_test['start_date'] . ' ' . $current_test['start_time']. ':00');
$duration_minutes = (int) $current_test['duration'];
$end_timestamp = $start_timestamp + ($duration_minutes * 60);

$end_time = date('Y-m-d H:i:s', $end_timestamp); // Format the end time

if (time() >= $end_timestamp) {
    $_SESSION['msg'] = "This test has ended";
    header("Location: Sdashboard.php");
    exit;
}

$sql = "SELECT COUNT(*) as attempt_count 
        FROM results 
        WHERE test_id = ? AND student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $test_id, $_SESSION['student_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['attempt_count'] > 0) {
    $_SESSION['msg']="You have already given this test.";
    header("Location: Sdashboard.php");
    exit;
}

// Query to select 10 random questions
$sql = "SELECT q_id,question,option1,option2,option3,option4,mark FROM mcq ORDER BY RAND() LIMIT 10";

// Execute the query
$result = $conn->query($sql);

// Initialize an array to store questions
$questions = [];

if ($result->num_rows > 0) {
    // Fetch data and store in array
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'q_id' => $row['q_id'],
            'question' => $row['question'],
            'option1' => $row['option1'],
            'option2' => $row['option2'],
            'option3' => $row['option3'],
            'option4' => $row['option4'],
            'mark' => $row['mark'],
        ];
    }
}
// Close the connection
$conn->close();
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "./layout/hlink.php"?>
    <title>Test Paper</title>
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
    <section class=" d-flex w-100">
        <div class="left-side col-3 bg-success-subtle">
            <p class="text-center mt-5 text-danger">time left</p>
            <h6 class="text-center" id="counting"></h6>
            <div class="container d-flex flex-wrap  justify-content-evenly mt-1">
                <button class="btn btn-danger mt-3" id="1" style="flex: 0 0 calc(33.333% - 1rem);">1</button>
                <button class="btn btn-danger mt-3" id="2" style="flex: 0 0 calc(33.333% - 1rem);">2</button>
                <button class="btn btn-danger mt-3" id="3" style="flex: 0 0 calc(33.333% - 1rem);">3</button>
                <button class="btn btn-danger mt-3" id="4" style="flex: 0 0 calc(33.333% - 1rem);">4</button>
                <button class="btn btn-danger mt-3" id="5" style="flex: 0 0 calc(33.333% - 1rem);">5</button>
                <button class="btn btn-danger mt-3" id="6" style="flex: 0 0 calc(33.333% - 1rem);">6</button>
                <button class="btn btn-danger mt-3" id="7" style="flex: 0 0 calc(33.333% - 1rem);">7</button>
                <button class="btn btn-danger mt-3" id="8" style="flex: 0 0 calc(33.333% - 1rem);">8</button>
                <button class="btn btn-danger mt-3" id="9" style="flex: 0 0 calc(33.333% - 1rem);">9</button>
                <button class="btn btn-danger mt-3" id="10" style="flex: 0 0 calc(33.333% - 1rem);">10</button>
            </div>
            <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-success mb-4" onclick="submitform();">submit</button>
            </div>
        </div>
        <div class="right-side col-9">
            <div class="container d-flex flex-column">
                <h3 class="text-center mt-4">Test Started : <?php echo $current_test['name']; ?></h3>
                
            <form action="./backend/save_results.php" method="post" id="myForm">
                <?php
                foreach ($questions as $index => $question) {
                    $qNumber = $index + 1; // Question number
                    echo '<div class="row bg-success-subtle m-4 p-2 rounded">';
                    echo '<p>Q ' . $qNumber . '. ' . htmlspecialchars($question['question']) . '</p>';
                    echo '<div class="form-check ms-5">';
                    echo '<input class="form-check-input"  type="radio" name="q_' . $question['q_id'] . '" id="q_' . $question['q_id'] . '_1" value="' . $question['option1'] . '"data-q="' . $qNumber . '">';
                    echo '<label class="form-check-label" for="q_' . $question['q_id'] . '_1">';
                    echo htmlspecialchars($question['option1']);
                    echo '</label>';
                    echo '</div>';
                    echo '<div class="form-check ms-5">';
                    echo '<input class="form-check-input" type="radio" name="q_' . $question['q_id'] . '" id="q_' . $question['q_id'] . '_2" value="' . $question['option2'] . '" data-q="' . $qNumber . '">';
                    echo '<label class="form-check-label" for="q_' . $question['q_id'] . '_2">';
                    echo htmlspecialchars($question['option2']);
                    echo '</label>';
                    echo '</div>';
                    echo '<div class="form-check ms-5">';
                    echo '<input class="form-check-input" type="radio" name="q_' . $question['q_id'] . '" id="q_' . $question['q_id'] . '_3" value="' . $question['option3'] . '" data-q="' . $qNumber . '">';
                    echo '<label class="form-check-label" for="q_' . $question['q_id'] . '_3">';
                    echo htmlspecialchars($question['option3']);
                    echo '</label>';
                    echo '</div>';
                    echo '<div class="form-check ms-5">';
                    echo '<input class="form-check-input" type="radio" name="q_' . $question['q_id'] . '" id="q_' . $question['q_id'] . '_4" value="' . $question['option4'] . '" data-q="' . $qNumber . '">';
                    echo '<label class="form-check-label" for="q_' . $question['q_id'] . '_4">';
                    echo htmlspecialchars($question['option4']);
                    echo '</label>';
                    echo '</div>';
                    echo '<div class="form-check ms-5" style="display: none;">';
                    echo '<input checked class="form-check-input" type="radio" name="q_' . $question['q_id'] . '" id="q_' . $question['q_id'] . '_5" value="' . null . '">';
                    echo '<label class="form-check-label" for="q_' . $question['q_id'] . '_5">';
                    echo "0";
                    echo '</label>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </form>
            </div>
        </div>
    </section>

    <script>
        const endTime = '<?php echo $end_time; ?>';
        const endTimestamp = new Date(endTime).getTime(); // Convert to timestamp
        const countingvar = document.getElementById('counting');
    // Update the remaining time every second
    const countdownInterval = setInterval(function() {
        const now = new Date().getTime(); // Get the current time
        const remainingTime = endTimestamp - now; // Calculate the remaining time in ms
        const totalSeconds = Math.floor(remainingTime / 1000);  // Convert remaining time to seconds

        const minutes = Math.floor(totalSeconds / 60);  // Get full minutes
        const seconds = totalSeconds % 60;  // Get remaining seconds

        if (minutes > 0) {
            countingvar.textContent = minutes + " minute" + (minutes > 1 ? "s" : "") + " " + seconds + " second" + (seconds !== 1 ? "s" : "");
        } else {
            countingvar.textContent = seconds + " second" + (seconds !== 1 ? "s" : "");
        }


        if (remainingTime <= 0) {
            // Time is up, submit the form
            document.getElementById('myForm').submit();
            clearInterval(countdownInterval);
        }
    }, 1000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Attach event listeners to all radio buttons
        const radios = document.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            radio.addEventListener("change", () => {
                // Extract the question ID from the name attribute of the radio button
                const questionId = radio.getAttribute('data-q');
                // Find the corresponding button by its ID
                const button = document.getElementById(questionId);
                if (button) {
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }
            });
        });
    });

    function submitform(){
        document.getElementById('myForm').submit();
    }
</script>
</body>

</html>