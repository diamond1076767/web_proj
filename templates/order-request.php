<?php
include('includes/header.php');
allowedRole([2,3]);

// --- Validate and sanitize GET parameters ---
$createDate = isset($_GET['date']) ? validate($_GET['date']) : '';
$requestStatus = isset($_GET['request_status']) ? validate($_GET['request_status']) : '';

// --- Base Query ---
$query = "SELECT 
            o._id AS request_id, 
            o.created_at AS request_date, 
            o.*, 
            c.customerName, 
            c.telephone 
          FROM request_order o
          LEFT JOIN customer c ON c._id = o.customerID";

// --- Add filters dynamically ---
$conditions = [];

if ($createDate !== '') {
    $conditions[] = "DATE(o.created_at)='$createDate'";
}
if ($requestStatus !== '') {
    $conditions[] = "o.status='$requestStatus'";
}

// --- Append WHERE clause if there are conditions ---
if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY o._id DESC";

// --- Execute Query ---
$orders = mysqli_query($con, $query);
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="mb-0">Order Requests</h4>
                </div>
                <div class="col-md-8">
                    <form action="" method="GET">
                        <div class="row g-1">
                            <div class="col-md-4">
                                <input type="date"
                                    name="date"
                                    class="form-control"
                                    aria-label="Filter by date"
                                    value="<?= htmlspecialchars($createDate); ?>" />
                            </div>
                            <div class="col-md-4">
                                <select name="request_status" class="form-select" aria-label="Filter by request status">
                                    <option value="">Select Request Status</option>
                                    <?php foreach (['Pending', 'Approved', 'Declined'] as $status): ?>
                                        <option value="<?= $status ?>" <?= $requestStatus == $status ? 'selected' : ''; ?>>
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="order-request.php" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php alertMessage(); ?>

            <?php if ($orders && mysqli_num_rows($orders) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-items-center">
                        <thead>
                            <tr>
                                <th>Cust. Name</th>
                                <th>Cust. Phone</th>
                                <th>Payment Mode</th>
                                <th>Requester</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($orderItem = mysqli_fetch_assoc($orders)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($orderItem['customerName'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars(!empty($orderItem['telephone']) ? decryption($orderItem['telephone']) : '-'); ?></td>
                                    <td><?= htmlspecialchars($orderItem['payment_mode']); ?></td>
                                    <td>
                                        <?php
                                        // Fetch requester username securely
                                        $userID = validate($orderItem['userID']);
                                        $stmt = mysqli_prepare($con, "SELECT userName FROM user WHERE _id = ?");
                                        mysqli_stmt_bind_param($stmt, "i", $userID);
                                        mysqli_stmt_execute($stmt);
                                        $res = mysqli_stmt_get_result($stmt);
                                        $userData = mysqli_fetch_assoc($res);
                                        echo htmlspecialchars($userData['userName'] ?? 'Unknown');
                                        ?>
                                    </td>
                                    <td><?= date('d M, Y', strtotime($orderItem['request_date'])); ?></td>
                                    <td><?= htmlspecialchars($orderItem['status']); ?></td>
                                    <td>
                                        <form action="orders-request-view.php" method="post" style="display:inline-block;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']); ?>">
                                            <button type="submit" class="btn btn-info btn-sm">View</button>
                                        </form>

                                        <?php if ($orderItem['status'] == 'Pending'): ?>

                                            <?php if (isset($_SESSION['loggedInUser']['roleID']) && $_SESSION['loggedInUser']['roleID'] == 2): ?>
                                                <form action="order-request-approve.php" method="post" style="display:inline-block;">
                                                    <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']); ?>">
                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this request?')">Approve</button>
                                                </form>

                                                <form action="order-request-decline.php" method="post" style="display:inline-block;">
                                                    <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']); ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Decline this request?')">Decline</button>
                                                </form>
                                            <?php endif; ?>

                                        <?php elseif ($orderItem['status'] == 'Approved' || $orderItem['status'] == 'Declined'): ?>

                                            <?php if (isset($_SESSION['loggedInUser']['roleID']) && $_SESSION['loggedInUser']['roleID'] == 2): ?>
                                                <form action="order-request-delete.php" method="post" style="display:inline-block;">
                                                    <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']); ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this request?')">Delete</button>
                                                </form>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <h5>No Record Available</h5>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<?php if(isset($_SESSION['success_message'])):
    $msg = addslashes($_SESSION['success_message']);
    unset($_SESSION['success_message']);
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof swal === "function") {
        swal({
            title: "Success",
            text: "<?= $msg ?>",
            icon: "success",
            button: "OK"
        });
    }
});
</script>
<?php endif; ?>
