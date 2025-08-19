<?php
session_start();
if(isset($_SESSION['auth']))
{
    $_SESSION['message'] = "You are already logged in";
    header('location: index.php');
    exit(0);
}

include('includes/header.php');
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <?php include('message.php'); ?>

               <div class="card">
                <div class="card-header">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    <form action="registercode.php" method="post">
                        <div class="form-group mb-3">
                            <label>First Name</label>
                            <input required type="text" name="fname" placeholder="Enter First Name" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Last Name</label>
                            <input required type="text" name="lname" placeholder="Enter Last Name" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Email ID</label>
                            <input required type="email" name="email" placeholder="Enter Email Address" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Password</label>
                            <input required type="password" name="password" placeholder="Enter Password" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Confirm Password</label>
                            <input required type="password" name="cpassword" placeholder="Confirm Password" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <button required type="submit" name="register_btn" class="btn btn-primary">Register Now</button>
                        </div>
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </form>

                </div>
               </div>
            </div>
</div>

<?php
include('includes/footer.php');
?>