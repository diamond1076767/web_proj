<?php
// Include header file with common elements
include('includes/header.php');

// Check if productItems session is not set, redirect to order-request-create.php
if (!isset($_SESSION['productItems'])) {
    header('Location: order-request-create.php');
    exit(); // Added exit to prevent further execution
}

allowedRole([2,3]);
?>

<!-- Order Success Modal -->
<div class="modal fade" id="orderSuccessModal" data-bs-backdrop='static' data-bs-keyboard='false' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="mb-3 p-4">
                    <!-- Order success message placeholder -->
                    <h5 id="orderPlaceSuccessMessage"></h5>
                </div>
                <!-- Close button -->
                <a href="order-request.php" type="button" class="btn btn-secondary">Close</a>
            </div>
        </div>
    </div>
</div>

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

                            // Validate customer phone and retrieve data using prepared statement
                            $customerQuery = mysqli_prepare($con, "SELECT * FROM customer WHERE telephone=? LIMIT 1");
                            mysqli_stmt_bind_param($customerQuery, "i", $phone);
                            mysqli_stmt_execute($customerQuery);
                            $result = mysqli_stmt_get_result($customerQuery);

                            if ($result && mysqli_num_rows($result) > 0) {
                                $cRowData = mysqli_fetch_assoc($result);
                                ?>
                                <!-- Display customer and invoice details -->
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
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Name: <?= validate($cRowData['customerName']); ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Company: <?= validate($cRowData['companyName']); ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Phone No.: <?= validate(decryption($cRowData['telephone'])); ?></p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Email Addr.: <?= validate(decryption($cRowData['email'])); ?></p>
                                            </td>
                                            <td align="end">
                                                <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Invoice Details</h5>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice No.: -</p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice Date: -</p>
                                                <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: 21 Tampines Ave 1, Singapore 529757</p>
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

                                                <td style="border-bottom: 1px solid #ccc;"><?= validate(number_format($row['price'], 0)); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= validate($row['quantity']); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                    <?= validate(number_format($row['price'] * $row['quantity'], 0)) ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <!-- Grand total row -->
                                        <tr>
                                            <td colspan="4" align="end" style="font-weight: bold;">Grand Total: </td>
                                            <td colspan="1" style="font-weight: bold;"><?= number_format($totalAmount, 0); ?></td>
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
                            <button type="button" class="btn btn-primary px-4 mx-1" id="saveOrder">Save</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include footer file -->
<?php include('includes/footer.php') ?>
