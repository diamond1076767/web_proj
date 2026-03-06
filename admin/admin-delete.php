<?php

    require '../config/function.php';

    $userID = $_POST['userId'];

    if (is_numeric($userID)) {
        $userId = validate($userID);
        
        // Get request order by ID
        $user = getById('user', $userId);

        if($user['status'] == 200)
        {
            $userDeleteRes = delete('user', $userId);
            if($userDeleteRes){
                redirect('admin.php', 'User Deleted Successfully.');
            }else{
                redirect('admin.php', 'Something Went Wrong.');
            }
        }else{
            redirect('admin.php',$user['message']);
        }
    }else{
       redirect('admin.php', 'Something Went Wrong.');
    }
?>