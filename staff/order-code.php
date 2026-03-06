<?php
// Include necessary functions and configurations
include('../config/function.php');

// Initialize session variables for product items
if (!isset($_SESSION['productItems'])) {
    $_SESSION['productItems'] = [];
}

// Initialize session variables for product item IDs
if (!isset($_SESSION['productItemIds'])) {
    $_SESSION['productItemIds'] = [];
}

// Handle adding an item to the cart
if (isset($_POST['addItem'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);
    
    // Check if the product exists
    $checkProduct = checkProduct($productId);
    
    if ($checkProduct) {
        if (mysqli_num_rows($checkProduct) > 0) {
            $row = mysqli_fetch_assoc($checkProduct);
            
            // Check if the requested quantity is available
            if ($row['quantity'] < $quantity) {
                redirect('order-request-create.php', 'Only ' . $row['quantity'] . ' quantity available');
            }
            
            // Create product data array
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
            
            // Check if the product is already in the cart
            if (!in_array($row['_id'], $_SESSION['productItemIds'])) {
                array_push($_SESSION['productItemIds'], $row['_id']);
                array_push($_SESSION['productItems'], $productData);
            } else {
                // Update quantity if the product is already in the cart
                foreach ($_SESSION['productItems'] as $key => $prodSessionItem) {
                    if ($prodSessionItem['_id'] == $row['_id']) {
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
            redirect('order-request-create.php', 'Item Added ' . $row['title']);
        } else {
            redirect('order-request-create.php', 'No such product found!');
        }
    } else {
        redirect('order-request-create.php', 'Something Went Wrong!');
    }
}

// Handle updating product quantity in the cart
if (isset($_POST['productIncDec'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);
    
    $flag = false;
    foreach ($_SESSION['productItems'] as $key => $item) {
        if ($item['_id'] == $productId) {
            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }
    }
    
    if ($flag) {
        jsonResponse(200, 'success', 'Quantity Updated');
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong. Please re-fresh');
    }
}

// Handle proceeding to place the order
if (isset($_POST['proceedToPlaceBtn'])) {
    $phone = validate(encryption($_POST['cphone']));
    $payment_mode = validate($_POST['payment_mode']);
    
    // Checking for Customer using prepared statement
    $checkCustomer = checkCustomer($phone);
    
    if ($checkCustomer) {
        if (mysqli_num_rows($checkCustomer) > 0) {
            $_SESSION['invoice_no'] = "INV-".rand(111111,999999);
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;
            jsonResponse(200, 'success', 'Customer Found');
        } else {
            $_SESSION['cphone'] = $phone;
            jsonResponse(404, 'warning', 'Customer Not Found');
        }
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong');
    }
}

// Handle saving customer information
if (isset($_POST['saveCustomerBtn'])) {
    $name = validate($_POST['name']);
    $company = validate($_POST['company']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    
    if ($name != '' && $phone != '' && $email != '') {
        // Create data array for customer
        $data = [
            'customerName' => $name,
            'companyName' => $company,
            'telephone' => encryption($phone),
            'email' => encryption($email),
        ];
        $result = insert('customer', $data);
        
        if ($result) {
            jsonResponse(200, 'success', 'Customer Created Successfully');
        } else {
            jsonResponse(500, 'error', 'Something Went Wrong');
        }
    } else {
        jsonResponse(422, 'warning', 'Please Fill Required Fields');
    }
}

// Handle saving the order
if (isset($_POST['saveOrder'])) {
    $phone = validate($_SESSION['cphone']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $userID = validate($_SESSION['loggedInUser']['user_id']);
    
    // Checking for Customer using prepared statement
    $checkCustomer = checkCustomer($phone);
    
    if (!$checkCustomer) {
        jsonResponse(500, 'error', 'Something Went Wrong!');
    }
    
    if (mysqli_num_rows($checkCustomer) > 0) {
        $customerData = mysqli_fetch_assoc($checkCustomer);
        
        if (!isset($_SESSION['productItems'])) {
            jsonResponse(404, 'warning', 'No Items to place order!');
        }
        $sessionProducts = $_SESSION['productItems'];
        
        $totalAmount = 0;
        foreach ($sessionProducts as $amtItem) {
            $totalAmount += $amtItem['price'] * $amtItem['quantity'];
        }
        
        // Create data array for order
        $data = [
            'customerID' => $customerData['_id'],
            'total_amount' => $totalAmount,
            'status' => 'Pending',
            'payment_mode' => $payment_mode,
            'userID' => $userID,
        ];
        $result = insert('request_order', $data);
        $lastOrderId = mysqli_insert_id($con);
        
        foreach ($sessionProducts as $prodItem) {
            $productId = $prodItem['_id'];
            $price = $prodItem['price'];
            $quantity = $prodItem['quantity'];
            
            // Create data array for order items
            $dataOrderItem = [
                'orderID' => $lastOrderId,
                'inventoryID' => $productId,
                'cost' => $price,
                'quantity' => $quantity,
            ];
            $orderItemQuery = insert('order_request_items', $dataOrderItem);
        }
        
        // Clear session variables
        unset($_SESSION['productItemIds']);
        unset($_SESSION['productItems']);
        unset($_SESSION['cphone']);
        unset($_SESSION['payment_mode']);
        
        jsonResponse(200, 'success', 'Order Request Placed Successfully.');
    } else {
        jsonResponse(404, 'warning', 'No Customer Found!');
    }
}

// Check if the request is for checking existing phone
if (isset($_POST['checkExistingPhone'])) {
    $phone = validate($_POST['phone']);
    $phoneExists = checkExistingPhone($phone);
    if ($phoneExists) {
        echo json_encode(['status' => 409, 'message' => 'Phone already exists', 'status_type' => 'warning']);
    } else {
        echo json_encode(['status' => 200, 'message' => 'Phone does not exist']);
    }
    exit(); // Stop further execution
}

// Check if the request is for checking existing email
if (isset($_POST['checkExistingEmail'])) {
    $email = validate($_POST['email']);
    $emailExists = checkExistingEmail($email);
    if ($emailExists) {
        echo json_encode(['status' => 409, 'message' => 'Email already exists', 'status_type' => 'warning']);
    } else {
        echo json_encode(['status' => 200, 'message' => 'Email does not exist']);
    }
    exit(); // Stop further execution
}
?>
