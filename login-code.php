<?php

require 'config/function.php';

if (isset($_POST['loginBtn'])){

    // 1. Get and validate form input
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    if ($username == '' || $password == ''){
        redirect('login.php', 'All fields are mandatory!');
    }

    // 2. Retrieve the user record from the database
    $stmt = mysqli_prepare($con, "SELECT * FROM user WHERE BINARY userName=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result){
        redirect('login.php', 'Something Went Wrong!');
    }

    if (mysqli_num_rows($result) != 1){
        redirect('login.php', 'Invalid Username');
    }

    // 3. Fetch the user data
    $row = mysqli_fetch_assoc($result);

    // 4. Check if account is locked
    if ($row['lock_acc'] == 1){
        redirect('login.php', 'Your account has been permanently locked. Contact your Admin.');
    }

    // 5. Verify password
    if (!password_verify($password, $row['password'])){

        $failedAttempts = intval($row['failed_attempts']) + 1;

        if ($failedAttempts >= 3){

            $stmtLock = mysqli_prepare($con, "UPDATE user SET lock_acc = 1 WHERE _id = ?");
            mysqli_stmt_bind_param($stmtLock, "i", $row['_id']);
            mysqli_stmt_execute($stmtLock);

            redirect('login.php', 'Invalid Password. Your account has been locked due to multiple failed login attempts');
        }

        // Update failed attempt counter
        $stmtAttempts = mysqli_prepare($con, "UPDATE user SET failed_attempts = ? WHERE _id = ?");
        mysqli_stmt_bind_param($stmtAttempts, "ii", $failedAttempts, $row['_id']);
        mysqli_stmt_execute($stmtAttempts);

        redirect('login.php', 'Invalid Password');
    }

    // 6. Successful login. Reset failed attempts
    $stmtReset = mysqli_prepare($con, "UPDATE user SET failed_attempts = 0 WHERE _id = ?");
    mysqli_stmt_bind_param($stmtReset, "i", $row['_id']);
    mysqli_stmt_execute($stmtReset);

    // 7. Update last login time
    $stmtLoginTime = mysqli_prepare($con, "UPDATE user SET logTime = CURRENT_TIMESTAMP WHERE _id = ?");
    mysqli_stmt_bind_param($stmtLoginTime, "i", $row['_id']);
    mysqli_stmt_execute($stmtLoginTime);

    // 8. Store user session information
    $_SESSION['user_id'] = $row['_id'];
    $_SESSION['first_log'] = $row['first_log'];

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

    // 9. Force password change on first login
    if ($row['first_log'] == 1){
        header("location: change-password.php");
        exit();
    }

    // 10. Redirect user based on role
    switch ($row['roleID']){

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

if (isset($_POST['resetBtn'])){

    $pass = validate($_POST['pass']);
    $conpass = validate($_POST['conpass']);
    $userID = $_SESSION['user_id'];

    if ($pass != '' && $conpass != '') {

        // Check if password match
        if ($pass != $conpass) {
            redirect('change-password.php', 'Passwords do not match.');
        }
        
        // Check if the password meets the strength criteria
        if (!isPasswordStrong($pass)) {
            redirect('change-password.php', 'Passwords does not meet the requirements: </br>
            1. The password must be at least 8 characters long.</br>
            2. The password must contain at least one uppercase letter.</br>
            3. The password must contain at least one lowercase letter.</br>
            4. The password must contain at least one numeric digit.</br>
            5. The password must contain at least one special character among the following: !@#$%^&*(),.?":{}|<>');
        }else{
            $bcryptpassword = password_hash($pass, PASSWORD_BCRYPT);
        }

        $data = [
            'password' => $bcryptpassword,
        ];
        $result = updateData('user', $userID, $data);

        if ($result) {
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
                $updateQuery = "UPDATE user SET logTime = CURRENT_TIMESTAMP, first_log = 0 WHERE _id = ?";
                $stmtUpdate = mysqli_prepare($con, $updateQuery);
                mysqli_stmt_bind_param($stmtUpdate, "i", $userId);
                mysqli_stmt_execute($stmtUpdate);
        
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
        
                switch ($row['roleID']) {
                    case 1:
                        redirect('admin/index.php', 'Password Changed Successfully');
                        break;
                    case 2:
                        redirect('manager/index.php', 'Password Changed Successfully');
                        break;
                    case 3:
                        redirect('staff/index.php', 'Password Changed Successfully');
                        break;
                }
            }
        } else {
            redirect('change-password.php', 'Something Went Wrong!');
        }
    } else {
        redirect('change-password.php', 'Please fill required fields');
    }
}
?>
