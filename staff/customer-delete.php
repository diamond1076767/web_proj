<?php

require '../config/function.php';


$customerID = $_POST['customerId'];

if (is_numeric($customerID)) {
    // Validate and retrieve the customer ID
    $customerId = validate($customerID);
    
    // Get customer details by ID
    $customer = getById('customer', $customerId);
    
    if ($customer['status'] == 200) {
        // Attempt to delete the customer
        $customerDeleteRes = delete('customer', $customerId);
        
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
