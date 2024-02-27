<?php
ob_start();
include 'config.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Me Password</title>
    <link rel="stylesheet" href="style.css">
    <?php include 'btrpcss.php'; ?>
</head>

<body>
    <?php
    if (isset($_POST['passreset'])) {
        $resetid = $_GET['vmxidval'];
        $newpass = $_POST['newpass'];
        $cpass = $_POST['cpass'];

        if ($resetid == '' || empty($resetid)) {
    ?>
            <div class="alert alert-danger" role="alert">
                Internal Error, Please Request A New Password Request
            </div>
        <?php
        } else if ($newpass == '' || empty($newpass)) {
        ?>
            <div class="alert alert-danger" role="alert">
                New Password Cannot Be Empty
            </div>
        <?php
        } else if ($cpass == '' || empty($cpass)) {
        ?>
            <div class="alert alert-danger" role="alert">
                Confirm New Password Cannot Be Empty
            </div>
    <?php
        } else {
            $updatequery = "UPDATE `users` SET `password` = '$newpass'";
            if ($connection->query($updatequery) === TRUE) {
                header('Location:index.php');
            } else {
                echo 'An error occured ' . $connection->error;
            }
        }
    }
    ?>

    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h1>Reset My Password</h1>
            </div>
            <div class="col-md-12">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" name="newpass" class="newpass form-control" id="newPassLabel" placeholder="New Password">
                        <label for="newPassLabel">New Password</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" name="cpass" class="cpass form-control" id="confirmNewPass" placeholder="Re-Type New Password">
                        <label for="confirmNewPass">Re-Type New Password</label>
                    </div>
            </div>
            <div class="col-md-12">
                <button type="submit" name="passreset" id="ResetBtn" class="btn btn-primary mt-3 mb-3 w-100" name="ploginBtn">Reset</button>
            </div>
            <div class="col-md-12">
                <div id="customcontent"></div>
            </div>
            </form>
        </div>
    </div>

    <?php include 'btrpjs.php'; ?>
</body>

</html>
<script>
    $(document).ready(function() {
        var submitBtn = document.getElementById('ResetBtn');
        submitBtn.disabled = true;

        $('.newpass, .cpass').on('input change', function() {
            var newPassword = document.getElementById('newPassLabel').value;
            var confirmedPassword = document.getElementById('confirmNewPass').value;

            console.log(confirmedPassword);
            console.log(newPassword);

            if (confirmedPassword === newPassword) {
                console.log('True');
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        });
    });
</script>