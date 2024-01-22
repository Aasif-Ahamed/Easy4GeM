<?php
// Start the session
session_start();
// Check if the user is already logged in
if (isset($_POST['btnLogout'])) {
    // Unset all session variables
    if (isset($_SESSION['userid'])) {
        session_unset();

        // Destroy the session
        session_destroy();

        // Redirect to the login page or any other page you want after logout
        header("Location: index.php");
        exit;
    } else {
        // If the user is not logged in, redirect to the login page
        header("Location: index.php");
        exit;
    }
}
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit;
}
