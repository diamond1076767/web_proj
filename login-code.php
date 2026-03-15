<?php

require 'config/function.php';

if (isset($_POST['loginBtn'])){

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    if ($username == '' || $password == ''){
        redirect('login.php', 'All fields are mandatory!');
    }

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

    $row = mysqli_fetch_assoc($result);

    if ($row['lock_acc'] == 1){
        redirect('login.php', 'Your account has been permanently locked. Contact your Admin.');
    }

    if (!password_verify($password, $row['password'])){

        $failedAttempts = intval($row['failed_attempts']) + 1;

        if ($failedAttempts >= 3){
            $stmtLock = mysqli_prepare($con, "UPDATE user SET lock_acc = 1 WHERE _id = ?");
            mysqli_stmt_bind_param($stmtLock, "i", $row['_id']);
            mysqli_stmt_execute($stmtLock);

            redirect('login.php', 'Invalid Password. Your account has been locked due to multiple failed login attempts');
        }

        $stmtAttempts = mysqli_prepare($con, "UPDATE user SET failed_attempts = ? WHERE _id = ?");
        mysqli_stmt_bind_param($stmtAttempts, "ii", $failedAttempts, $row['_id']);
        mysqli_stmt_execute($stmtAttempts);

        redirect('login.php', 'Invalid Password');
    }

    // Successful login
    session_regenerate_id(true); // prevent session fixation

    $stmtReset = mysqli_prepare($con, "UPDATE user SET failed_attempts = 0 WHERE _id = ?");
    mysqli_stmt_bind_param($stmtReset, "i", $row['_id']);
    mysqli_stmt_execute($stmtReset);

    $stmtLoginTime = mysqli_prepare($con, "UPDATE user SET logTime = CURRENT_TIMESTAMP WHERE _id = ?");
    mysqli_stmt_bind_param($stmtLoginTime, "i", $row['_id']);
    mysqli_stmt_execute($stmtLoginTime);

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

    if ($row['first_log'] == 1){
        header("location: change-password.php");
        exit();
    }

    // 8. Redirect all users to templates/index.php
    redirect('templates/index.php', 'Logged In Successfully');
}
?>