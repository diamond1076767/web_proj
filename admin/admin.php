<?php include("includes/header.php");?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
				<div class="card-header">			
			<div class="row">
				<div class="col-md-4">
					<h4 class="mb-0">Manager/Staff</h4>
				</div>
				<div class="col-md-8">
					<form action="" method="GET">
						<div class="row g-1">
							<div class="col-md-4">
								<input type="date" 
									name="date" 
									class="form-control"
									value="<?= isset($_GET['date']) == true ? $_GET['date']:'';?>"
								/>
							</div>
							
							<div class="col-md-4">
								<select name="role_status" class="form-select">
									<option value="">
										Select User Role</option>
									<option value="Manager"
									   <?= 
									   isset($_GET['role_status']) == true 
									   ?
									   ($_GET['role_status'] == 'Manager' ? 'selected':'')
									   :
									   '';
									   ?>
										>
										Manager
										</option>
									<option value="Staff"
									   <?=
									   isset($_GET['role_status']) == true 
									   ?
									   ($_GET['role_status'] == 'Staff' ? 'selected':'')
									   :
									   '';
									   ?>
									   	>
									   	Staff
									   	</option>
								</select>
							</div>
							<div class="col-md-4">
								<button type="submit" class="btn btn-primary">Filter</button>
								<a href="manager.php" class="btn btn-danger">Reset</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="card-body">
			<?php alertMessage();?>

			<?php
			if (isset($_GET['date']) || isset($_GET['role_status'])) {
			    $createDate = validate($_GET['date']);
			    $roleStatus = validate($_GET['role_status']);
			    
			    if ($createDate != '' && $roleStatus == '') {
			        $query = "SELECT o._id AS user_id, o.*, c.* FROM user o, role c
                    WHERE o.roleID = c._id AND DATE(o.created_at)='$createDate'";
			    } elseif ($createDate == '' && $roleStatus != '') {
			        $query = "SELECT o._id AS user_id, o.*, c.* FROM user o, role c
                    WHERE o.roleID = c._id AND c.roleName='$roleStatus'";
			    } elseif ($createDate != '' && $roleStatus != '') {
			        $query = "SELECT o._id AS user_id, o.*, c.* FROM user o, role c
                    WHERE o.roleID = c._id
                    AND DATE(o.created_at)='$createDate'
                    AND c.roleName='$roleStatus'";
			    } else {
			        $query = "SELECT *, _id AS user_id FROM user WHERE _id <> 1";
			    }
			}else{
			    $query = "SELECT *, _id AS user_id FROM user WHERE _id <> 1";
			}
	        $users = mysqli_query($con, $query);
	
            if(!$users){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            
            if(mysqli_num_rows($users)>0)
    		{
			?>
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
    						<th>ID</th>
    						<th>Username</th>
    						<th>Full Name</th>
    						<th>Phone No.</th>
    						<th>Email</th>
    						<th>Role</th>
    						<th>Action</th>
    					</tr>
					</thead>
					<tbody>
					<?php foreach($users as $userItem) : ?>
    			    <tr>
						<td><?= $userItem['user_id']?></td>
						<td><?= $userItem['userName']?></td>
						<td><?= decryption($userItem['fullName'])?></td>
						<td><?= decryption($userItem['telephone'])?></td>
						<td><?= decryption($userItem['email'])?></td>
												<td>
							<?php
                            $roleID = $userItem['roleID'];
                   
                            $query = "SELECT roleName FROM role WHERE _id = $roleID";
                            $result = mysqli_query($con, $query);
                        
                            if ($result) {
                                $roleRow = mysqli_fetch_assoc($result);
                                $roleName = $roleRow['roleName'];
                        
                                echo $roleName;
                            } else {
                                echo "Error retrieving user role information";
                            }
                            ?>
						</td>
						<td>
							<?php 
							if($userItem['lock_acc']==1){
							 echo '<span class="badge bg-danger">Banned</span>';
							}else{
                        	 echo '<span class="badge bg-primary">Active</span>';
							}
							?>
						</td>
						
						<td>
							<form action="admin-edit.php" method="post" style="display: inline-block; margin-right: 1px;">
                               <input type="hidden" name="userId" value="<?= validate($userItem['_id']) ?>">
                                <button type="submit" class="btn btn-success btn-sm">Edit</button>
                            </form>
							<form action="admin-delete.php" method="post" style="display: inline-block; margin-right: 1px;">
                               <input type="hidden" name="userId" value="<?= validate($userItem['_id']) ?>">
							   <button type="submit" class="btn btn-danger btn-sm" onclick= "return confirm('Are you sure you want to remove this user?')">Delete</button>
                            </form>
						</td>
					</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
			
			<?php
            }
            else
    	    {
    	       ?>
    				<h4 class="mb-0">No Record found</h4>
    			<?php
            }
			?>
		</div>
	</div>
</div>

<?php include("includes/footer.php");?>