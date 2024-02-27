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
    <title>Welcome | Register New User</title>
    <link rel="stylesheet" href="style.css">
    <?php include 'btrpcss.php'; ?>
</head>

<body>
    <?php
    if (isset($_POST['registerUser'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $emailadd = $_POST['emailadd'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($firstname == '' || empty($firstname)) {
    ?>
            <div class="alert alert-danger" role="alert">
                Please Enter The First Name
            </div>
        <?php
        } else if ($lastname == '' || empty($lastname)) {
        ?>
            <div class="alert alert-danger" role="alert">
                Please Enter The Last Name
            </div>
        <?php
        } else if ($emailadd == '' || empty($emailadd)) {
        ?>
            <div class="alert alert-danger" role="alert">
                Please Enter The Email Address
            </div>
        <?php
        } else if ($username == '' || empty($username)) {
        ?>
            <div class="alert alert-danger" role="alert">
                Please Enter The Username
            </div>
        <?php
        } else if ($password == '' || empty($password)) {
        ?>
            <div class="alert alert-danger" role="alert">
                Please Enter A Strong Password
            </div>
            <?php
        } else {
            $insertquery = "INSERT INTO `users` (`firstname`, `lastname`, `emailadd`, `username`, `password`) VALUES ('$firstname','$lastname','$emailadd','$username','$password')";
            if ($connection->query($insertquery) === TRUE) {
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
                    $mail->addAddress($emailadd, $lastname);     //Add a recipient
                    //$mail->addCC('hussainnotifications@gmail.com', 'Riyasath Hussain');
                    //$mail->addReplyTo('hussainnotifications@gmail.com', 'Riyasath Hussain');
                    $mail->isHTML(true);                                //Set email format to HTML
                    $mail->Subject = 'New User Registration | easy4gem';
                    $mail->Body    = 'Dear ' . $lastname . ',<br><br> Please use the below username and password to login to easy4gem site.<br><br> Username - ' . $username . '<br>Password - ' . $password . '<br>Site Link - easy4gem.com';

                    $mail->send();
            ?>
                    <div class="alert alert-success" role="alert">
                        Registraion Success, login details have been sent to registered email address
                    </div>
    <?php
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }
    }
    ?>
    <form action="" method="post">
        <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h1>Register New User</h1>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="firstname" class="form-control" id="firstNameLabel" placeholder="First Name">
                        <label for="firstNameLabel">First Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="lastname" class="form-control" id="lastnameLabel" placeholder="Last Name">
                        <label for="lastnameLabel">Last Name</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="email" name="emailadd" class="form-control" id="emailaddlabel" placeholder="Email Address">
                        <label for="emailaddlabel">Email Address</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username">
                        <label for="floatingInput">Username</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary mt-3 mb-3 w-100" name="registerUser">Register</button>
                </div>

            </div>
        </div>
    </form>

    <?php include 'btrpjs.php'; ?>
</body>

</html>