<?php require '../config/function.php';
    if (!isset($_SESSION['loggedInUser']['roleID']) || $_SESSION['loggedInUser']['roleID'] != 1) {
        redirect('index.php', 'Access Denied. Manager only.');
        exit();
    }

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