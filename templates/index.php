<?php include("includes/header.php");
allowedRole([1,2,3]);?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12 mb-3">
            <h1 class="mt-4">Dashboard</h1>
            <div class="my-2">
                <?php alertMessage(); ?>
            </div>
        </div>

        <?php 
        $roleID = $_SESSION['loggedInUser']['roleID'];
        $userID = validate($_SESSION['loggedInUser']['user_id']);
        ?>

        <!-- Shared Cards: Products, Orders, Categories -->
        <div class="col-md-3 mb-3">
            <div class="card card-body border-primary p-3" style="border-width:medium">
                <p class="text-sm mb-0 text-capitalize">Total Products</p>
                <h5 class="font-bold mb-0"><?= getCount('inventory') ?></h5>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body border-info p-3" style="border-width:medium">
                <p class="text-sm mb-0 text-capitalize">Total Orders</p>
                <h5 class="font-bold mb-0"><?= getCount('sales_order') ?></h5>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body border-warning p-3" style="border-width:medium">
                <p class="text-sm mb-0 text-capitalize">Today Orders</p>
                <h5 class="fw-bold mb-0">
                    <?php 
                    $todayDate = date('Y-m-d');
                    $todayOrders = mysqli_query($con, "SELECT * FROM sales_order WHERE order_date='$todayDate'");
                    echo ($todayOrders && mysqli_num_rows($todayOrders) > 0) ? mysqli_num_rows($todayOrders) : "0";
                    ?>
                </h5>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body border-dark p-3" style="border-width:medium">
                <p class="text-sm mb-0 text-capitalize">Total Categories</p>
                <h5 class="font-bold mb-0"><?= getCount('categories') ?></h5>
            </div>
        </div>

        <!-- Shared Cards for both Admin & Manager -->
            <div class="col-md-12 mb-3">
                <hr>
                <h5>Sales Orders</h5>
            </div>

        <!-- Admin-specific metrics -->
        <?php if($roleID == 1): ?>
            <div class="col-md-3 mb-3">
                <div class="card card-body border-secondary p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Total Users</p>
                    <h5 class="font-bold mb-0"><?= getCount('user') ?></h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body border-success p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Approved User Requests</p>
                    <h5 class="font-bold mb-0">
                        <?php 
                        $totalRequests = mysqli_query($con, "SELECT * FROM request_user WHERE status='Approved'");
                        echo ($totalRequests && mysqli_num_rows($totalRequests) > 0) ? mysqli_num_rows($totalRequests) : "0";
                        ?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body border-danger p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Pending User Requests</p>
                    <h5 class="font-bold mb-0">
                        <?php 
                        $totalRequests = mysqli_query($con, "SELECT * FROM request_user WHERE status='Pending'");
                        echo ($totalRequests && mysqli_num_rows($totalRequests) > 0) ? mysqli_num_rows($totalRequests) : "0";
                        ?>
                    </h5>
                </div>
            </div>
        <?php endif; ?>

        <!-- Manager-specific metrics -->
        <?php if($roleID == 2): ?>
            <div class="col-md-3 mb-3">
                <div class="card card-body border-secondary p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Total Customers</p>
                    <h5 class="font-bold mb-0"><?= getCount('customer') ?></h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body border-success p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Pending User Requests</p>
                    <h5 class="font-bold mb-0">
                        <?php 
                        $result = mysqli_query($con, "
                        SELECT COUNT(*) as total 
                        FROM request_user 
                        WHERE status='Pending'
                        ");

                        $row = mysqli_fetch_assoc($result);
                        echo $row['total'] ?? 0;
                        ?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body border-danger p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Approved User Requests</p>
                    <h5 class="font-bold mb-0">
                        <?php 
                        $result = mysqli_query($con, "
                            SELECT COUNT(*) as total 
                            FROM request_user 
                            WHERE status='Approved'
                        ");

                        $row = mysqli_fetch_assoc($result);
                        echo $row['total'] ?? 0;
                        ?>
                        </h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body border-muted p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Pending Order Requests</p>
                    <h5 class="font-bold mb-0">
                        <?php 
                        $totalRequests = mysqli_query($con, "SELECT * FROM request_order WHERE status='Pending'");
                        echo ($totalRequests && mysqli_num_rows($totalRequests) > 0) ? mysqli_num_rows($totalRequests) : "0";
                        ?>
                    </h5>
                </div>
            </div>
        <?php endif; ?>

        <!-- Staff-specific metrics -->
        <?php if($roleID == 3): ?>
            <div class="col-md-3 mb-3">
                <div class="card card-body border-secondary p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Total Customers</p>
                    <h5 class="font-bold mb-0"><?= getCount('customer') ?></h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body border-muted p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Pending Order Requests</p>
                    <h5 class="font-bold mb-0">
                        <?php 
                        $totalRequests = mysqli_query($con, "SELECT * FROM request_order WHERE status='Pending' AND userID='$userID'");
                        echo ($totalRequests && mysqli_num_rows($totalRequests) > 0) ? mysqli_num_rows($totalRequests) : "0";
                        ?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body border-success p-3" style="border-width:medium">
                    <p class="text-sm mb-0 text-capitalize">Approved Order Requests</p>
                    <h5 class="font-bold mb-0">
                        <?php 
                        $totalRequests = mysqli_query($con, "SELECT * FROM request_order WHERE status='Approved' AND userID='$userID'");
                        echo ($totalRequests && mysqli_num_rows($totalRequests) > 0) ? mysqli_num_rows($totalRequests) : "0";
                        ?>
                    </h5>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include("includes/footer.php");?>