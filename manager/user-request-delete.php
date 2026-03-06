<?php

require '../config/function.php';

// Get the request ID from the POST data
$requestID = $_POST['requestId'];

// Check if the provided request ID is a numeric value
if (is_numeric($requestID)) {
    // Sanitize and validate the request ID
    $requestId = validate($requestID);

    // Retrieve request details based on the ID
    $request = getById('request_user', $requestId);

    // Check if the request retrieval was successful
    if ($request['status'] == 200) {
        // Attempt to delete the request
        $requestDeleteRes = delete('request_user', $requestId);

        // Check if the request deletion was successful
        if ($requestDeleteRes) {
            // Redirect with success message if deletion was successful
            redirect('user-request.php', 'Request Deleted Successfully.');
        } else {
            // Redirect with error message if something went wrong during deletion
            redirect('user-request.php', 'Something Went Wrong.');
        }
    } else {
        // Redirect with the message from the request retrieval if unsuccessful
        redirect('user-request.php', $request['message']);
    }
} else {
    // Redirect with error message if the provided request ID is not numeric
    redirect('user-request.php', 'Something Went Wrong.');
}
?>
