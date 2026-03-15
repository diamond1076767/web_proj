<?php
$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$roleID = $_SESSION['loggedInUser']['roleID'] ?? 0;
?>

<div id="layoutSidenav_nav">
	<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
		<div class="sb-sidenav-menu">
			<div class="nav">

				<div class="sb-sidenav-menu-heading">Core</div>

				<a class="nav-link <?= $page == 'index.php' ? 'active' : ''; ?>" href="index.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-chart-pie"></i>
					</div> Dashboard
				</a>

				<?php if ($roleID == 1 && $roleID == 2): ?>
				<a class="nav-link <?= $page == 'order-create.php' ? 'active' : ''; ?>" href="order-create.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-boxes"></i>
					</div> Create Order
				</a>
				<?php endif; ?>

				<a class="nav-link <?= $page == 'orders.php' ? 'active' : ''; ?>" href="orders.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-dolly-flatbed"></i>
					</div> Orders
				</a>

				<?php if ($roleID == 2): ?>
					<!-- Manager only -->
					<a class="nav-link <?= $page == 'order-request.php' ? 'active' : ''; ?>" href="order-request.php">
						<div class="sb-nav-link-icon">
							<i class="fas fa-bell"></i>
						</div> Order Request
					</a>
				<?php endif; ?>

				<?php if ($roleID == 3): ?>
					<!-- Staff only -->
					<a class="nav-link <?= $page == 'order-request-create.php' ? 'active' : ''; ?>" href="order-request-create.php">
						<div class="sb-nav-link-icon">
							<i class="fas fa-boxes"></i>
						</div> Create Request
					</a>
				<?php endif; ?>

				<div class="sb-sidenav-menu-heading">Inventories</div>

				<a class="nav-link <?= $page == 'categories.php' ? 'active' : ''; ?>" href="categories.php">
					<div class="sb-nav-link-icon">
						<i class="far fa-newspaper"></i>
					</div> Categories
				</a>

				<a class="nav-link <?= $page == 'inventory.php' ? 'active' : ''; ?>" href="inventory.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-box-open"></i>
					</div> Inventory
				</a>

				<div class="sb-sidenav-menu-heading">Manage Users</div>

				<a class="nav-link <?= $page == 'customer.php' ? 'active' : ''; ?>" href="customer.php">
					<div class="sb-nav-link-icon">
						<i class="far fa-address-card"></i>
					</div> Customer
				</a>


				<?php if ($roleID != 3): ?>
					<?php
					$label = ($roleID == 2) ? "Staff" : "Manager/Staff";
					?>
					<a class="nav-link <?= ($page == 'admin.php') || ($page == 'admin-create.php') ? 'collapse active' : 'collapsed'; ?>"
						href="#" data-bs-toggle="collapse"
						data-bs-target="#collapseadmin" aria-expanded="false"
						aria-controls="collapseadmin">

						<div class="sb-nav-link-icon">
							<i class="fas fa-restroom"></i>
						</div> <?= $label ?>
						<div class="sb-sidenav-collapse-arrow">
							<i class="fas fa-angle-down"></i>
						</div>
					</a>
					<div class="collapse
				<?= ($page == 'admin.php') || ($page == 'admin-create.php') || ($page == 'user-request.php') ? 'show' : ''; ?>
					"
						id="collapseadmin"
						aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
						<nav class="sb-sidenav-menu-nested nav">
							<a class="nav-link <?= $page == 'admin.php' ? 'active' : ''; ?>" href="admin.php">View Users</a>
							<a class="nav-link <?= $page == 'user-request.php' ? 'active' : ''; ?>" href="user-request.php">View Requests</a>
							<?php if ($roleID == 1): // Only Admin can add new users ?>
                				<a class="nav-link <?= $page == 'admin-create.php' ? 'active' : ''; ?>" href="admin-create.php">Add New User</a>
            				<?php endif; ?>
						</nav>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</nav>
</div>