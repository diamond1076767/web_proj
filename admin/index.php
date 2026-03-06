<?php include("includes/header.php");?>

<div class="container-fluid px-4">
	<div class="row">
		<div class="col-md-12 mb-3">
			<h1 class="mt-4">Dashboard</h1>
			<?php alertMessage()?>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-primary p-3" style="border-width:medium">
				<p class="text-sm mb-0 text-capitalize">Total Products</p>
				<h5 class="font-bold mb-0">
					<?= getCount('inventory') ?>
				</h5>
			</div>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-secondary p-3" style="border-width:medium">
				<p class="text-sm mb-0 text-capitalize">Total Users</p>
				<h5 class="font-bold mb-0">
					<?= getCount('user') ?>
				</h5>
			</div>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-success p-3" style="border-width:medium">
				<p class="text-sm mb-0 text-capitalize">Approved User Request</p>
				<h5 class="font-bold mb-0">
					<?php 
					$userID = validate($_SESSION['loggedInUser']['user_id']);
					   $totalRequests = mysqli_query($con, "SELECT * FROM request_user WHERE status='Approved'");
					   if($totalRequests){
					       if(mysqli_num_rows($totalRequests)>0){
					           $totalCountRequestss = mysqli_num_rows($totalRequests);
					           echo $totalCountRequestss;
					       }else{
					           echo "0";
					       }
					   }else{
					       echo 'Something Went Wrong!';
					   }
					?>
				</h5>
			</div>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-danger p-3" style="border-width:medium">
				<p class="text-sm mb-0 text-capitalize">Approved User Request</p>
				<h5 class="font-bold mb-0">
					<?php 
					$userID = validate($_SESSION['loggedInUser']['user_id']);
					   $totalRequests = mysqli_query($con, "SELECT * FROM request_user WHERE status='Pending'");
					   if($totalRequests){
					       if(mysqli_num_rows($totalRequests)>0){
					           $totalCountRequestss = mysqli_num_rows($totalRequests);
					           echo $totalCountRequestss;
					       }else{
					           echo "0";
					       }
					   }else{
					       echo 'Something Went Wrong!';
					   }
					?>
				</h5>
			</div>
		</div>
		
		
		<div class="col-md-12 mb-3">
			<hr>
			<h5>Sales Orders</h5>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-warning p-3" style="border-width:medium">
				<p class="text-sm mb-0 text-capitalize">Today Orders</p>
				<h5 class="fw-bold mb-0">
					<?php 
					   $todayDate = date('Y-m-d');
					   $todayOrders = mysqli_query($con, "SELECT * FROM sales_order WHERE order_date='$todayDate'");
					   if($todayOrders){
					       if(mysqli_num_rows($todayOrders)>0){
					           $totalCountOrders = mysqli_num_rows($todayOrders);
					           echo $totalCountOrders;
					       }else{
					           echo "0";
					       }
					   }else{
					       echo 'Something Went Wrong!';
					   }
					?>
				</h5>
			</div>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-info p-3" style="border-width:medium">
				<p class="text-sm mb-0 text-capitalize">Total Orders</p>
				<h5 class="font-bold mb-0">
					<?= getCount('sales_order') ?>
				</h5>
			</div>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-dark p-3" style="border-width:medium">
				<p class="text-sm mb-0 text-capitalize">Total Customers</p>
				<h5 class="font-bold mb-0">
					<?= getCount('customer') ?>
				</h5>
			</div>
		</div>
		
		<div class="col-md-3 mb-3">
			<div class="card card-body border-muted p-3" style="border-width:medium"">
				<p class="text-sm mb-0 text-capitalize">Total Category</p>
				<h5 class="font-bold mb-0">
					<?= getCount('categories') ?>
				</h5>
			</div>
		</div>
	</div>
	
</div>

<?php include("includes/footer.php");?>
