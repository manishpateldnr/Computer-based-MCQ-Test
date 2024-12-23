<?php
session_start();
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'faculty') {
    // Redirect to login page or show error message
    header("Location: Flogin.php");
    exit;
}

if (isset($_SESSION['msg'])) {
    $message = $_SESSION['msg'];
    echo "<script>alert('$message');</script>";
}
unset($_SESSION['msg']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Test</title>
    <?php include "./layout/hlink.php"?>
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<?php include_once './layout/navbar.php'; ?>

    <div class="container card my-3 w-50 shadow py-2">
        <h2 class="text-center mb-4">Add Test</h2>
        <form action="./backend/save_test.php" method="post">
            <div class="d-flex justify-content-evenly">
                <div class="left ">
                    <!-- Test Type -->
                    <div class="mb-3">
                        <label for="test_type" class="form-label">Test Type</label>
                        <select name="test_type" id="test_type" class="form-select" required>
                            <option value="" disabled selected>Select test type</option>
                            <option value="MCQ">MCQ</option>
                            <option value="SHORT" disabled>Short</option>
                            <option value="LONG" disabled>Long</option>
                        </select>
                    </div>
                    <!-- Test Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Test Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter test name"
                            required>
                    </div>
                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                </div>

                <div class="right">
                    <!-- Start time -->
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                    </div>

                    <!-- Subjects -->
                    <div class="mb-3">
                        <label for="subj_code" class="form-label">Subject</label>
                        <select name="subj_code" id="subj_code" class="form-select" required onchange="checkSlot()">
                            <option value="" disabled selected>Select Subject</option>
                            <option value="CUTM1018">PHP</option>
                            <option value="CUTM1019">JAVA</option>
                        </select>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (in minutes)</label>
                        <input type="number" name="duration" id="duration" class="form-control"
                            placeholder="Enter duration" required>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <p id="slot_result"></p>
                <button type="submit" id="submitbtn" class="btn btn-primary btn-lg">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script>

    // Function to check the slot availability
    function checkSlot() {
        let date = $("#start_date").val();
        let time = $("#start_time").val();

        // Validate inputs
        if (!date || !time) {
            $("#slot_result").html("<span style='color: orange;'>Please select both date and time.</span>");
            return;
        }

        // Send AJAX request
        $.ajax({
            url: "http://localhost/cbtest/backend/check_slot.php", // Replace with your backend file path
            type: "POST",
            data: {
                start_date: date,
                start_time: time
            },
            success: function (response) {
                // Update result based on the server response
                if (response === "available") {
                    $("#slot_result").html("<span style='color: green;'>Slot is available!</span>");
                    $("#submitbtn").prop("disabled", false).show(); // Enable the submit button if slot is available
                } else if (response === "unavailable") {
                    $("#slot_result").html("<span style='color: red;'>Slot is unavailable.</span>");
                    $("#submitbtn").prop("disabled", true).hide(); // Disable the submit button if slot is unavailable
                } else {
                    $("#slot_result").html("<span style='color: orange;'>Error: " + response + "</span>");
                    $("#submitbtn").prop("disabled", true).hide(); // Disable the submit button on error
                }
            },
            error: function () {
                $("#slot_result").html("<span style='color: red;'>Failed to check the slot. Try again.</span>");
                $("#submitbtn").prop("disabled", true).hide(); // Disable the submit button on error
            }
        });
    }
    </script>
</body>

</html>
