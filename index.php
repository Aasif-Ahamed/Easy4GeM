<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
    <?php include 'btrpcss.php'; ?>
</head>

<body>
    <?php
    //include 'navbar.php';
    if (isset($_POST['ploginBtn'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $loginquery = "SELECT * FROM `users` WHERE `username`= '$username' AND `password`='$password'";
        $qres = $connection->query($loginquery);
        if ($qres->num_rows > 0) {
            while ($qresrow = $qres->fetch_assoc()) {
                $_SESSION['userid'] = $qresrow['userid'];
                header('Location:dashboard.php');
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">
            <i class="fa-solid fa-circle-exclamation"></i> Invalid Credentials. Please Try Again
          </div>';
        }
    }
    ?>

    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h1>Welcome</h1>
            </div>
            <div class="col-md-12">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username">
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 mb-3 w-100" name="ploginBtn">Login</button>
                    <!-- <button type="button" class="btn btn-secondary mt-3 mb-3" name="forgotpass">Forgot Password ?</button> -->
                </form>
            </div>
        </div>
    </div>


    <?php include 'btrpjs.php'; ?>
</body>

</html>