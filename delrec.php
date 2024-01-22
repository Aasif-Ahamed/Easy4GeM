<?php
include 'config.php';
$reqtype = $_GET['reqtype'];
$reqid = $_GET['vmxc'];
$filename = $_GET['imgpth'];
$aorb = $_GET['aorb'];
$delqurone = "DELETE  FROM `masterdata` WHERE `id`=$reqid";
if (mysqli_query($connection, $delqurone)) {
    if (file_exists($filename)) {
        unlink($filename);
    }
    if ($aorb == 'a') {
        header("Location:dashboardA.php");
    } else if ($aorb == 'b') {
        header("Location:dashboardB.php");
    }
} else {
    echo "ERROR: Could not able to execute $delqurone. " . mysqli_error($connection);
}
