<?php require '../config/function.php';
    allowedRole([1,2]);
    

    $requestID = $_POST['requestId'];

    if (is_numeric($requestID)) {
        $requestId = validate($requestID);
        
        // Get request order by ID
        $request = getById('request_user', $requestId);
        
        if($request['status'] == 200)
        {
            $requestDeleteRes = delete('request_user', $requestId);
            if($requestDeleteRes){
                redirect('user-request.php', 'Request Deleted Successfully.');
            }else{
                redirect('user-request.php', 'Something Went Wrong.');
            }
        }else{
            redirect('user-request.php',$request['message']);
        }

    }else{
       redirect('user-request.php', 'Something Went Wrong.');
    }
?>