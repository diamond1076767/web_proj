<?php include("includes/header.php");
if (!isset($_SESSION['loggedInUser']['roleID']) || $_SESSION['loggedInUser']['roleID'] != 2) {
        redirect('index.php', 'Access Denied. Manager only.');
        exit();
    }
?>

<!-- Container for the main content -->
<div class="container-fluid px-4">
    <!-- Card for updating user request -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <!-- Title and Back button -->
            <h4 class="mb-0">Update Request
                <a href="user-request.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage();?>
            
            <!-- Form for updating user request -->
            <form action="manager-code.php" method="POST">
            <?php 
                    // Set session variable with request ID if available
                    if (isset($_POST['requestId'])) {
                        $_SESSION['requestID'] = $_POST['requestId'];
                    }
    
                    // Retrieve request data based on session variable
                    if (isset($_SESSION['requestID'])) {
                        $requestID = $_SESSION['requestID'];
                        $requestData = getById('request_user', $requestID);
                    }
                    
                    // Check if request data is available
                    if ($requestData) {
                        if ($requestData['status'] == 200) {
                            ?>
                            <!-- Hidden field for request ID -->
                            <input type='hidden' name='requestId' value="<?= validate($requestData['data']['_id'])?>">
                            
                            <!-- Row for displaying details -->
                            <div class="row">
                                <!-- Display Role -->
                                <div class="col-md-4 mb-3">
                                    <label for="">Role</label>
                                    <?php 
                                        // Get role name based on role ID
                                        $requestId = validate($requestData['data']['_id']);
                                        $query = "SELECT r.roleName FROM request_user u
                                                  JOIN role r ON u.roleID = r._id
                                                  WHERE u._id = $requestId";
                                        $result = mysqli_query($con, $query);
                                        
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $roleName = validate($row['roleName']);
                                            ?>
                                            <!-- Display role name -->
                                            <input type="text" name="role_id" disabled value="<?= validate($roleName); ?>" class="form-control"/>
                                            <?php
                                        } else {
                                            // Display default if role not found
                                            echo '<input type="text" name="role_id" disabled value="Unknown Role" class="form-control"/>';
                                        }
                                    ?>
                                </div>
                
                                <!-- Display Requested By -->
                                <div class="col-md-4 mb-3">
                                    <label for="">Requested By</label>
                                    <?php 
                                        // Get username based on user ID
                                        $requestId = validate($requestData['data']['_id']);
                                        $query = "SELECT u.userName FROM request_user r
                                                  JOIN user u ON r.userID = u._id
                                                  WHERE r._id = $requestId";
                                        $result = mysqli_query($con, $query);
                                        
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $userName = validate($row['userName']);
                                            ?>
                                            <!-- Display username -->
                                            <input type="text" name="username" disabled value="<?= $userName; ?>" class="form-control"/>
                                            <?php
                                        } else {
                                            // Display default if username not found
                                            echo '<input type="text" name="role_id" disabled value="Unknown Username" class="form-control"/>';
                                        }
                                    ?>
                                </div>
                
                                <!-- Display Create Date -->
                                <div class="col-md-4 mb-3">
                                    <label>Create Date</label>
                                    <br/>
                                    <?php
                                        // Format and display create date
                                        $dateString = $requestData['data']['created_at'];
                                        $formattedDate = date('d F, Y', strtotime($dateString));
                                        ?>
                                        <input type="text" name="created_at" value="<?= validate($formattedDate) ?>" disabled class="form-control">
                                </div>
                                
                                <!-- Input fields for updating user details -->
                                <div class="col-md-6 mb-3">
                                    <label for="">Username *</label>
                                    <input type="text" name="username" required class="form-control" value="<?= validate($requestData['data']['userName'])?>" />
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="">Full Name *</label>
                                    <input type="text" name="fullname" required class="form-control" value="<?= validate($requestData['data']['fullName'])?>" />
                                </div>
                
                                <div class="col-md-6 mb-3">
                                    <label>Email *</label>
                                    <br/>
                                    <input type="email" name="email" required class="form-control" value="<?= validate(decryption($requestData['data']['email']))?>" >
                                </div>
                
                                <div class="col-md-6 mb-3">
                                    <label>Phone No. *</label>
                                    <br/>
                                    <input type="text" name="telephone" required class="form-control" value="<?= validate(decryption($requestData['data']['telephone']))?>" >
                                </div>
                                
                                <!-- Button for updating user request -->
                                <div class="col-md-8 mb-3 text-start">
                                    <button type="submit" name="updateUserRequest" class="btn btn-primary" style="margin-top:15px">Update</button>    
                                </div>
                            </div>
                        </form>
                            <?php
                        } else {
                            // Display message if request data status is not 200
                            echo '<h5>'.$requestData['message'].'</h5>';
                        }
                    } else {
                        // Display message if request data is not available
                        echo 'Something Went Wrong';
                        return false;
                    }
            ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php");?>
