<?php include('includes/header.php');
if (!isset($_SESSION['loggedInUser']['roleID']) || !in_array($_SESSION['loggedInUser']['roleID'], [1,2])) {
        redirect('index.php', 'Access Denied. Admin or Manager only.');
        exit();
    }
?>

<div class="modal fade" id="addUserModal" data-bs-backdrop='static' data-bs-keyboard='false' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
      	<div class="mb-3" style="text-align:left">
        	<label>Role*</label>
        	<input type="text" class="form-control" id="role_id" />
        </div>
      
        <div class="col-md-6 mb-3">
        	<label>Username *</label>
        	<input type="text" class="form-control" id="username"/>
        </div>
        
        <div class="col-md-6 mb-3">
        	<label>Full Name *</label>
        	<input type="text" class="form-control" id="fullname"/>
        </div>
        
        <div class="col-md-6 mb-3">
        	<label>Phone No. *</label>
        	<input type="text" class="form-control" id="phone" />
        </div>
        
        <div class="col-md-6 mb-3">
        	<label>Email Address *</label>
        	<input type="email" class="form-control" id="email" />
        </div>
        <div class="col-md-6 mb-3">
        	<label>Password *</label>
        	<input type="password" required class="form-control" id="password" />
        </div>
        <div class="col-md-6 mb-3">
        	<label>Confirm Password *</label>
        	<input type="password" required class="form-control" id="conpassword" />
        </div>
        <input type="hidden" id="hidden_role_id" name="hidden_role_id" />
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary saveUser">Confirm</button>
      </div>
    </div>
  </div>
</div>


<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">			
			<div class="row">
				<div class="col-md-4">
					<h4 class="mb=0">User Requests</h4>
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
								<button type="submit" class="btn btn-primary">Filter</button>
								<a href="user-request.php" class="btn btn-danger">Reset</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			<?php 
			
			if(isset($_GET['date']) || isset($_GET['request_status'])){
			    
			    $requestDate = validate($_GET['date']);
			    $requestStatus = validate($_GET['request_status']);
			    
			    if($requestDate != '' && $requestStatus == ''){
			        $query = "SELECT * FROM request_user 
                    WHERE DATE(created_at)='$requestDate' ORDER BY _id DESC";
			        
			    }elseif($requestDate == '' && $requestStatus != ''){
			        $query = "SELECT* FROM request_user 
                    WHERE status='$requestStatus' ORDER BY _id DESC";
			        
			    }elseif($requestDate != '' && $requestStatus != ''){
			        $query = "SELECT * FROM request_user 
                    WHERE DATE(created_at)='$requestDate' 
                    AND status='$requestStatus' ORDER BY _id DESC";
			        
			    }else{
			        $query = "SELECT * FROM request_user ORDER BY _id DESC";
			    }
			}else{
			    $query = "SELECT * FROM request_user ORDER BY _id DESC";
			}
			 $requests = mysqli_query($con, $query);
			 if($requests){
			     if(mysqli_num_rows($requests)>0){
			         ?>
			         <table class="table table-striped table-bordered align-items-center justify-content-center">
			         	<thead>
			         		<tr>
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
			         		<tr>
			         			<input type="hidden" class="hidden-roleid" value="<?= $reqItem['roleID'] ?>" />
			         			<input type="hidden" class="hidden-id" value="<?= $reqItem['_id'] ?>" />
			         			<td><?= $reqItem['userName'];?></td>
			         			<td><?= $reqItem['fullName'];?></td>
			         			<td>
                                    <?php
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

			         			<td><?= decryption($reqItem['telephone']);?></td>
			         			<td><?= decryption($reqItem['email']);?></td>
			         			
			         			<td>
			         			<?php
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
                                    <?php if ($reqItem['status'] == 'Pending') : ?>
                                        <a href="#" class="btn btn-success btn-sm approveButton">Approve</a>

										<form action="user-request-decline.php" method="post" style="display: inline-block; margin-right: 1px;">
										<input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
											<button type="submit" class="btn btn-warning btn-sm" onclick= "return confirm('Are you sure you want to decline this request?')">Decline</button>
										</form>
                                    <?php else : ?>
										<form action="user-request-delete.php" method="post" style="display: inline-block; margin-right: 1px;">
										<input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
											<button type="submit" class="btn btn-danger btn-sm" onclick= "return confirm('Are you sure you want to delete this request?')">Delete</button>
										</form>
                                    <?php endif; ?>
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