<?php include('includes/header.php');
allowedRole([1, 2]);
?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<div class="row">
				<div class="col-md-4">
					<h4 class="mb-0">User Requests</h4>
				</div>
				<div class="col-md-8">
					<form action="" method="GET">
						<div class="row g-1">
							<div class="col-md-4">
								<input type="date"
								aria-label="Filter by date"
									name="date"
									class="form-control"
									value="<?= isset($_GET['date']) ? $_GET['date'] : ''; ?>" />
							</div>
							<div class="col-md-4">
								<select name="request_status" class="form-select" aria-label="Request Status">
									<option value="">Select Request Status</option>
									<?php
									$statuses = ['Pending', 'Approved', 'Declined'];
									foreach ($statuses as $status) {
										$selected = (isset($_GET['request_status']) && $_GET['request_status'] == $status) ? 'selected' : '';
										echo "<option value='$status' $selected>$status</option>";
									}
									?>
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
			<?php alertMessage(); ?>

			<?php
			$where = [];
			if (!empty($_GET['date'])) {
				$date = validate($_GET['date']);
				$where[] = "DATE(ru.created_at) = '$date'";
			}
			if (!empty($_GET['request_status'])) {
				$status = validate($_GET['request_status']);
				$where[] = "ru.status = '$status'";
			}
			$whereSQL = !empty($where) ? "WHERE " . implode(' AND ', $where) : '';

			// Query without joining 'user' table
			$query = "
				SELECT ru.*, r.roleName
				FROM request_user ru
				LEFT JOIN role r ON ru.roleID = r._id
				$whereSQL
				ORDER BY ru._id DESC
			";
			$requests = mysqli_query($con, $query);

			if ($requests && mysqli_num_rows($requests) > 0):
			?>

				<table class="table table-striped table-bordered align-items-center">
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
						<?php while ($reqItem = mysqli_fetch_assoc($requests)) : ?>
							<tr>
								<td><?= $reqItem['userName']; ?></td>
								<td><?= $reqItem['fullName']; ?></td>
								<td><?= $reqItem['roleName'] ?? '-'; ?></td>
								<td><?= decryption($reqItem['telephone']); ?></td>
								<td><?= decryption($reqItem['email']); ?></td>

								<!-- Use requesterName directly -->
								<td><?= $reqItem['requesterName'] ?? '-'; ?></td>

								<td><?= date('d M, Y', strtotime($reqItem['created_at'])); ?></td>
								<td><?= $reqItem['status']; ?></td>

								<td>
									<?php 
									$currentUserRole = $_SESSION['loggedInUser']['roleID'] ?? null;

									if ($currentUserRole == 1):
										if ($reqItem['status'] == 'Pending'): ?>
											<form action="user-request-approve.php" method="post" style="display:inline-block;">
												<input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
												<button type="submit" name="approveUser" class="btn btn-success btn-sm"
													onclick="return confirm('Approve this request?')">
													Approve
												</button>
											</form>
											<form action="user-request-decline.php" method="post" style="display:inline-block;">
												<input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
												<button type="submit" name="declineUser" class="btn btn-warning btn-sm"
													onclick="return confirm('Are you sure you want to decline this request?')">
													Decline
												</button>
											</form>
										<?php else: ?>
											<form action="user-request-delete.php" method="post" style="display:inline-block;">
												<input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
												<button type="submit" class="btn btn-danger btn-sm"
													onclick="return confirm('Are you sure you want to delete this request?')">
													Delete
												</button>
											</form>
										<?php endif; 
									elseif ($currentUserRole == 2): ?>
										<form action="user-request-delete.php" method="post" style="display:inline-block;">
											<input type="hidden" name="requestId" value="<?= validate($reqItem['_id']) ?>">
											<button type="submit" class="btn btn-danger btn-sm"
												onclick="return confirm('Delete your request?')">
												Delete
											</button>
										</form>
									<?php else:
										echo '<span class="text-muted">No Actions</span>';
									endif; ?>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>

			<?php else: ?>
				<h5>No Record Available</h5>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php include('includes/footer.php'); ?>
