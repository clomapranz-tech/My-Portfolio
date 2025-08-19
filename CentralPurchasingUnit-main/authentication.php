<?php
include('config/dbcon.php');
if(!isset($_SESSION['auth']))
{
    $_SESSION['message'] = "Please login to proceed.";
    header('location: login.php');
    exit(0);
}

?>