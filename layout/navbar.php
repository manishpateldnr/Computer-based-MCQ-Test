<nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="./">
                <img src="./assests/images/icon.jpg" width="30"
                    height="24" class="d-inline-block align-text-top">
                Online Exam
                <?php   
              if (isset($_SESSION['loged_in'])) {
          ?>
        </a>
          <span class="text-light">Welcome :  <?php echo $_SESSION['name']; ?></span>
          <?php } ?>
            
            <div class="d-flex" role="search">
                <?php 
                    if (isset($_SESSION['loged_in'])) {
                ?>
                <a href="./logout.php" class="btn btn-danger">Logout</a>
                
                <?php
                    }else{
                        ?>
                        <a href="./Flogin.php" class="btn btn-light mx-2">Faculty</a>
                        <a href="./Alogin.php" class="btn btn-light mx-2">Admin</a>
                        <a href="./Slogin.php" class="btn btn-light">Login</a>
                    <?php } ?>
            </div>
        </div>
    </nav>
