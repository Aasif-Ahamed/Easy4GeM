<?php
$dbname = 'projectgem';
$username = 'root';
$password = '';
$servername = 'localhost';

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Database Connection Error : " . mysqli_connect_error());
}
