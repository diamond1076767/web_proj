<?php
// Include header file with common elements
include('includes/header.php');
allowedRole([1,2,3]);
// Check if 'track' parameter is set in GET
if (isset($_POST['orderId'])) {
	// Get customer details by ID
$_SESSION['orderID'] = $_POST['orderId'];
}
?>

<div class="container-fluid px-4">
	<!-- Card for displaying order details -->
	<div class="card mt-4 shadow-sm">
	<div class="card-header">
				<div class="row">
					<div class="col-md-6">
						<!-- Header with title "Order View" -->
						<h4 class="mb-0">Order View</h4>
					</div>
					<div class="col-md-6 text-end">
						<!-- Print and Back buttons -->
						<form action="orders-view-print.php" method="post" class="d-inline">
							<input type="hidden" name="trackId" value="<?= $_SESSION['orderID'] ?>">
							<button type="submit" class="btn btn-primary btn-sm">Print</button>
						</form>
						<a href="orders.php" class="btn btn-danger mx-2 btn-sm">Back</a>
					</div>
				</div>
			</div>
		<div class="card-body">

			<?php
			// Display alert messages
			alertMessage();
			?>

			<?php

			if (isset($_SESSION['orderID'])) {
				// Validate and retrieve the tracking number
				$trackingNo = $_SESSION['orderID'];

				// Query to retrieve order details
				$query = "SELECT o.*, c.* FROM sales_order o, customer c 
                            WHERE c._id = o.customerID AND tracking_no=? 
                            ORDER BY o._id DESC";

				$stmt = $con->prepare($query);
				$stmt->bind_param("s", $trackingNo);
				$stmt->execute();
				$orders = $stmt->get_result();

				if ($orders) {
					if (mysqli_num_rows($orders) > 0) {

						$orderData = mysqli_fetch_assoc($orders);

						?>
						<!-- Display order details in a card -->
						<div class="card card-body shadow border-1 mb-4">
							<div class="row">
								<div class="col-md-6">
									<!-- Display order details in the left column -->
									<h4>Order Details</h4>

									<label class="mb-1">
										Order Date:
										<span class="fw-bold"><?= validate($orderData['order_date']); ?></span>
									</label>
									</br>
									<label class="mb-1">
										Order Status:
										<span class="fw-bold"><?= validate($orderData['order_status']); ?></span>
									</label>
									</br>
									<label class="mb-1">
										Payment Mode:
										<span class="fw-bold"><?= validate($orderData['payment_mode']); ?></span>
									</label>
									</br>
								</div>

								<div class="col-md-6">
									<!-- Display user details in the right column -->
									<h4>User Details</h4>
									<label class="mb-1">
										Full Name:
										<span class="fw-bold"><?= validate($orderData['customerName']); ?></span>
									</label>
									</br>
									<label class="mb-1">
										Email Address:
										<span class="fw-bold"><?= validate(decryption($orderData['email'])); ?></span>
									</label>
									</br>
									<label class="mb-1">
										Phone Number:
										<span class="fw-bold"><?= validate(decryption($orderData['telephone'])); ?></span>
									</label>
									</br>
								</div>
							</div>
						</div>

						<?php
						// Query to retrieve order item details
						$orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.cost as orderItemPrice, o.*,oi.*,p.* ,c.*
                            FROM sales_order as o, order_items as oi,inventory as p, colour as c
                            WHERE oi.orderID = o._id AND p._id = oi.inventoryID AND p.colourID = c._id AND o.tracking_no=?";

						$stmtOrderItemQuery = $con->prepare($orderItemQuery);
						$stmtOrderItemQuery->bind_param("s", $trackingNo);
						$stmtOrderItemQuery->execute();
						$orderItemRes = $stmtOrderItemQuery->get_result();

						if ($orderItemRes) {
							if (mysqli_num_rows($orderItemRes) > 0) {
								?>
								<!-- Display order item details in a table -->
								<h4 class="my-3">Order Item Details</h4>
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Product</th>
											<th>Colour</th>
											<th>Price</th>
											<th>Quantity</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($orderItemRes as $orderItemRow) : ?>
											<tr>
												<!-- Display order item details in each row -->
												<td>
													<img src="<?= $orderItemRow['image'] != '' ? '../' . $orderItemRow['image'] : '../assets/images/no-img.png'; ?>"
														style="width:50px;height:50px;"
														alt="Img" />
													<?= validate($orderItemRow['title']); ?>
												</td>
												<td width="15%" class="fw-bold text-center">
													<?= validate($orderItemRow['colourName']) ?>
												</td>
												<td width="15%" class="fw-bold text-center">
													<?= validate(number_format($orderItemRow['orderItemPrice'], 2)) ?>
												</td>
												<td width="15%" class="fw-bold text-center">
													<?= validate($orderItemRow['orderItemQuantity']) ?>
												</td>
												<td width="15%" class="fw-bold text-center">
													<?= validate(number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'], 2)) ?>
												</td>
											</tr>
										<?php endforeach; ?>

										<tr>
											<!-- Display total price row -->
											<td colspan="2" class="text-end fw-bold">Total Price: </td>
											<td colspan="3" class="text-end fw-bold">SGD <?= validate(number_format($orderItemRow['total_amount'], 2)); ?></td>
										</tr>
									</tbody>
								</table>
								<?php
							} else {
								echo '<h5>No Record Found!</h5>';
								return false;
							}
						} else {
							echo '<h5>Something Went Wrong</h5>';
							return false;
						}
						?>

						<?php
					} else {
						echo '<h5>No Record Found!</h5>';
						return false;
					}
				} else {
					echo '<h5>Something Went Wrong</h5>';
				}
			} else {
				?>
				<!-- Display message if 'track' parameter is not found -->
				<div class="text-center py-5">
					<h5>No Tracking Number Found</h5>
					<div>
						<a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back To Orders</a>
					</div>
				</div>
				<?php
			}
			?>

		</div>
	</div>
</div>

<?php
// Include footer file with common elements
include('includes/footer.php');
?>
