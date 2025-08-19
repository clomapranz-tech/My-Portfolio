<?php
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
    if($_SESSION['auth_role'] != "1" && $_SESSION['auth_role'] != "2")
    {
        $_SESSION['message'] = "Only CPU Staff can Access That";
        header('location: purchase_request-view.php');
        exit(0);
    }
    else
    {
        // echo "Welcome Admin";
    }
}
?>