<?php
session_start();
// if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'admin') {
//     // Redirect to login page or show error message
//     header("Location: Flogin.php");
//     exit;
// }

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
        <h2 class="text-center mb-4">Add Subject</h2>
        <form action="./backend/addfaculty.php" method="post">
            <div class="d-flex justify-content-evenly">
                <div class="left ">
                    <!-- Test Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Faculty Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Faculty name"
                            required>
                    </div>
                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                    </div>
                </div>

                <div class="right">
                    <!-- Duration -->
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

                    <!-- <div class="mb-3">
                        <label for="subj_code" class="form-label">Role</label>
                        <select name="subj_code" id="subj_code" class="form-select" required onchange="checkSlot()">
                            <option value="" disabled selected>Select Role</option>
                            <option value="FACULTY">Faculty</option>
                            <option value="ADMIN">Admin</option>
                        </select>
                    </div> -->
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
