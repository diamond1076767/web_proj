<?php
// Include header file with common elements
include('includes/header.php');

// Check if productItems session is not set, redirect to order-request-create.php
if (!isset($_SESSION['productItems'])) {
    header('Location: order-request-create.php');
    exit(); // Added exit to prevent further execution
}
allowedRole([3]);
?>


<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Order Summary Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Order Summary
                        <!-- Back to create order button -->
                        <a href="order-request-create.php" class="btn btn-danger float-end">Back to create order</a>
                    </h4>
                </div>
                <div class="card-body">
                    <?php alertMessage(); ?>

                    <div id="myBillingArea">
                        <?php
                        // Check if customer phone is set in the session
                        if (isset($_SESSION['cphone'])) {
                            $phone = validate($_SESSION['cphone']);
                            $invoiceNo = validate($_SESSION['invoice_no'] ?? '');
                            $customerQuery = mysqli_query($con, "SELECT * FROM customer WHERE telephone='$phone' LIMIT 1");

                            if ($customerQuery && mysqli_num_rows($customerQuery) > 0) {
                                $cRowData = mysqli_fetch_assoc($customerQuery);
                                ?>
                                <!-- Display customer and invoice details -->
                                <table style="width: 100%;margin-bottom: 20px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;" colspan="2">
                                                <h4 style="font-size: 23px; line-height: 30px; margin: 2px; padding: 0;">Singapore Advanced Manufacturing Centre</h4>
                                                <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">Singapore,Singapore 656667</p>
                                                <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">Singapore AMC</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Customer Details</h5>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Name: <?= validate($cRowData['customerName']); ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Company: <?= validate($cRowData['companyName']); ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Phone No.: <?= validate(decryption($cRowData['telephone'])); ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Email Addr.: <?= validate(decryption($cRowData['email'])); ?></p>
                                            </td>
                                            <td align="end">
                                                <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Invoice Details</h5>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice No.: <?= $invoiceNo ?: '-'; ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice Date: <?= date('d M Y'); ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: Singapore, Singapore 656667</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                            } else {
                                echo "<h5>No Customer Found</h5>";
                                exit; // Added exit to prevent further execution
                            }
                        }
                        ?>

                        <?php
                        // Check if productItems session is set
                        if (isset($_SESSION['productItems'])) {
                            $sessionProducts = $_SESSION['productItems'];
                            ?>
                            <!-- Display product items table -->
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
                                        $totalAmount = 0;

                                        foreach ($sessionProducts as $key => $row) {
                                            $totalAmount += validate($row['price'] * $row['quantity']);
                                            ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= validate(validate($row['title'])); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?php
                                                    // Get color name using prepared statement
                                                    $colourID = validate($row['colourID']);
                                                    $colorName = getColourName($colourID);

                                                    echo validate($colorName !== 'Unknown Color' ? $colorName : 'No Color Found');
                                                ?></td>

                                                <td style="border-bottom: 1px solid #ccc;"><?= validate(number_format($row['price'], 2)); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= validate($row['quantity']); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                    <?= validate(number_format($row['price'] * $row['quantity'], 2)) ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <!-- Grand total row -->
                                        <tr>
                                            <td colspan="4" align="end" style="font-weight: bold;">Grand Total: </td>
                                            <td colspan="1" style="font-weight: bold;"><?= number_format($totalAmount, 2); ?></td>
                                        </tr>
                                        <!-- Payment mode row -->
                                        <tr>
                                            <td colspan="5">Payment Mode: <?= validate($_SESSION['payment_mode']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } else {
                            echo '<h5>No items added</h5>';
                        }
                        ?>
                    </div>
                    <?php if (isset($_SESSION['productItems'])) : ?>
                        <!-- Save button -->
                        <div class="mt-4 text-end">
                            <form method="POST" action="order-code.php" class="saveOrderForm" style="display:inline-block; margin-right:5px;">
                                <input type="hidden" name="saveOrder" value="1">
                                <button type="button" name="saveOrder" class="btn btn-primary px-4 mx-1 saveOrderConfirm" data-confirm-text="Send this order request?">Save</button>
                            </form>
                            
                            <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()">Print</button>
                            <button class="btn btn-warning px-4 mx-1" onclick="downloadPDF('<?= $_SESSION['invoice_no'] ?? 'NA'; ?>')">Download PDF</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include footer file -->
<?php include('includes/footer.php') ?>
