<?php require '../config/function.php';
allowedRole([2]);


$requestID = $_POST['requestId'];

if (is_numeric($requestID)) {
    $requestId = validate($requestID);

    // Get request order by ID
    $request = getById('request_order', $requestId);
}

$checkProduct = "SELECT r.*,c.* FROM request_order r,customer c WHERE r.customerID = c._id AND r._id = '$requestId'";
$result = mysqli_query($con, $checkProduct);

// Check if the query was successful
if ($result) {
    // Fetch the data as an associative array
    $row = mysqli_fetch_assoc($result);

    // Check if the data exists
    if ($row) {
        // Access the 'phone' column from the result
        $phone = $row['telephone'];
        $invoice_no = "INV-" . rand(111111, 999999);
        $userID = validate($_SESSION['loggedInUser']['user_id']);

        $checkCustomer = mysqli_query($con, "SELECT * FROM customer WHERE telephone ='$phone' LIMIT 1");
        if (!$checkCustomer) {
            jsonResponse(500, 'error', 'Something Went Wrong!');
        }

        if (mysqli_num_rows($checkCustomer) > 0) {
            $customerData = mysqli_fetch_assoc($checkCustomer);

            $data = [
                'customerID' => $row['customerID'],
                'tracking_no' => rand(11111, 99999),
                'order_date' => date('Y-m-d'),
                'invoice_no' => $invoice_no,
                'total_amount' => $row['total_amount'],
                'order_status' => 'Order Placed',
                'payment_mode' => $row['payment_mode'],
                'userID' => $userID,
            ];
            $result = insert('sales_order', $data);
            $lastOrderId = mysqli_insert_id($con);

            $checkProducts = mysqli_query($con, "SELECT * FROM order_request_items WHERE orderID = '$requestId'");

            while ($prodItem = mysqli_fetch_assoc($checkProducts)) {
                // Your processing for each $prodItem goes here
                $quantity = $prodItem['quantity'];
                $inventoryId = $prodItem['inventoryID'];

                // Inserting order items
                $dataOrderItem = [
                    'orderID' => $lastOrderId,
                    'inventoryID' => $inventoryId,
                    'cost' => $prodItem['cost'],  // replace with the correct column name
                    'quantity' => $quantity,
                ];
                $orderItemQuery = insert('order_items', $dataOrderItem);

                // Checking for the quantity and decreasing quantity and making Total Quantity
                $checkProductQuantityQuery = mysqli_query($con, "SELECT * FROM inventory WHERE _id='$inventoryId'");
                $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
                $totalProductQuantity = $productQtyData['quantity'] - $quantity;

                $dataUpdate = [
                    'quantity' => $totalProductQuantity,
                ];
                $updateProductQty = updateData('inventory', $inventoryId, $dataUpdate);
            }

            if ($request['status'] === 200) {

                $data = [
                    'status' => 'Approved'
                ];

                $requestApproveRes = update('request_order', $requestId, $data);
                if ($requestApproveRes) {
                    redirect('order-request.php', 'Request Approved Successfully.');
                } else {
                    redirect('order-request.php', 'Something Went Wrong.');
                }
            } else {
                redirect('order-request.php', 'Request cannot be approved. Current status: ' . $request['status']);
            }
        } else {
            redirect('order-request.php', 'Something Went Wrong.');
        }
    }
}
