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
</head>

<body>
<?php include_once './layout/navbar.php'; ?>

    <div class="container card my-3 w-50 shadow py-2">
        <h2 class="text-center mb-4">Add Subject</h2>
        <form action="./backend/addsubject.php" method="post">
            <div class="d-flex justify-content-evenly">
                <div class="left ">
                    <!-- Test Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Subject Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Subject name"
                            required>
                    </div>
                    <!-- Start Date -->
                    <div class="mb-3">
                        <label for="subj_code" class="form-label">Subject Code</label>
                        <input type="text" name="subj_code" id="subj_code" class="form-control" placeholder="Enter Subject Code" required>
                    </div>
                </div>

                <div class="right">
                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="credit" class="form-label">Credit</label>
                        <input type="number" name="credit" id="credit" class="form-control"
                            placeholder="Enter credit" required>
                    </div>
                    <div class="mb-3">
                        <label for="test_type" class="form-label">Assign Faculties</label>
                        <input type="text" class="form-control" placeholder="after subject add" disabled>
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
    
</body>

</html>
