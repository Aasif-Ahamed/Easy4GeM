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

    if (isset($_POST['passreset'])) {
        $regemail = $_POST['regemail'];
        if (empty($regemail) || $regemail == '') {
    ?>
            <div class="alert alert-danger" role="alert">
                Please enter your registered email address to reset the password
            </div>
    <?php
        } else {
            $searchdbquery = "SELECT * FROM `users` WHERE `emailadd` = '$regemail'";
            $searchdbqueryres = $connection->query($searchdbquery);
            if ($searchdbqueryres->num_rows > 0) {
                while ($resetrow = $searchdbqueryres->fetch_assoc()) {
                    $resetname = $resetrow['lastname'];
                    $resetid = $resetrow['userid'];
                    $resetlink = 'http://localhost/ProjectGem/passreset.php?vmxidval=' . $resetid . '';
                    $mail = new PHPMailer(true);
                    try {
                        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'node236.r-usdatacenter.register.lk';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'admin@easy4gem.com';                     //SMTP username
                        $mail->Password   = 'zt3$1E*!yeWq';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        $mail->setFrom('admin@easy4gem.com', 'Easy4Gem');
                        $mail->addAddress($regemail);     //Add a recipient
                        //$mail->addCC('hussainnotifications@gmail.com', 'Riyasath Hussain');
                        //$mail->addReplyTo('hussainnotifications@gmail.com', 'Riyasath Hussain');
                        $mail->isHTML(true);                                //Set email format to HTML
                        $mail->Subject = 'Password Reset Request';
                        $mail->Body    = 'Dear ' . $resetname . ',<br><br> Please use the below link to reset your password.<br><br>' . $resetlink . '';

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            }
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
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary mt-3 mb-3 w-100" name="ploginBtn">Login</button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-secondary w-100 mt-3 mb-3" name="forgotpass" data-bs-toggle="modal" data-bs-target="#forgotPassword">Forgot Password ?</button>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="forgotPassword" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="forgotPasswordLabel">Password Reset</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Enter Your Email Address</h4>
                                <p>You will recieve the link to reset your password to the registered email.</p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <input type="email" required name="regemail" class="form-control" id="floatingInput" placeholder="name@example.com">
                                    <label for="floatingInput">Email address</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="passreset" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'btrpjs.php'; ?>
</body>

</html>