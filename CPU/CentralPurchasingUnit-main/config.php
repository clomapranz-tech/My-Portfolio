<?php

// Google API configuration
define('GOOGLE_CLIENT_ID', '414301651467-g3knisjibh4sr6medoc2nbgbs6mr1mk4.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URL', 'http://localhost/CPU/login.php');

//Include Google Client Library for PHP autoload file
require_once 'admin/vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId(GOOGLE_CLIENT_ID);

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret(GOOGLE_CLIENT_SECRET);

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri(GOOGLE_REDIRECT_URL);

$google_client->addScope('email');
$google_client->addScope('profile');


?>