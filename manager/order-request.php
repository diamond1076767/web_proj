<?php include('includes/header.php')?>

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
			
			if(isset($_GET['date']) || isset($_GET['request_status'])){
			    
			    $createDate = validate($_GET['date']);
			    $requestStatus = validate($_GET['request_status']);
			    
			    if($createDate != '' && $requestStatus == ''){
			        $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID AND DATE(request_date)='$createDate' ORDER BY request_id DESC";
			        
			    }elseif($createDate == '' && $requestStatus != ''){
			        $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID AND o.status='$requestStatus' ORDER BY request_id DESC";
			        
			    }elseif($createDate != '' && $requestStatus != ''){
			        $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID 
                    AND DATE(request_date)='$createDate'
                    AND o.status='$requestStatus' ORDER BY request_id DESC";
			        
			    }else{
			        $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c
                    WHERE c._id = o.customerID ORDER BY request_id DESC";
			    }
			}else{
			    $query = "SELECT o._id AS request_id,o.created_at AS request_date,o.*,c.* FROM request_order o, customer c 
                    WHERE c._id = o.customerID ORDER BY request_id DESC";
			}
			 $orders = mysqli_query($con, $query);
			 if($orders){
			     if(mysqli_num_rows($orders)>0){
			         ?>
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
			         		<?php foreach($orders as $orderItem) : ?>
			         		<tr>
			         			<td><?= validate($orderItem['customerName']);?></td>
			         			<td><?= validate(decryption($orderItem['telephone']));?></td>
			         			
			         			<td><?= validate($orderItem['payment_mode']);?></td>
			         			<td>
                                    <?php
                                    $userID = validate($orderItem['userID']);
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
								<td><?= date('d M, Y', strtotime($orderItem['request_date']));?></td>
								<td><?= $orderItem['status'];?></td>
			         			<td>
                                    <?php if ($orderItem['status'] == 'Pending') : ?>
										<form action="orders-request-view.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']) ?>">
                                            <button type="submit" class="btn btn-info btn-sm">View</button>
                                        </form>

                                        <form action="order-request-approve.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']) ?>">
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this request?')">Approve</button>
                                        </form>
                                        
                                        <form action="order-request-decline.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']) ?>">
                                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to decline this request?')">Decline</button>
                                        </form>
                                    <?php else : ?>
										<form action="orders-request-view.php" method="post" style="display: inline-block; margin-right: 1px;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']) ?>">
                                            <button type="submit" class="btn btn-info btn-sm">View</button>
                                        </form>
                                        
                                        <form action="order-request-delete.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="requestId" value="<?= validate($orderItem['request_id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
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