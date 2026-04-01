<?php include("includes/header.php");
allowedRole([1]);
?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Add New User
				<a href="admin.php" class="btn btn-primary float-end">Back</a>
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			<form action="admin-code.php" method="POST">
				<div class="row">
					<div class="col-md-12 mb-3">
                        <label for="role_id">Select User Role</label>
                        <select id="role_id" name="role_id" class="form-select mySelect2">
                            <option value="">-- Select User Role --</option>
                            <?php
                            $query = "SELECT * FROM role WHERE _id <> 1";
                            $roles = mysqli_query($con, $query);
                            if ($roles) {
                                if (mysqli_num_rows($roles) > 0) {
                                    foreach ($roles as $roleItem) {
                                        $roleName = $roleItem['roleName'];
                                        
                                        ?>
                                        <option value="<?= $roleItem['_id'];?>"><?= $roleItem['roleName']; ?></option>
                                        <?php
                                    }
                                } else {
                                    echo '<option value="">No User Role Found</option>';
                                }
                            } else {
                                echo '<option value="">Something Went Wrong</option>';
                            }
                            ?>
                        </select>
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
    					<label for="email">Email Address *</label>
    					<input type="email" id="email" name="email" required class="form-control" />
    				</div>
    				<div class="col-md-6 mb-3">
    					<label for="phone">Phone Number *</label>
    					<input type="text" id="phone" name="phone" required class="form-control" />
    				</div>
    				<div class="col-md-6 mb-3">
    					<label for="password">Password *</label>
    					<input type="password" id="password" name="password" required class="form-control" />
    				</div>
    				<div class="col-md-6 mb-3">
    					<label for="confirmpassword">Confirm Password *</label>
    					<input type="password" id="confirmpassword" name="confirmpassword" required class="form-control" />
    				</div>
    				<div class="col-md-6 mb-3">
    					<label for="lock_acc">Lock Account</label>
    					<br/>
    					<input type="checkbox" id="lock_acc" name="lock_acc" value="1" style="width:30px;height:30px;">
    				</div>
    				<div class="col-md-6 mb-3 text-end">
    					<button type="submit" name="createUser" class="btn btn-primary" style="margin-top:10px">Submit</button>
    				</div>
				</div>
			</form>
		</div>
	</div>
</div>


<?php include("includes/footer.php");?>
