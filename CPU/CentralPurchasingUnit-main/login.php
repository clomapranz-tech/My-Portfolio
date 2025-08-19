<?php
session_start();
include('config/dbcon.php');
include('config.php');

use Google\Service\Oauth2 as Google_Service_Oauth2;

// Check if a message URL parameter exists
if (isset($_GET['message'])) {
    $message = '';
    switch ($_GET['message']) {
        case 'email_domain_not_allowed':
            $message = "You cannot use this email domain. Please log in with a valid email address.";
            break;
            // Add more cases if needed
    }
    $_SESSION['message'] = $message;
}

if (isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You are already logged in";
    header('Location: index.php');
    exit(0);
}

$login_button = '';

// Google OAuth Process
if (isset($_GET["code"])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if (!isset($token['error'])) {
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];

        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        if (!empty($data['given_name'])) $_SESSION['user_first_name'] = $data['given_name'];
        if (!empty($data['family_name'])) $_SESSION['user_last_name'] = $data['family_name'];
        if (!empty($data['email'])) $_SESSION['user_email_address'] = $data['email'];
        if (!empty($data['gender'])) $_SESSION['user_gender'] = $data['gender'];
        if (!empty($data['picture'])) $_SESSION['user_image'] = $data['picture'];
    }
}

// Check if the user is already authenticated using Google
if (isset($_SESSION['access_token'])) {
    $google_client->setAccessToken($_SESSION['access_token']);
    $google_service = new Google_Service_Oauth2($google_client);
    $data = $google_service->userinfo->get();

    $email = $data['email'];
    $fname = $data['given_name'];
    $lname = $data['family_name'];

    // Check if the email matches allowed domains
    $allowedDomains = ['my.xu.edu.ph', 'xu.edu.ph'];
    $emailDomain = substr($email, strrpos($email, '@') + 1);
    if (in_array($emailDomain, $allowedDomains)) {
        // Check if the user already exists in the database
        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $user_id = $user['id'];
            $role_as = $user['role_as'];
        } else {
            // Insert user into the database if not already exists
            $insert_query = "INSERT INTO users (fname, lname, email) VALUES ('$fname', '$lname', '$email')";
            mysqli_query($con, $insert_query);

            // Get the newly inserted user ID
            $user_id = mysqli_insert_id($con);
            $role_as = 2; // Assuming '2' is for regular user role
        }

        // Set session variables
        $_SESSION['auth'] = true;
        $_SESSION['auth_user'] = [
            'user_name' => $fname . ' ' . $lname,
            'user_email' => $email,
            'user_id' => $user_id,
        ];
        $_SESSION['auth_role'] = $role_as;

        // Redirect to the appropriate page
        $_SESSION['message'] = "Welcome $fname $lname!";
        header('Location: index.php');
        exit;
    } else {
        // If email domain is not allowed, revoke token and log out the user
        unset($_SESSION['auth'], $_SESSION['auth_user'], $_SESSION['auth_role']);
        $google_client->revokeToken($_SESSION['access_token']);
        session_destroy();

        header('Location: login.php?message=email_domain_not_allowed');
        exit;
    }
}

// Display Google login button if not authenticated
if (!isset($_SESSION['access_token'])) {
    $login_button = '<h4 class="btn bg-blue-500 text-cyan-50 hover:bg-blue-300 hover:text-cyan-50"><a href="' . $google_client->createAuthUrl() . '">Login with Google</a></h4>';
}

include('includes/header.php');
// include('includes/navbar_index.php');
?>

<style>
    .login-background {
        position: relative;
        background-image: url('assets/images/background_xu.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        overflow: hidden;
    }

    .login-background::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1;
    }

    .login-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2;
    }

    .login-form-wrapper {
        position: relative;
        z-index: 2;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        padding: 30px;
        width: 100%;
        max-width: 400px;
    }

    .login-logo {
        position: absolute;
        top: -50px;
        left: 50%;
        transform: translateX(-50%);
        max-width: 190px;
        z-index: 3;
    }
</style>

<div class="login-background">
    <div class="login-container">
        <div class="login-form-wrapper">
            <!-- Add logo at the top, center it and overflow above the form -->
            <img src="assets/images/XU_Logotype_Logo.png" alt="Logo" class="login-logo"> 

            <div class="py-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <?php include('message.php'); ?>
                            <form action="logincode.php" method="POST">
                                <div class="form-group mb-3">
                                    <label>Username</label>
                                    <input required type="email" name="email" placeholder="Enter Email Address" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Password</label>
                                    <input  type="password" name="password" placeholder="Enter Password" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <button required type="submit" name="login_btn"
                                        class="btn bg-blue-500 text-cyan-50 hover:bg-blue-300 hover:text-black w-100">
                                        Login
                                    </button>
                                </div>
                                <!-- <div class="form-group mb-3">
                                    <button required type="submit" name="login_btn"
                                        class="btn bg-blue-500 text-cyan-50 hover:bg-blue-300 hover:text-cyan-50 w-100">
                                        Register
                                    </button>
                                </div> -->

                                <!-- <div class="form-group mb-3">
                                    <?php echo $login_button; ?>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php'); ?>