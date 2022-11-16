<?php
// Initialize the session
session_start();
require_once "./config.php";

// Get URI and remove basepath
$_GET['basepath'] = dirname($_SERVER['PHP_SELF']);
if ($_GET['basepath'] == "/" || $_GET['basepath'] == "\\") {
    $_GET['basepath'] = "";
}
$_GET['imgpath'] = dirname(dirname($_SERVER['PHP_SELF']));
$uri = substr($_SERVER['REQUEST_URI'], strlen($_GET['basepath']));

// Check for query
if (strstr($uri, '?')) {
    // remove querystring
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// seperate URI
$params = explode("/", trim($uri, "/"));

// walk through params
foreach ($params as $key => $value) {
    $_GET['v' . $key] = trim($value);
}


// select page based on parameter
switch ($_GET['v0']) {
    case 'registreren':
        include_once 'auth/register.php';
        break;
    case 'Inloggen':
        include_once 'auth/login.php';
        break;    
    case 'Logout':
        include_once 'auth/logout.php';
        break;
    case 'formulier':
        include_once 'screens/form.php';
        break;
    case 'SaveForm':
        include_once 'functions/data/SaveForm.php';
        break;
    case 'GetForm':
        include_once 'functions/data/GetForm.php';
        break;
    default:
        if (isset($_SESSION["loggedin"])) {
            include_once 'screens/profile.php';
        } else {
            include_once 'screens/home.php';
        }
}