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
    
    // If user doesn't exist or is banned, destroy session and redirect to login
    if (mysqli_num_rows($result) == 0) {
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit();
    }

    $row = mysqli_fetch_assoc($result);

    if ($row['lock_acc'] == 1) {
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit();
    }
    } else {
    // Redirect to the login page if the user is not logged in
    redirect('../login.php', "Login to continue..");
    exit();
}
?>
