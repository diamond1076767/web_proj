<?php include("includes/header.php");
allowedRole([1,2,3]);
?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Inventories
				<a href="inventory-create.php" class="btn btn-primary float-end">Add Product</a>
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage(); ?>

			<?php
			$inventory = getAll('inventory');

			if (!$inventory) {
				echo '<h4>Something Went Wrong!</h4>';
				return false;
			}

			if (mysqli_num_rows($inventory) > 0) {
			?>
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Image</th>
								<th>Name</th>
								<th>Colour</th>
								<th>Category</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>Description</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($inventory as $item) : ?>
								<tr>
									<td><?= $item['_id'] ?></td>
									<td>
										<img src="<?= $item['image'] != '' ? '../' . $item['image'] : '../assets/images/no-img.png'; ?>"
											style="width:50px;height:50px;"
											alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?> product image" />
									</td>
									<td><?= $item['title'] ?></td>
									<td>
										<?php
										$colourID = $item['colourID'];
										if ($colourID !== null) {
											$query = "SELECT colourName FROM colour WHERE _id = $colourID";
											$result = mysqli_query($con, $query);
											if ($result && mysqli_num_rows($result) > 0) {
												$colorRow = mysqli_fetch_assoc($result);
												echo $colorRow['colourName'];
											} else {
												echo '-';
											}
										} else {
											echo '-';
										}
										?>
									</td>

									<td>
										<?php
										$categoryID = $item['categoryID'];
										if ($categoryID !== null) {
											$query = "SELECT categoryName FROM categories WHERE _id = $categoryID";
											$result = mysqli_query($con, $query);
											if ($result && mysqli_num_rows($result) > 0) {
												$catRow = mysqli_fetch_assoc($result);
												echo $catRow['categoryName'];
											} else {
												echo '-';
											}
										} else {
											echo '-';
										}
										?>
									</td>
									<td><?= $item['quantity'] ?></td>
									<td><?= $item['cost'] ?></td>
									<td><?= !empty($item['description']) ? $item['description'] : '-'; ?></td>
									<td>
										<?php
										if ($item['status'] == 1) {
											echo '<span class="badge bg-danger">Hidden</span>';
										} else {
											echo '<span class="badge bg-primary">Visible</span>';
										}
										?>
									</td>
									<td>
										<form action="inventory-edit.php" method="post" style="display: inline-block; margin-right: 1px;">
											<input type="hidden" name="invenId" value="<?= validate($item['_id']) ?>">
											<button type="submit" class="btn btn-success btn-sm">Edit</button>
										</form>

										<form action="inventory-delete.php" method="post" style="display: inline-block; margin-right: 1px;">
											<input type="hidden" name="invenId" value="<?= validate($item['_id']) ?>">
											<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
										</form>
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

