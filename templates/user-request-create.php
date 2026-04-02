<?php include("includes/header.php");
allowedRole([2]);
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add User Request
                <a href="user-request.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage();?>
            
            <form action="admin-code.php" method="POST">
            
                <div class="row">
                
                    <div class="col-md-4 mb-3">
                        <label for="role_id">Request Role</label>
                        <br/>
                        <!-- Displaying the requested role (in this case, role with ID 3) -->
                        <input type="text" id="role_id" name="role_id" disabled class="form-control" value="<?php 
                            $query = "SELECT * FROM role WHERE _id = 3";
                            $role = mysqli_query($con, $query);
                            if ($role){
                                if(mysqli_num_rows($role) > 0){
                                    $roleItem = mysqli_fetch_assoc($role);
                                    echo $roleItem['roleName'];
                                }else{
                                    echo 'No Roles found';
                                }
                            }else{
                                echo 'Something Went Wrong!';
                            }
                        ?>">
                    </div>
                
                    <div class="col-md-4 mb-3">
                        <label for="requesterName">Requested By</label>
                        <!-- Displaying the username of the logged-in user (locked) -->
                        <input id="requesterName" type="text" name="requesterName" disabled class="form-control" value="<?php
                            if (isset($_SESSION['loggedIn'])) {
                                echo $_SESSION['loggedInUser']['username'];
                            }
                        ?>">
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label for="request_date">Create Date</label>
                        <br/>
                        <!-- Displaying the current date -->
                        <input id="request_date" type="text" name="date" disabled class="form-control" value="<?php echo date('j F, Y')?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" required class="form-control" />
                    </div>
                
                    <div class="col-md-6 mb-3">
                        <label for="fullname">Full Name *</label>
                        <input type="text" id="fullname" name="fullname" required class="form-control" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email">Email *</label>
                        <br/>
                        <input id="email" type="email" name="email" required class="form-control">
                    </div>

                
                    <div class="col-md-6 mb-3">
                        <label for="telephone">Phone No. *</label>
                        <br/>
                        <input id="telephone" type="text" name="telephone" required class="form-control">
                    </div>
                    
                    <div class="col-md-8 mb-3 text-start">
                        <!-- Submit button for the form -->
                        <button type="submit" name="saveUserRequest" class="btn btn-primary" style="margin-top:15px">Submit</button>    
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php");?>
