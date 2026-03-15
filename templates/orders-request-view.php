<?php
// Include header file with common elements
include('includes/header.php');

if (!isset($_SESSION['loggedInUser']['roleID']) || !in_array($_SESSION['loggedInUser']['roleID'], [2,3])) {
    redirect('index.php', 'Access Denied. Staff or Manager only.');
    exit();
}
?>

<div class="container-fluid px-4">
    <!-- Card for displaying order request details -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <!-- Header with title "Order Request View" and Back button -->
            <h4 class="mb-0">Order Request View
                <a href="order-request.php" class="btn btn-danger mx-2 btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php
            // Display alert messages
            alertMessage();
            ?>

            <?php
            // Check if 'id' parameter is set in GET
            if (isset($_POST['requestId'])) {
                // Get customer details by ID
            $_SESSION['requestID'] = $_POST['requestId'];
            }

            if (isset($_SESSION['requestID'])) {
                $_id = $_SESSION['requestID'];
                

                // Query to retrieve order details
                $query = "SELECT o.*, c.* FROM request_order o, customer c 
                            WHERE c._id = o.customerID AND o._id = ? 
                            ORDER BY o._id DESC";

                $stmt = $con->prepare($query);
                $stmt->bind_param("i", $_id);
                $stmt->execute();
                $orders = $stmt->get_result();

                if ($orders) {
                    if (mysqli_num_rows($orders) > 0) {

                        $orderData = mysqli_fetch_assoc($orders);

                        ?>
                        <!-- Display order details in a card -->
                        <div class="card card-body shadow border-1 mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Display order details in the left column -->
                                    <h4>Order Details</h4>

                                    <label class="mb-1">
                                        Order Date:
                                        <span class="fw-bold"><?= validate(date("Y-m-d", strtotime($orderData['created_at']))) ?></span>
                                    </label>
                                    </br>
                                    <label class="mb-1">
                                        Order Status:
                                        <span class="fw-bold"><?= validate($orderData['status']); ?></span>
                                    </label>
                                    </br>
                                    <label class="mb-1">
                                        Payment Mode:
                                        <span class="fw-bold"><?= validate($orderData['payment_mode']); ?></span>
                                    </label>
                                    </br>
                                </div>

                                <div class="col-md-6">
                                    <!-- Display user details in the right column -->
                                    <h4>User Details</h4>
                                    <label class="mb-1">
                                        Full Name:
                                        <span class="fw-bold"><?= validate($orderData['customerName']); ?></span>
                                    </label>
                                    </br>
                                    <label class="mb-1">
                                        Email Address:
                                        <span class="fw-bold"><?= validate(decryption($orderData['email'])); ?></span>
                                    </label>
                                    </br>
                                    <label class="mb-1">
                                        Phone Number:
                                        <span class="fw-bold"><?= validate(decryption($orderData['telephone'])); ?></span>
                                    </label>
                                    </br>
                                </div>
                            </div>
                        </div>

                        <?php
                        // Query to retrieve order item details
                        $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.cost as orderItemPrice, o.*,oi.*,p.*, c.* 
                            FROM request_order o, order_request_items oi, inventory p, colour c
                            WHERE oi.orderID = o._id AND p._id = oi.inventoryID AND p.colourID = c._id AND o._id = ?";

                        $stmtOrderItemQuery = $con->prepare($orderItemQuery);
                        $stmtOrderItemQuery->bind_param("i", $_id);
                        $stmtOrderItemQuery->execute();
                        $orderItemRes = $stmtOrderItemQuery->get_result();

                        if ($orderItemRes) {
                            if (mysqli_num_rows($orderItemRes) > 0) {
                                ?>
                                <!-- Display order item details in a table -->
                                <h4 class="my-3">Order Item Details</h4>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Colour</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItemRes as $orderItemRow) : ?>
                                            <tr>
                                                <!-- Display order item details in each row -->
                                                <td>
                                                    <img src="<?= $orderItemRow['image'] != '' ? '../' . $orderItemRow['image'] : '../assets/images/no-img.png'; ?>"
                                                        style="width:50px;height:50px;"
                                                        alt="Img" />
                                                    <?= validate($orderItemRow['title']); ?>
                                                </td>
                                                <td width="15%" class="fw-bold text-center">
                                                    <?= validate($orderItemRow['colourName']); ?>
                                                </td>
                                                <td width="15%" class="fw-bold text-center">
                                                    <?= validate(number_format($orderItemRow['orderItemPrice'], 0)) ?>
                                                </td>
                                                <td width="15%" class="fw-bold text-center">
                                                    <?= validate($orderItemRow['orderItemQuantity']) ?>
                                                </td>
                                                <td width="15%" class="fw-bold text-center">
                                                    <?= validate(number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'], 0)) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <tr>
                                            <!-- Display total price row -->
                                            <td colspan="2" class="text-end fw-bold">Total Price: </td>
                                            <td colspan="3" class="text-end fw-bold">SGD <?= validate(number_format($orderItemRow['total_amount'], 0)); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                echo '<h5>Something Went Wrong</h5>';
                                return false;
                            }
                        } else {
                            echo '<h5>Something Went Wrong</h5>';
                            return false;
                        }
                        ?>

                        <?php
                    } else {
                        echo '<h5>No Record Found!</h5>';
                        return false;
                    }
                } else {
                    echo '<h5>Something Went Wrong</h5>';
                }
            } else {
                ?>
                <!-- Display message if 'id' parameter is not found in GET -->
                <div class="text-center py-5">
                    <h5>No Tracking Number Found</h5>
                    <div>
                        <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back To Orders</a>
                        <?php unset($_SESSION['requestID']); ?>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>

<?php
// Include footer file with common elements
include('includes/footer.php');
?>