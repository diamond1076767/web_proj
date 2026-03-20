<?php require '../config/function.php';
    allowedRole([1]);

    $requestID = $_POST['requestId'];

    if (is_numeric($requestID)) {
        $requestId = validate($requestID);
        
        // Get request order by ID
        $request = getById('request_user', $requestId);
        
        
        if($request['status'] == 200)
        {
            $data = [
                'status' => 'Declined'
            ];

            $requestDeclineRes = updateData('request_user', $requestId, $data);
            if($requestDeclineRes){
                redirect('user-request.php', 'Request Declined Successfully.');
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