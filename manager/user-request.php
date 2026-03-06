<?php include('includes/header.php')?>

<!-- Container for the main content -->
<div class="container-fluid px-4">
    <!-- Card for displaying user requests with filter options -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <!-- Row containing the title and filter form -->
            <div class="row">
                <!-- Title section -->
                <div class="col-md-4">
                    <h4 class="mb=0">User Requests</h4>
                </div>
                <!-- Filter form section -->
                <div class="col-md-8">
                    <form action="" method="GET">
                        <!-- Row for date input, request status dropdown, and filter/reset buttons -->
                        <div class="row g-1">
                            <div class="col-md-4">
                                <input type="date" 
                                    name="date" 
                                    class="form-control"
                                    value="<?= isset($_GET['date']) == true ? $_GET['date']:'';?>"
                                />
                            </div>
                            <div class="col-md-4">
                                <select name="request_status" class="form-select">
                                    <!-- Options for request status filter -->
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
                                <a href="user-request.php" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Card body for displaying user requests -->
        <div class="card-body">
            <!-- Display alert messages if any -->
            <?php alertMessage();?>
            <?php 
            // Check if the user is logged in and get the user ID
            if(isset($_SESSION['loggedIn'])){
                $userID = validate($_SESSION['loggedInUser']['user_id']);
            }
            
            // Build the query based on filter parameters
            if(isset($_GET['date']) || isset($_GET['request_status'])){
                
                $requestDate = validate($_GET['date']);
                $requestStatus = validate($_GET['request_status']);
                
                if($requestDate != '' && $requestStatus == ''){
                    $query = "SELECT * FROM request_user 
                    WHERE DATE(created_at)='$requestDate' AND userID='$userID' ORDER BY _id DESC";
                    
                } elseif($requestDate == '' && $requestStatus != ''){
                    $query = "SELECT * FROM request_user 
                    WHERE status='$requestStatus' AND userID='$userID' ORDER BY _id DESC";
                    
                } elseif($requestDate != '' && $requestStatus != ''){
                    $query = "SELECT * FROM request_user 
                    WHERE DATE(created_at)='$requestDate' 
                    AND status='$requestStatus' AND userID='$userID' ORDER BY _id DESC";
                    
                } else {
                    $query = "SELECT * FROM request_user WHERE userID='$userID' ORDER BY _id DESC";
                }
            } else {
                $query = "SELECT * FROM request_user WHERE userID='$userID' ORDER BY _id DESC";
            }
            
            // Execute the query to get user requests
            $requests = mysqli_query($con, $query);
            if($requests){
                if(mysqli_num_rows($requests)>0){
                    ?>
                    <!-- Display user requests in a table -->
                    <table class="table table-striped table-bordered align-items-center justify-content-center">
                        <thead>
                            <tr>
                                <!-- Table headers -->
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Role</th>
                                <th>Phone No.</th>
                                <th>Email</th>
                                <th>Requester</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($requests as $reqItem) : ?>
                            <!-- Table rows with user request details -->
                            <tr>
                                <!-- Hidden fields for role ID and request ID -->
                                <input type="hidden" class="hidden-roleid" value="<?= $reqItem['roleID'] ?>" />
                                <input type="hidden" class="hidden-id" value="<?= $reqItem['_id'] ?>" />
                                <td><?= $reqItem['userName'];?></td>
                                <td><?= $reqItem['fullName'];?></td>
                                <td>
                                    <?php
                                    // Get role name based on role ID
                                    $roleID = $reqItem['roleID'];
                                    $query = "SELECT * FROM role WHERE _id='$roleID'";
                                    $result = mysqli_query($con, $query);
                                
                                    if ($result) {
                                        $roleData = mysqli_fetch_assoc($result);
                                        echo $roleData['roleName']; // Assuming the role name column in your role table is 'roleName'
                                    } else {
                                        echo "Role not found"; // Handle the case where the role is not found.
                                    }
                                    ?>
                                </td>
                                <td><?= validate(decryption($reqItem['telephone']));?></td>
                                <td><?= validate(decryption($reqItem['email']));?></td>
                                <td>
                                    <?php
                                    // Get requester's username based on user ID
                                    $userID = $reqItem['userID'];
                                    $query = "SELECT userName FROM user WHERE _id='$userID'";
                                    $result = mysqli_query($con, $query);
                                    
                                    if ($result) {
                                        $userData = mysqli_fetch_assoc($result);
                                        echo $userData['userName'];
                                    } else {
                                        echo "Username not found"; // Handle the case where the username is not found.
                                    }
                                    ?>
                                </td>
                                <td><?= date('d M, Y', strtotime($reqItem['created_at']));?></td>
                                <td><?= $reqItem['status'];?></td>
                                <td>
                                    <!-- Action buttons based on request status -->
                                    <?php if ($reqItem['status'] == 'Pending') : ?>
                                        <!-- Edit and delete buttons for pending requests -->
                                        <form action="user-request-edit.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Edit</button>
                                        </form>

                                        <form action="user-request-delete.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
                                        </form>
                                    <?php else : ?>
                                        <!-- Delete button for approved or declined requests -->
                                        <form action="user-request-delete.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
                                        </form>
                                    <?php endif; ?>
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

<!-- Include footer -->
<?php include('includes/footer.php');?>
