<?php

// Include the necessary functions and configuration
require '../config/function.php';

// Check if 'index' parameter is present and numeric
$paraResult = checkParamId('index');
if (is_numeric($paraResult)) {
    
    // Sanitize and validate the 'index' value
    $indexValue = validate($paraResult);
    
    // Check if session variables are set
    if (isset($_SESSION['productItems']) && isset($_SESSION['productItemIds'])) {
        
        // Unset the specified index in session arrays
        unset($_SESSION['productItems'][$indexValue]);
        unset($_SESSION['productItemIds'][$indexValue]);
        
        // Redirect to the order-request-create.php page with a success message
        redirect('order-request-create.php', 'Item Removed');
    } else {
        // Redirect to the order-request-create.php page with a message about the absence of items
        redirect('order-request-create.php', 'There is no item');
    }
    
} else {
    // Redirect to the order-request-create.php page with an error message for non-numeric parameter
    redirect('order-request-create.php', 'Param not numeric');
}

?>
