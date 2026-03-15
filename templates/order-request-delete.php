<?php require '../config/function.php';
    if (!isset($_SESSION['loggedInUser']['roleID']) || !in_array($_SESSION['loggedInUser']['roleID'], [2,3])) {
        redirect('index.php', 'Access Denied. Staff or Manager only.');
        exit();
    }
    

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