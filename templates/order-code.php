<?php 
include('../config/function.php');
allowedRole([1,2,3]);
if(!isset($_SESSION['productItems'])){
    $_SESSION['productItems'] = [];
}

if(!isset($_SESSION['productItemIds'])){
    $_SESSION['productItemIds'] = [];
}

if(isset($_POST['addItem'])){

    $roleID = $_SESSION['loggedInUser']['roleID'] ?? null;

    $redirectPage = ($roleID == 3) 
    ? 'order-request-create.php' 
    : 'order-create.php';

    $productId = validate($_POST['product_id']);  
    $quantity = validate($_POST['quantity']);
    
    
    $checkProduct = mysqli_query($con, "SELECT * FROM inventory WHERE _id='$productId' LIMIT 1");
    if($checkProduct){
        
        if(mysqli_num_rows($checkProduct)>0){
            $row = mysqli_fetch_assoc($checkProduct);
            if($row['quantity'] < $quantity){
                redirect($redirectPage, 'Only ' .$row['quantity']. ' quantity available');
            }
            
            $productData = [
                '_id' => $row['_id'],
                'categoryID' => $row['categoryID'],
                'colourID' => $row['colourID'],
                'title' => $row['title'],
                'quantity' => $quantity,
                'price' => $row['cost'],
                'description' => $row['description'],
                'image' => $row['image'],                                
            ];
            
            if(!in_array($row['_id'], $_SESSION['productItemIds'])){
                
                array_push($_SESSION['productItemIds'], $row['_id']);
                array_push($_SESSION['productItems'], $productData);
            }else{
                foreach($_SESSION['productItems'] as $key => $prodSessionItem){
                    if($prodSessionItem['_id'] == $row['_id']){
                        $newQuantity = $prodSessionItem['quantity'] + $quantity;       
                        
                        $productData = [
                            '_id' => $row['_id'],
                            'categoryID' => $row['categoryID'],
                            'colourID' => $row['colourID'],
                            'title' => $row['title'],
                            'quantity' => $newQuantity,
                            'price' => $row['cost'],
                            'description' => $row['description'],
                            'image' => $row['image'],
                        ];
                        $_SESSION['productItems'][$key] = $productData;
                    }
                }
            }
            redirect($redirectPage, 'Item Added '.$row['title']);
        }else{
            redirect($redirectPage, 'No such product found!');
        }
    }else{
        redirect($redirectPage, 'Something Went Wrong!');
    }
}

if(isset($_POST['productIncDec'])){
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);
    
    $flag = false;
    foreach($_SESSION['productItems'] as $key => $item){
        if($item['_id'] == $productId){
            
            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }
    }
    
    if($flag){
        jsonResponse(200, 'success', 'Quantity Updated');
    }else{
        jsonResponse(500, 'error', 'Something Went Wrong. Please re-fresh');
    }
}

if(isset($_POST['proceedToPlaceBtn'])){
    $phone = validate(encryption($_POST['cphone'])); // Ensure encryption matches DB storage
    $payment_mode = validate($_POST['payment_mode']);

    $checkCustomer = mysqli_query($con, "SELECT * FROM customer WHERE telephone='$phone' LIMIT 1");

    // Get current user role
    $roleID = $_SESSION['loggedInUser']['roleID'] ?? 0;

    if($checkCustomer && mysqli_num_rows($checkCustomer) > 0){
        $_SESSION['invoice_no'] = "INV-".rand(111111,999999);
        $_SESSION['cphone'] = $phone;
        $_SESSION['payment_mode'] = $payment_mode;

        // Redirect based on role
        if($roleID == 3){
            redirect('order-request-summary.php', 'Customer Found');
        } else {
            redirect('order-summary.php', 'Customer Found');
        }

    } else {
        // FIX: Redirect back to the correct page if customer isn't found
        if($roleID == 3){
            redirect('order-request-create.php', 'Customer Not Found. Please add customer first.');
        } else {
            // This keeps Admins/Managers on the correct page!
            redirect('order-create.php', 'Customer Not Found. Please click "Add Customer" above.');
        }
    }
}

if(isset($_POST['saveCustomerBtn']))
{
    $name = validate($_POST['name']);
    $company = validate($_POST['company']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    
    if($name != '' && $phone != '' && $email != ''){
        
        $data = [
            'customerName' => $name,
            'companyName' => $company,
            'telephone' => encryption($phone),
            'email' => encryption($email),
        ];
        $result = insert('customer', $data);
        if($result){
            jsonResponse(200, 'success', 'Customer Created Successfully');
        }else{
            jsonResponse(500, 'error', 'Something Went Wrong');
        }
    }else{
        jsonResponse(422, 'warning', 'Please Fill Required Fields');
    }
}

if(isset($_POST['saveOrder'])){
    $phone = validate($_SESSION['cphone']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $userID = validate($_SESSION['loggedInUser']['user_id']);
    $roleID = $_SESSION['loggedInUser']['roleID'];

    $checkCustomer = mysqli_query($con, "SELECT * FROM customer WHERE telephone ='$phone' LIMIT 1");
    if(!$checkCustomer || mysqli_num_rows($checkCustomer) == 0){
        $_SESSION['error_message'] = "No Customer Found!";
        header('Location: order-summary.php');
        exit;
    }

    $customerData = mysqli_fetch_assoc($checkCustomer);

    if(!isset($_SESSION['productItems'])){
        $_SESSION['error_message'] = "No Items to place order!";
        header('Location: order-summary.php');
        exit;
    }

    $sessionProducts = $_SESSION['productItems'];
    $totalAmount = 0;
    foreach($sessionProducts as $amtItem){
        $totalAmount += $amtItem['price'] * $amtItem['quantity'];
    }

    // ===== ADMIN / MANAGER =====
    if($roleID == 1 || $roleID == 2){
        $data = [
            'customerID' => $customerData['_id'],
            'tracking_no' => rand(11111,99999),
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'Order Placed',
            'payment_mode' => $payment_mode,
            'userID' => $userID,
        ];
        $result = insert('sales_order', $data);
        $lastOrderId = mysqli_insert_id($con);

        foreach($sessionProducts as $prodItem){
            $dataOrderItem = [
                'orderID' => $lastOrderId,
                'inventoryID' => $prodItem['_id'],
                'cost' => $prodItem['price'],
                'quantity' => $prodItem['quantity'],
            ];
            insert('order_items', $dataOrderItem);

            // Update inventory
            $productQtyData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM inventory WHERE _id='".$prodItem['_id']."'"));
            $totalProductQuantity = $productQtyData['quantity'] - $prodItem['quantity'];
            update('inventory', $prodItem['_id'], ['quantity' => $totalProductQuantity]);
        }

    } else {
        // ===== STAFF → REQUEST ORDER =====
        $data = [
            'customerID' => $customerData['_id'],
            'total_amount' => $totalAmount,
            'status' => 'Pending',
            'payment_mode' => $payment_mode,
            'userID' => $userID,
        ];
        $result = insert('request_order', $data);
        $lastOrderId = mysqli_insert_id($con);

        foreach($sessionProducts as $prodItem){
            $dataOrderItem = [
                'orderID' => $lastOrderId,
                'inventoryID' => $prodItem['_id'],
                'cost' => $prodItem['price'],
                'quantity' => $prodItem['quantity'],
            ];
            insert('order_request_items', $dataOrderItem);
        }
    }

    // Clear session
    unset($_SESSION['productItemIds'], $_SESSION['productItems'], $_SESSION['cphone'], $_SESSION['payment_mode'], $_SESSION['invoice_no']);

    // Set success message and redirect
    $_SESSION['success_message'] = ($roleID == 1 || $roleID == 2) ? "Order Placed Successfully" : "Order Request Placed Successfully";
    header('Location: orders.php');
    exit; // Always exit after header redirect
}

?>