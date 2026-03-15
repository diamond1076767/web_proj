<?php
    
    require '../config/function.php';
    
    if (!isset($_SESSION['loggedInUser']['roleID']) || $_SESSION['loggedInUser']['roleID'] != 2) {
        redirect('index.php', 'Access Denied. Manager only.');
        exit();
    };

    $requestID = $_POST['requestId'];

    if (is_numeric($requestID)) {
        $requestId = validate($requestID);
        
        // Get request order by ID
        $request = getById('request_order', $requestId);
        
        if($request['status'] == 200)
        {
            $data = [
                'status' => 'Declined'
            ];

            $requestDeclineRes = update('request_order', $requestId, $data);
            if($requestDeclineRes){
                redirect('order-request.php', 'Request Declined Successfully.');
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