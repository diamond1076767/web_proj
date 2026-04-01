<?php require '../config/function.php';
allowedRole([1]);

if (!isset($_POST['userId'])) {
    redirect('admin.php', 'Invalid request.');
    exit();
}

$userID = $_POST['userId'];

if (is_numeric($userID)) {

    $userId = validate($userID);

    // Prevent deleting main admin
    if ($userId == 1) {
        redirect('admin.php', 'Primary Admin account cannot be deleted.');
        exit();
    }

    // Get user by ID
    $user = getById('user', $userId);

    if ($user['status'] == 200) {

        $userDeleteRes = delete('user', $userId);

        if ($userDeleteRes) {
            redirect('admin.php', 'User Deleted Successfully.');
        } else {
            redirect('admin.php', 'Something Went Wrong.');
        }

    } else {
        redirect('admin.php', $user['message']);
    }

} else {
    redirect('admin.php', 'Invalid User ID.');
}

?>
