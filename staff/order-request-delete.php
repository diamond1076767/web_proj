<?php
require '../config/function.php';

$requestID = $_POST['requestId'];

if (is_numeric($requestID)) {
    $requestId = validate($requestID);
    
    // Get request order by ID
    $request = getById('request_order', $requestId);
    
    if ($request['status'] == 200) {
        // If the request order exists and has a status of 200 (assuming it's a success status)
        
        // Delete the request order
        $requestDeleteRes = delete('request_order', $requestId);
        
        // Check if the deletion was successful
        if ($requestDeleteRes) {
            redirect('order-request.php', 'Request Deleted Successfully.');
        } else {
            redirect('order-request.php', 'Something Went Wrong.');
        }
    } else {
        // If the request order does not have a success status
        redirect('order-request.php', $request['message']);
    }
} else {
    // If the 'id' parameter is not numeric
    redirect('order-request.php', 'Something Went Wrong.');
}
?>
