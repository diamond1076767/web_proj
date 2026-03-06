<?php include("includes/header.php");?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Edit Customer
				<a href="customer.php" class="btn btn-primary float-end">Back</a>
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			<form action="staff-code.php" method="POST">
			
				<?php 
				if (isset($_POST['customerId'])) {
					// Get customer details by ID
				$_SESSION['customerID'] = $_POST['customerId'];
				}

				if (isset($_SESSION['customerID'])) {
                    $customerID = $_SESSION['customerID'];
                    $customer = getById('customer', $customerID);

                    if ($customer['status'] == 200) {
				?>    
				<div class="row">
					<div class="col-md-12 mb-3">
    					<label for="">Customer Name *</label>
    					<input type="text" name="name" value='<?= validate($customer['data']['customerName']); ?>' required class="form-control" />
    				</div>
					<div class="col-md-12 mb-3">
						<label>Company *</label> <br/> <input type="text" name="companyName" value='<?= validate($customer['data']['companyName']); ?>'
							required class="form-control">
					</div>					

					<div class="col-md-12 mb-3">
						<label>Email *</label> <br/> <input type="email" name="email" value='<?= validate(decryption($customer['data']['email'])); ?>'
							required class="form-control">
					</div>
					<div class="col-md-12 mb-3">
						<label>Telephone *</label> <br/> <input type="text" name="phone" value='<?= validate(decryption($customer['data']['telephone'])); ?>'
							required class="form-control">
					</div>

					<div class="col-md-12 mb-3 text-start">
						</br>
						<button type="submit" name="updateCustomer" class="btn btn-primary">Submit</button>
					</div>
				</div>
				<?php 
				}
				else{
				    echo '<h5>'.$customer['message'].'</h5>';
				}
			}
				?>
			</form>
		</div>
	</div>
</div>

<?php include("includes/footer.php");?>
