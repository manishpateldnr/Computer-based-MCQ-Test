<?php
session_start();
if (!isset($_SESSION['loged_in']) || $_SESSION['loged_in'] !== true || $_SESSION['user_type'] !== 'admin') {
}
else{
  header("Location: Addtest.php");
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
    <?php include "./layout/hlink.php"?>
    <title>CBtest | Admin login</title>
</head>
<body>
  <nav>
    <?php include_once './layout/navbar.php'; ?>
  </nav>
    <section class="vh-100 d-flex flex-column justify-content-center">
        <div class="container-fluid h-custom">
          <div class=" row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5 ">
              <img src="./assests/images/main.jpg"
                class="img-fluid rounded shadow" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
              <h2>Admin Login</h2>
              <form action="./backend/alogin.php" method="post">
                <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="email" name="email" id="form3Example3" class="form-control form-control-lg"
                    placeholder="Enter email address" />
                </div>
      
                <!-- Password input -->
                <div data-mdb-input-init class="form-outline mb-3">
                  <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
                    placeholder="Enter password" />
                </div>
      
                <div class="d-flex justify-content-between align-items-center">
                  <!-- Checkbox -->
                  <div class="form-check mb-0">
                    <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                    <label class="form-check-label" for="form2Example3">
                      Remember me
                    </label>
                  </div>
                  <a href="#!" class="text-body">Forgot password?</a>
                </div>
      
                <div class="text-center text-lg-start mt-4 pt-2">
                  <button  type="submit"  data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                    style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                  
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
      <?php include "./layout/footer.php" ?>
      <?php include "./layout/models.php" ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>