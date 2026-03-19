<?php
require '../config/function.php';
$roleID = $_SESSION['loggedInUser']['roleID'] ?? null;

$redirectPage = ($roleID == 3) 
    ? 'order-request-create.php' 
    : 'order-create.php';
allowedRole([1,2,3]);
$paraResult = checkParamId('index');
if(is_numeric($paraResult)){
    
    $indexValue = validate($paraResult);
    
    if(isset($_SESSION['productItems']) && isset($_SESSION['productItemIds'])){
        unset($_SESSION['productItems'][$indexValue]);
        unset($_SESSION['productItemIds'][$indexValue]);
        
        redirect($redirectPage, 'Item Removed');
    }else{
        redirect($redirectPage, 'There is no item');
    }
    
}else{
    redirect($redirectPage, 'param not numeric');
}

?>