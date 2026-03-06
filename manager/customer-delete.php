<?php

require '../config/function.php';

    $cusID = $_POST['cusId'];

    if (is_numeric($cusID)) {
        $cusId = validate($cusID);
        
        // Get request order by ID
        $customer = getById('customer', $cusId);
    
    if ($customer['status'] == 200) {
        // Attempt to delete the customer
        $customerDeleteRes = delete('customer', $cusId);
        
        if ($customerDeleteRes) {
            // Redirect with success message if deletion is successful
            redirect('customer.php', 'Customer Deleted Successfully.');
        } else {
            // Redirect with error message if deletion fails
            redirect('customer.php', 'Something Went Wrong.');
        }
    } else {
        // Redirect with error message if customer details retrieval fails
        redirect('customer.php', $customer['message']);
    }
} else {
    // Redirect with error message if 'id' parameter is not numeric
    redirect('customer.php', 'Something Went Wrong.');
}
?>
