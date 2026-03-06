<?php
// Include header file with common elements
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <!-- Card for displaying order details -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <!-- Header with title "Print Order" and Back button -->
            <h4 class="mb-0">Print Order
                <a href="orders.php" class="btn btn-danger btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">

            <!-- Container for printing order details -->
            <div id="myBillingArea">
                <?php
                if (isset($_POST['trackId'])) {
					// Get customer details by ID
				$_SESSION['trackID'] = $_POST['trackId'];
				}

				if (isset($_SESSION['trackID'])) {
                    $trackingNo = $_SESSION['trackID'];

                    // Use prepared statement for the first query to retrieve order details
                    $orderQuery = "SELECT o.*, c.* FROM sales_order o, customer c 
                            WHERE c._id = o.customerID AND tracking_no = ? LIMIT 1";
                    $stmtOrderQuery = $con->prepare($orderQuery);
                    $stmtOrderQuery->bind_param("s", $trackingNo);
                    $stmtOrderQuery->execute();
                    $orderQueryRes = $stmtOrderQuery->get_result();

                    // Check if the first query was successful
                    if (!$orderQueryRes) {
                        error_log(mysqli_error($con));
                        echo '<h5>Something Went Wrong</h5>';
                        return false;
                    }

                    // Check if there are rows in the result set
                    if (mysqli_num_rows($orderQueryRes) > 0) {
                        $orderDataRow = mysqli_fetch_assoc($orderQueryRes);
                        ?>
                        <!-- Display header details for the printed order -->
                        <table style="width: 100%;margin-bottom: 20px;">
                            <tbody>
                                <tr>
                                    <td style="text-align: center;" colspan="2">
                                        <h4 style="font-size: 23px; line-height: 30px; margin: 2px; padding: 0;">TP Advanced Manufacturing Centre</h4>
                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">21 Tampines Ave 1, Singapore 529757</p>
                                        <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">Temasek Polytechnic</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Customer Details</h5>
                                        <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Name: <?= validate($orderDataRow['customerName'])?></p>
                                        <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Company: <?= validate($orderDataRow['companyName'])?></p>
                                        <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Phone No.: <?= validate(decryption($orderDataRow['telephone']))?></p>
                                        <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Email Addr.: <?= validate(decryption($orderDataRow['email'])) ?></p>
                                    </td>
                                    <td align="end">
                                        <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Invoice Details</h5>
                                        <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice No.: <?= validate($orderDataRow['invoice_no'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice Date: <?= validate(date('d M Y')); ?></p>
                                        <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: 21 Tampines Ave 1, Singapore 529757</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        echo "<h5>No Data Found</h5>";
                        return false;
                    }

                    // Use prepared statement for the second query to retrieve order item details
                    $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.cost as orderItemPrice, o.*, oi.*, p.* 
                            FROM sales_order o, order_items oi, inventory p
                            WHERE oi.orderID = o._id AND p._id = oi.inventoryID AND o.tracking_no=?";
                    $stmtOrderItemQuery = $con->prepare($orderItemQuery);
                    $stmtOrderItemQuery->bind_param("s", $trackingNo);
                    $stmtOrderItemQuery->execute();
                    $orderItemQueryRes = $stmtOrderItemQuery->get_result();

                    // Check if the second query was successful
                    if ($orderItemQueryRes) {
                        if (mysqli_num_rows($orderItemQueryRes) > 0) {
                            ?>
                            <!-- Display table for order item details -->
                            <div class="table-responsive mb-3">
                                <table style="width:100%;" cellpadding="5">
                                    <thead>
                                        <tr>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="5%">ID</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;">Product Name</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Colour</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="15%">Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($orderItemQueryRes as $key => $row) :
                                        ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= validate($row['title']); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?php
                                                    $colourID = $row['colourID'];

                                                    // Use prepared statement for the color query
                                                    $stmtColorQuery = $con->prepare("SELECT colourName FROM colour WHERE _id=?");
                                                    $stmtColorQuery->bind_param("i", $colourID);
                                                    $stmtColorQuery->execute();
                                                    $colorQueryRes = $stmtColorQuery->get_result();

                                                    if ($colorQueryRes && mysqli_num_rows($colorQueryRes) > 0) {
                                                        $colorData = mysqli_fetch_assoc($colorQueryRes);
                                                        echo validate($colorData['colourName']);
                                                    } else {
                                                        echo "No Color Found";
                                                    }
                                                    ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= validate(number_format($row['orderItemPrice'], 0)); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= validate($row['orderItemQuantity']); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                    <?= validate(number_format($row['orderItemPrice'] * $row['orderItemQuantity'], 0)) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="5" align="end" style="font-weight: bold;">Grand Total: </td>
                                            <td colspan="1" style="font-weight: bold;"><?= validate(number_format($row['total_amount'], 0)); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">Payment Mode: <?= validate($row['payment_mode']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        } else {
                            echo '<h5>No Data Found</h5>';
                            return false;
                        }
                    } else {
                        echo '<h5>Something Went Wrong!</h5>';
                        return false;
                    }
                } else {
                    ?>
                    <!-- Display message if 'track' parameter is not found -->
                    <div class="text-center py-5">
                        <h5>No Tracking Number Parameter Found</h5>
                        <div>
                            <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back To Orders</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Buttons for printing and downloading PDF -->
            <div class="mt-4 text-end">
                <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()">Print</button>
                <button class="btn btn-primary px-4 mx-1" onclick="downloadPDF('<?= validate($orderDataRow['invoice_no']); ?>')">Download PDF</button>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['trackID']); ?>
<?php
// Include footer file with common elements
include('includes/footer.php');
?>
