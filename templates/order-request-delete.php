<?php require '../config/function.php';
    allowedRole([2,3]);

    $requestID = $_POST['requestId'];

    if (is_numeric($requestID)) {
        $requestId = validate($requestID);
        
        // Get request order by ID
        $request = getById('request_order', $requestId);
        if($request['status'] == 200)
        {
            $requestDeleteRes = delete('request_order', $requestId);
            if($requestDeleteRes){
                redirect('order-request.php', 'Request Deleted Successfully.');
            }else{
                redirect('order-request.php', 'Something Went Wrong.');
            }
        }else{
            redirect('order-request.php',$request['message']);
        }

    }else{
       redirect('order-request.php', 'Something Went Wrong.');
    }
?>
