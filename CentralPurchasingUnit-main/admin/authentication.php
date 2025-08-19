<?php
session_start();
include('config/dbcon.php');
if(!isset($_SESSION['auth']))
{
    $_SESSION['message'] = "Login to Access Dashboard";
    header('location: ../login.php');
    exit(0);
}
else
{
    //IF NOT ADMIN OR SUPER USER OR DEPARTMENT EDITOR OR UNIT HEAD THEN REDIRECT TO LOGIN PAGE
    if($_SESSION['auth_role'] != "1" && $_SESSION['auth_role'] != "2" && $_SESSION['auth_role'] != "3" && $_SESSION['auth_role'] != "4" && $_SESSION['auth_role'] != "5")
    {
        $_SESSION['message'] = "You are not authorized as Admin";
        header('location: ../login.php');
        exit(0);
    }
    else
    {
        // echo "Welcome Admin";
    }
}
?>