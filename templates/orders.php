<?php
// Include header file with common elements
include('includes/header.php');
allowedRole([1,2,3]);
?>

<div class="container-fluid px-4">
    <!-- Card for displaying orders with filter options -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">            
            <div class="row">
                <div class="col-md-4">
                    <!-- Header with title "Orders" -->
                    <h4 class="mb=0">Orders</h4>
                </div>
                <div class="col-md-8">
                    <!-- Form for filtering orders based on date and payment status -->
                    <form action="" method="GET">
                        <div class="row g-1">
                            <!-- Date input -->
                            <div class="col-md-4">
                                <input type="date" 
                                    name="date" 
                                    class="form-control"
                                    value="<?= isset($_GET['date']) == true ? validate($_GET['date']) : ''; ?>"
                                />
                            </div>
                            <!-- Payment status dropdown -->
                            <div class="col-md-4">
                                <select name="payment_status" class="form-select">
                                    <option value="">
                                        Select Payment Status
                                    </option>
                                    <option value="Cash Payment"
                                    <?= isset($_GET['payment_status']) == true && validate($_GET['payment_status']) == 'Cash Payment' ? 'selected' : ''; ?>
                                    >
                                        Cash Payment
                                    </option>
                                    <option value="Online Payment"
                                        <?= isset($_GET['payment_status']) == true && validate($_GET['payment_status']) == 'Online Payment' ? 'selected' : ''; ?>
                                    >
                                        Online Payment
                                    </option>
                                </select>
                            </div>
                            <!-- Filter and Reset buttons -->
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="orders.php" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php 
            // Check if date or payment_status is set in GET parameters
            if(isset($_GET['date']) || isset($_GET['payment_status'])){
                // Retrieve date and payment_status from GET parameters
                $orderDate = validate($_GET['date']);
                $paymentStatus = validate($_GET['payment_status']);
                
                // Check filter conditions and execute corresponding query
                if($orderDate != '' && $paymentStatus == ''){
                    $query = "SELECT o.*, c.* FROM sales_order o, customer c
                        WHERE c._id = o.customerID AND o.order_date=? ORDER BY o._id DESC";
                    
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("s", $orderDate);
                    $stmt->execute();
                    
                } elseif($orderDate == '' && $paymentStatus != ''){
                    $query = "SELECT o.*, c.* FROM sales_order o, customer c
                        WHERE c._id = o.customerID AND o.payment_mode=? ORDER BY o._id DESC";
                    
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("s", $paymentStatus);
                    $stmt->execute();
                    
                } elseif($orderDate != '' && $paymentStatus != ''){
                    $query = "SELECT o.*, c.* FROM sales_order o, customer c
                        WHERE c._id = o.customerID 
                        AND o.order_date=? 
                        AND o.payment_mode=? ORDER BY o._id DESC";
                    
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("ss", $orderDate, $paymentStatus);
                    $stmt->execute();
                    
                } else {
                    // Default query without filters
                    $query = "SELECT o.*, c.* FROM sales_order o, customer c
                        WHERE c._id = o.customerID ORDER BY o._id DESC";
                    
                    $stmt = $con->prepare($query);
                    $stmt->execute();
                }
                
                // Get results from the executed query
                $result = $stmt->get_result();
                
            } else {
                // If no filters applied, fetch all orders
                $query = "SELECT o.*, c.* FROM sales_order o, customer c 
                    WHERE c._id = o.customerID ORDER BY o._id DESC";
                
                $result = mysqli_query($con, $query);
            }
            
            // Display orders based on the result
            if($result){
                if(mysqli_num_rows($result) > 0){
                    ?>
                    <!-- Table to display orders -->
                    <table class="table table-striped table-bordered align-items-center justify-content-center">
                        <thead>
                            <tr>
                                <th>Tracking No.</th>
                                <th>Cust. Name</th>
                                <th>Cust. Phone</th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Payment Mode</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($result as $orderItem) : ?>
                            <tr>
                                <!-- Display order details -->
                                <td class="fw-bold"><?= validate($orderItem['tracking_no']);?></td>
                                <td><?= validate($orderItem['customerName']);?></td>
                                <td><?= validate(decryption($orderItem['telephone']));?></td>
                                <td><?= validate(date('d M, Y', strtotime($orderItem['order_date'])));?></td>
                                <td><?= validate($orderItem['order_status']);?></td>
                                <td><?= validate($orderItem['payment_mode']);?></td>
                                <td>
                                    <?php
                                    // Retrieve and display the username who created the order
                                    $userID = $orderItem['userID'];
                                    $query = "SELECT userName FROM user WHERE _id=?";
                                    
                                    $stmtUser = $con->prepare($query);
                                    $stmtUser->bind_param("i", $userID);
                                    $stmtUser->execute();
                                    $resultUser = $stmtUser->get_result();
                                    
                                    if ($resultUser) {
                                        $userData = mysqli_fetch_assoc($resultUser);
                                        echo validate($userData['userName']);
                                    } else {
                                        echo "Username not found"; // Handle the case where the username is not found.
                                    }
                                    ?>
                                </td>

                                <td>
                                        <!-- Buttons for viewing and printing orders -->
                                        <form action="orders-view.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="orderId" value="<?= validate($orderItem['tracking_no']) ?>">
                                            <button type="submit" class="btn btn-info btn-sm">View</button>
                                        </form>
                                        
                                        <form action="orders-view-print.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="trackId" value="<?= validate($orderItem['tracking_no']) ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">Print</button>
                                        </form>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<h5>No Record Available</h5>";
                }
            } else {
                echo "<h5>Something Went Wrong</h5>";
            }
            ?>

        </div>
    </div>
</div>

<?php 
// Include footer file with common elements
include('includes/footer.php');
?>

<?php if(isset($_SESSION['success_message'])): 
    $msg = addslashes($_SESSION['success_message']); // escape quotes
    unset($_SESSION['success_message']);
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Ensure the DOM and SweetAlert are loaded
    if (typeof swal === "function") {
        swal({
            title: "Success",
            text: "<?= $msg ?>",
            icon: "success",
            button: "OK"
        });
    } else {
        console.warn("SweetAlert not loaded");
    }
});
</script>
<?php endif; ?>
