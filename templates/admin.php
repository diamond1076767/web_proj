<?php include("includes/header.php"); 
$sessionRole = $_SESSION['loggedInUser']['roleID'];
allowedRole([1,2]);
?>
<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<div class="row">
				<div class="col-md-4">
					<h4 class="mb-0">
						<?php
						if ($sessionRole == 2) {
							echo "Staff";
						} else {
							echo "Manager/Staff";
						}
						?>
					</h4>
				</div>
				<div class="col-md-8">
					<form action="" method="GET">
						<div class="row g-1">
							<div class="col-md-4">
								<input type="date"
									name="date"
									class="form-control"
									value="<?= isset($_GET['date']) == true ? $_GET['date'] : ''; ?>" />
							</div>

							<?php if ($sessionRole == 1 || $sessionRole == 2): ?>
								<div class="col-md-4">
									<select name="role_status" class="form-select">
										<option value="">Select User Role</option>

										<?php if ($sessionRole == 1): ?>
											<!-- Only Admin can see Manager -->
											<option value="Manager"
												<?= isset($_GET['role_status']) && $_GET['role_status'] == 'Manager' ? 'selected' : ''; ?>>
												Manager
											</option>
										<?php endif; ?>

										<option value="Staff"
											<?= isset($_GET['role_status']) && $_GET['role_status'] == 'Staff' ? 'selected' : ''; ?>>
											Staff
										</option>

									</select>
								</div>
							<?php endif; ?>
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
			<?php alertMessage(); ?>
											
			<?php
			$createDate = isset($_GET['date']) ? validate($_GET['date']) : '';
			$roleStatus = isset($_GET['role_status']) ? validate($_GET['role_status']) : '';

			if ($sessionRole == 2 && $roleStatus == 'Manager') {
				$roleStatus = 'Staff';
			}

			// Base query (always join role table)
			$query = "SELECT o._id AS user_id, o.*, c.roleName
			FROM user o		
			JOIN role c ON o.roleID = c._id
			WHERE 1";

			// Restrict visibility by role
			if ($sessionRole == 1) {
				$query .= " AND o._id <> 1";
			} elseif ($sessionRole == 2) {
				$query .= " AND o.roleID = 3";
			}

			// Apply date filter
			if ($createDate != '') {
				$query .= " AND DATE(o.created_at) = '$createDate'";
			}

			// Apply role filter
			if ($roleStatus != '') {
				$query .= " AND c.roleName = '$roleStatus'";
			}
			$users = mysqli_query($con, $query);

			if (!$users) {
				echo '<h4>Something Went Wrong!</h4>';
				return false;
			}

			if (mysqli_num_rows($users) > 0) {
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
							<?php foreach ($users as $userItem) : ?>
								<tr>
									<td><?= $userItem['user_id'] ?></td>
									<td><?= $userItem['userName'] ?></td>
									<td><?= decryption($userItem['fullName']) ?></td>
									<td><?= decryption($userItem['telephone']) ?></td>
									<td><?= decryption($userItem['email']) ?></td>
									<td><?= $userItem['roleName'] ?? 'Unknown'; ?></td>
									<td>
										<?php
										if ($userItem['lock_acc'] == 1) {
											echo '<span class="badge bg-danger">Banned</span>';
										} else {
											echo '<span class="badge bg-primary">Active</span>';
										}
										?>
									</td>

									<td>
										<form action="admin-edit.php" method="post" style="display: inline-block; margin-right: 1px;">
											<input type="hidden" name="userId" value="<?= validate($userItem['_id']) ?>">
											<button type="submit" class="btn btn-success btn-sm">Edit</button>
										</form>
										<?php if ($sessionRole == 1): ?>
										<form action="admin-delete.php" method="post" style="display: inline-block; margin-right: 1px;">
											<input type="hidden" name="userId" value="<?= validate($userItem['_id']) ?>">
											<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this user?')">Delete</button>
										</form>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

			<?php
			} else {
			?>
				<h4 class="mb-0">No Record found</h4>
			<?php
			}
			?>
		</div>
	</div>
</div>

<?php include("includes/footer.php"); ?>