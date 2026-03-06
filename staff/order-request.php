<?php include('includes/header.php');?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">            
            <div class="row">
                <div class="col-md-4">
                    <h4 class="mb=0">Order Requests</h4>
                </div>
                <div class="col-md-8">
                    <form action="" method="GET">
                        <div class="row g-1">
                            <div class="col-md-4">
                                <!-- Date filter -->
                                <input type="date" 
                                    name="date" 
                                    class="form-control"
                                    value="<?= isset($_GET['date']) == true ? $_GET['date']:'';?>"
                                />
                            </div>
                            <div class="col-md-4">
                                <!-- Request status filter -->
                                <select name="request_status" class="form-select">
                                    <option value="">
                                        Select Request Status</option>
                                    <option value="Pending"
                                       <?= 
                                       isset($_GET['request_status']) == true 
                                       ?
                                       ($_GET['request_status'] == 'Pending' ? 'selected':'')
                                       :
                                       '';
                                       ?>
                                        >
                                        Pending
                                        </option>
                                    <option value="Approved"
                                       <?= 
                                       isset($_GET['request_status']) == true 
                                       ?
                                       ($_GET['request_status'] == 'Approved' ? 'selected':'')
                                       :
                                       '';
                                       ?>
                                       	>
                                       	Approved
                                       	</option>
                                    <option value="Declined"
                                       <?= 
                                       isset($_GET['request_status']) == true 
                                       ?
                                       ($_GET['request_status'] == 'Declined' ? 'selected':'')
                                       :
                                       '';
                                       ?>
                                       	>
                                       	Declined
                                       	</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <!-- Filter and Reset buttons -->
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="order-request.php" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
        <?php alertMessage();?>
            <?php 
            // Check if the user is logged in
            if(isset($_SESSION['loggedIn'])){
                $userID = validate($_SESSION['loggedInUser']['user_id']);
            }
            
            if(isset($_GET['date']) || isset($_GET['request_status'])){
                
                $createDate = validate($_GET['date']);
                $requestStatus = validate($_GET['request_status']);
                
                if($createDate != '' && $requestStatus == ''){
                    // Filter by date
                    $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID AND DATE(o.created_at)=? AND userID=? ORDER BY request_id DESC";
                    
                    // Prepare the statement
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, 'si', $createDate, $userID);
                    
                }elseif($createDate == '' && $requestStatus != ''){
                    // Filter by request status
                    $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID AND o.status=? AND userID=? ORDER BY request_id DESC";
                    
                    // Prepare the statement
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, 'si', $requestStatus, $userID);
                    
                }elseif($createDate != '' && $requestStatus != ''){
                    // Filter by both date and request status
                    $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID 
                    AND DATE(o.created_at)=?
                    AND o.status=? AND userID=? ORDER BY request_id DESC";
                    
                    // Prepare the statement
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, 'ssi', $createDate, $requestStatus, $userID);
                    
                }else{
                    // No filters, get all orders for the user
                    $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID AND userID=? ORDER BY request_id DESC";
                    
                    // Prepare the statement
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, 'i', $userID);
                }
                
                // Execute the statement
                mysqli_stmt_execute($stmt);
                
                // Get the result
                $result = mysqli_stmt_get_result($stmt);
                
            }else{
                // No filters, get all orders for the user
                $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c 
                    WHERE c._id = o.customerID AND userID=? ORDER BY request_id DESC";
                
                // Prepare the statement
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, 'i', $userID);
                
                // Execute the statement
                mysqli_stmt_execute($stmt);
                
                // Get the result
                $result = mysqli_stmt_get_result($stmt);
            }
            
            if($result){
                 if(mysqli_num_rows($result) > 0){
                     ?>
                     <!-- Display the orders in a table -->
                     <table class="table table-striped table-bordered align-items-center justify-content-center">
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
                            <?php foreach($result as $orderItem) : ?>
                            <tr>
                                <td><?= validate($orderItem['customerName']);?></td>
                                <td><?= validate(decryption($orderItem['telephone']));?></td>
                                
                                <td><?= validate($orderItem['payment_mode']);?></td>
                                <td>
                                    <?php
                                    // Get the username of the requester
                                    $userID = validate($orderItem['userID']);
                                    $query = "SELECT userName FROM user WHERE _id=?";
                                    $stmt = mysqli_prepare($con, $query);
                                    mysqli_stmt_bind_param($stmt, 'i', $userID);
                                    mysqli_stmt_execute($stmt);
                                    $userData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
                                    
                                    echo validate($userData['userName']);
                                    ?>
                                </td>
                                <td><?= date('d M, Y', strtotime(validate($orderItem['request_date'])));?></td>
                                <td><?= validate($orderItem['status']);?></td>
                                <td>
                                        <form action="orders-request-view.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']) ?>">
                                            <button type="submit" class="btn btn-info btn-sm">View</button>
                                        </form>
                                        
                                        <form action="order-request-delete.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
                                        </form>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                     </table>
                     <?php
                 }else{
                     echo "<h5>No Record Available</h5>";
                 }
             }else{
                 echo "<h5>Something Went Wrong</h5>";
             }
            ?>
            
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
