<?php include("includes/header.php");?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
				<div class="card-header">			
			<div class="row">
				<div class="col-md-4">
					<h4 class="mb-0">Staff</h4>
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
								<select name="lock_acc" class="form-select">
									<option value="">
										Select User Status</option>
									<option value="0"
									   <?= 
									   isset($_GET['lock_acc']) == true 
									   ?
									   ($_GET['lock_acc'] == '0' ? 'selected':'')
									   :
									   '';
									   ?>
										>
										Active
										</option>
									<option value="1"
									   <?= 
									   isset($_GET['lock_acc']) == true 
									   ?
									   ($_GET['lock_acc'] == '1' ? 'selected':'')
									   :
									   '';
									   ?>
									   	>
									   	Inactive
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
			<?php 
			
			if (isset($_GET['date']) || isset($_GET['lock_acc'])) {
			    $createDate = validate($_GET['date']);
			    $lockAcc = validate($_GET['lock_acc']);
			    
			    if ($createDate != '' && $lockAcc == '') {
			        $query = "SELECT * FROM user 
                    WHERE DATE(created_at)='$createDate' AND roleID = 3";
			    } elseif ($createDate == '' && $lockAcc != '') {
			        $query = "SELECT * FROM user
                    WHERE lock_acc='$lockAcc' AND roleID = 3";
			    } elseif ($createDate != '' && $lockAcc != '') {
			        $query = "SELECT * FROM user 
                    WHERE DATE(created_at)='$createDate'
                    AND lock_acc='$lockAcc' AND roleID = 3";
			    } else {
			        $query = "SELECT * FROM user WHERE roleID = 3";
			    }
			}else{
			    $query = "SELECT * FROM user WHERE roleID = 3";
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
							<th>Status</th>
    						<th>Role</th>
    					</tr>
					</thead>
					<tbody>
					<?php foreach($users as $userItem) : ?>
    			    <tr>
						<td><?= $userItem['_id']?></td>
						<td><?= $userItem['userName']?></td>
						<td><?= $userItem['fullName']?></td>
						<td><?= decryption($userItem['telephone'])?></td>
						<td><?= decryption($userItem['email'])?></td>
						<td>
							<?php 
							if($userItem['lock_acc']==1){
    							 echo '<span class="badge bg-danger">Inactive</span>';
    							}else{
                            	 echo '<span class="badge bg-primary">Active</span>';
    							}
							?>
						</td>
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