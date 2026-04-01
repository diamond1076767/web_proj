<?php include("includes/header.php");
allowedRole([1]);
?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Edit User
				<a href="admin.php" class="btn btn-primary float-end">Back</a>
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			
			<form action="admin-code.php" method="POST">
				
				<?php
				    if (isset($_POST['userId'])) {
						$_SESSION['userID'] = $_POST['userId'];
						}
		
						if (isset($_SESSION['userID'])) {
							$userID = $_SESSION['userID'];
							$userData = getById('user', $userID);
						}
						
						if($userData['status'] == 200)
						{
						?>  
				                <input type='hidden' name='userId' value="<?= $userData['data']['_id']?>">
				                <div class="row">
				                	<div class="col-md-12 mb-3">
                                        <label for="role_id">Role</label>
                                        <?php 
                                            $userId = $userData['data']['_id'];
                                            $query = "SELECT r.roleName FROM user u
                                                      JOIN role r ON u.roleID = r._id
                                                      WHERE u._id = $userId";
                                            $result = mysqli_query($con, $query);
                                            
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $roleName = $row['roleName'];
                                                ?>
                                                <input type="text" id="role_id" name="role_id" disabled value="<?= $roleName; ?>" class="form-control"/>
                                                <?php
                                            } else {
                                                echo '<input type="text" id="role_id" name="role_id" disabled value="Unknown Role" class="form-control"/>';
                                            }
                                        ?>
                                    </div>
                					<div class="col-md-6 mb-3">
                    						<label for="username">Username *</label>
                    						<input type="text" id="username" name="username" required value="<?= $userData['data']['userName'];?>" class="form-control" />
                    				</div>
                    				<div class="col-md-6 mb-3">
                    						<label for="fullname">Full Name *</label>
                    						<input type="text" id="fullname" name="fullname" required value="<?= decryption($userData['data']['fullName']);?>" class="form-control" />
                    				</div>
                    				<div class="col-md-6 mb-3">
                    						<label for="email">Email *</label>
                    						<input type="email" id="email" name="email" required value="<?= decryption($userData['data']['email']);?>" class="form-control" />
                    				</div>
                    				<div class="col-md-6 mb-3">
                    						<label for="phone">Phone Number *</label>
                    						<input type="number" id="phone" name="phone" required value="<?= decryption($userData['data']['telephone']);?>" class="form-control" />
                    				</div>
                    				<div class="col-md-6 mb-3">
                    						<label for="password">Password </label>
                    						<input type="password" id="password" name="password" class="form-control" />	
                    				</div>
                    				<div class="col-md-6 mb-3">
                    						<label for="confirmpassword">Confirm Password </label>
                    						<input type="password" id="confirmpassword" name="confirmpassword" class="form-control" />	
                    				</div>
                    				<div class="col-md-6 mb-3">
                    						<label for="lock_acc">Lock Account</label>
                                       <br/>
                    						<input type="checkbox" id="lock_acc" name="lock_acc" <?=$userData['data']['lock_acc'] == true ? 'checked':''?> style="width:30px;height:30px;" />
                    				</div>
                    				<div class="col-md-6 mb-3 text-end">
                    					<button type="submit" name="updateUser" class="btn btn-primary" style="margin-top:10px">Submit</button>
                    				</div>
            					</div>
            			</form>
				                <?php
				            }else{
				                echo '<h5>'.$userData['message'].'</h5>';
				            }
                  
				?>
		</div>
	</div>
</div>

<?php include("includes/footer.php");?>
