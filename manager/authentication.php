<?php
// Check if the user is logged in
if (isset($_SESSION['loggedIn'])) {
    // Retrieve and validate user data from the session
    $username = validate($_SESSION['loggedInUser']['username']);
    $roleID = validate($_SESSION['loggedInUser']['roleID']);
    
    // Query to check if the user exists and is not banned
    $query = "SELECT * FROM user WHERE userName=? LIMIT 1";
    
    // Prepare the statement
    $stmt = mysqli_prepare($con, $query);
    
    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    
    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if the user exists
    if (mysqli_num_rows($result) == 0) {
        // Logout and redirect if the user doesn't exist
        logoutAndRedirect('../login.php', 'Access Denied!');
    } else {
        $row = mysqli_fetch_assoc($result);
        // Check if the user's account is banned
        if ($row['lock_acc'] === 1) {
            // Logout and redirect if the user's account is banned
            logoutAndRedirect('../login.php', 'Your account has been banned! Please contact admin.');
        }
    }
    
    // Regenerate the session ID
    session_regenerate_id(true);
    
    
    // Define a map for role-based redirection
    $redirectMap = [
        1 => '../admin/index.php',
        3 => '../staff/index.php',
    ];
    
    // Redirect based on the user's role
    if (isset($redirectMap[$roleID])) {
        header("Location: " . $redirectMap[$roleID]);
        exit();
    }
} else {
    // Redirect to the login page if the user is not logged in
    redirect('../login.php', "Login to continue..");
}
?>
