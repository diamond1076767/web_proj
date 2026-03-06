<?php
require "config/function.php";
require "authenticator.php";

// Check if the user is already logged in
if(isset($_SESSION['loggedIn'])){
    // Retrieve and validate the user's role ID
    $roleID = validate($_SESSION['loggedInUser']['roleID']);
    
    // Redirect users based on their role
    switch ($roleID) {
        case 1:
            header("Location: admin/index.php");
            exit();
        case 2:
            header("Location: manager/index.php");
            exit();
        case 3:
            header("Location: staff/index.php");
            exit();
    }
}

// If the request method is not POST, redirect to the OTP page
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("location: otp.php");
    die();
}

// Instantiate the Authenticator class
$Authenticator = new Authenticator();

// Verify the provided OTP code
$checkResult = $Authenticator->verifyCode($_SESSION['auth_secret'], $_POST['code'], 0); 

// Handle OTP verification results
if (!$checkResult) {
    $_SESSION['failed'] = true;
    $_SESSION['authenticate'] = "cannotlogin"; // Additional session variable
    header("location: otp.php");
    die();
} elseif($_SESSION['first_log'] == 1){
    // Retrieve user details from the database
    if (isset($_SESSION['user_id'])){
        $userID = $_SESSION['user_id'];
        $query = $con->prepare("SELECT * FROM user WHERE _id = ?");
        $query->bind_param("i", $userID);
        $result=$query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();

        // Update loginTime in the database
        $userId = $_SESSION['user_id'];
        $auth_secret = $_SESSION['auth_secret'];
        $updateQuery = "UPDATE user SET logTime = CURRENT_TIMESTAMP WHERE _id = ?";
        $stmtUpdate = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, "i", $userId);
        mysqli_stmt_execute($stmtUpdate);

        // Set session variables for the logged-in user
        $_SESSION['loggedIn'] = true;
        $_SESSION['loggedInUser'] = [
            'user_id' => $row['_id'],
            'username' => $row['userName'],
            'fullname' => $row['fullName'],
            'email' => $row['email'],
            'phone' => $row['telephone'],
            'dob' => $row['dob'],
            'roleID' => $row['roleID'],
            'image' => $row['avatar'],
        ];
        
        header("location: change-password.php");
    }
} else {
    // Retrieve user details from the database
    if (isset($_SESSION['user_id'])){
        $userID = $_SESSION['user_id'];
        $query = $con->prepare("SELECT * FROM user WHERE _id = ?");
        $query->bind_param("i", $userID);
        $result=$query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();

        // Update loginTime in the database
        $userId = $_SESSION['user_id'];
        $auth_secret = $_SESSION['auth_secret'];
        $updateQuery = "UPDATE user SET logTime = CURRENT_TIMESTAMP WHERE _id = ?";
        $stmtUpdate = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, "i", $userId);
        mysqli_stmt_execute($stmtUpdate);

        // Set session variables for the logged-in user
        $_SESSION['loggedIn'] = true;
        $_SESSION['loggedInUser'] = [
            'user_id' => $row['_id'],
            'username' => $row['userName'],
            'fullname' => $row['fullName'],
            'email' => $row['email'],
            'phone' => $row['telephone'],
            'dob' => $row['dob'],
            'roleID' => $row['roleID'],
            'image' => $row['avatar'],
        ];

        // Redirect users based on their role
        switch ($row['roleID']) {
            case 1:
                redirect('admin/index.php', 'Logged In Successfully');
                break;
            case 2:
                redirect('manager/index.php', 'Logged In Successfully');
                break;
            case 3:
                redirect('staff/index.php', 'Logged In Successfully');
                break;
        }
    }
}
?>
