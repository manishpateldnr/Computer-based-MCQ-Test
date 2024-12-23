<?php
session_start();
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'admin') {
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
    <style>
        /* Optional: Initial style */
        .error {
            border: 2px solid red;
        }
        .valid {
            border: 2px solid green;
        }
    </style>
</head>

<body>
<?php include_once './layout/navbar.php'; ?>

    <div class="container card my-3 w-50 shadow py-2">
        <h2 class="text-center mb-4">Add Student</h2>
        <form action="./backend/addstudent.php" method="post">
            <div class="d-flex justify-content-evenly">
                <div class="left ">

                    <div class="mb-3">
                        <label for="reg" class="form-label">Reg. No.</label>
                        <input type="text" name="reg" id="reg" class="form-control" placeholder="Enter Reg. No."
                            required>
                    </div>
                    <!-- Test Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Student name"
                            required>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="sec" class="form-label">Section</label>
                        <select name="sec" id="sec" class="form-select" required >
                            <option value="" disabled selected>Select Department</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>

                <div class="right">
                    <div class="mb-3">
                        <label for="dept" class="form-label">Department</label>
                        <select name="dept" id="dept" class="form-select" required >
                            <option value="" disabled selected>Select Department</option>
                            <option value="CSE">CSE</option>
                            <option value="EEE">EEE</option>
                            <option value="ECE">ECE</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="course" class="form-label">Course</label>
                        <select name="course" id="course" class="form-select" required>
                            <option value="" disabled selected>Select Department</option>
                            <option value="Btech">B. tech.</option>
                            <option value="Mtech">M. tech.</option>
                            <option value="Bsc">B.Sc.</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmpassword" class="form-label">Confirm Password</label>
                        <input type="text" name="confirmpassword" id="confirmpassword" class="form-control"
                            placeholder="Confirm Password" required onkeyup="confirmpass()">
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
    function confirmpass() {
            var pass1 = document.getElementById("password").value;
            var pass2 = document.getElementById("confirmpassword").value;
            var confirmInput = document.getElementById("password");
            var submitBtn = document.getElementById("submitbtn");

            if (pass1 !== pass2) {
                // If passwords don't match, apply red border and disable submit button
                confirmInput.classList.add("error");
                confirmInput.classList.remove("valid");
                submitBtn.disabled = true;
            } else {
                // If passwords match, apply green border and enable submit button
                confirmInput.classList.remove("error");
                confirmInput.classList.add("valid");
                submitBtn.disabled = false;
            }
        }
    </script>
</body>

</html>
