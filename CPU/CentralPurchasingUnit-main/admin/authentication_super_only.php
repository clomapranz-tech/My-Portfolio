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
    if($_SESSION['auth_role'] != "2")
    {
        $_SESSION['message'] = "Only Super User Can Access That";
        header('location: index.php');
        exit(0);
    }
    else
    {
        // echo "Welcome Admin";
    }
}
?>