<?php
require 'config/function.php';

// Define constants for logout messages
define('LOGGED_OUT_SUCCESSFULLY', 'Logged Out Successfully.');
define('INACTIVITY_LOGOUT_MESSAGE', 'Logged Out Due To Inactivity For 15 Minutes, Please Login Again To Continue.');

$isInactivityLogout = isset($_GET['inactivity']) && $_GET['inactivity'] === 'true';

logoutSession();
session_unset();
session_destroy();

redirect(
    'login.php',
    $isInactivityLogout ? INACTIVITY_LOGOUT_MESSAGE : LOGGED_OUT_SUCCESSFULLY
);
?>
