<?php
require 'config/function.php';

// Define constants for logout messages
define('LOGGED_OUT_SUCCESSFULLY', 'Logged Out Successfully.');
define('INACTIVITY_LOGOUT_MESSAGE', 'Logged Out Due To Inactivity For 15 Minutes, Please Login Again To Continue.');

// Check if the user is currently logged in
if (isset($_SESSION['loggedIn'])) {
    // Check if the logout is due to user inactivity
    $isInactivityLogout = isset($_GET['inactivity']) && $_GET['inactivity'] == 'true';

    // Redirect the user to the login page with an appropriate message
    if ($isInactivityLogout) {
        // Call the logoutSession function to destroy login sessions
        logoutSession();

        redirect('login.php', INACTIVITY_LOGOUT_MESSAGE);
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy all sessions
    } else {
                // Call the logoutSession function to destroy login sessions
        logoutSession();
        redirect('login.php', LOGGED_OUT_SUCCESSFULLY);
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy all sessions
    }
} else {

    // Redirect the user to the login page with a logout message
    redirect('login.php', LOGGED_OUT_SUCCESSFULLY);
    // If the user is not logged in, destroy all sessions
    session_unset();
    session_destroy();
}
?>
