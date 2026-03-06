<?php 
    $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
?>

<div id="layoutSidenav_nav">
	<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
		<div class="sb-sidenav-menu">
			<div class="nav">
				<div class="sb-sidenav-menu-heading">Core</div>
				
				<a class="nav-link <?= $page == 'index.php' ? 'active':'';?>" href="index.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-chart-pie"></i>
					</div> Dashboard
				</a>
				
				<a class="nav-link <?= $page == 'orders.php' ? 'active':'';?>" href="orders.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-dolly-flatbed"></i>
					</div> Orders
				</a>
				<a class="nav-link <?= $page == 'order-request-create.php' ? 'active':'';?>" href="order-request-create.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-boxes"></i>
					</div> Create Request
				</a>
				
				<a class="nav-link <?= $page == 'order-request.php' ? 'active':'';?>" href="order-request.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-bell"></i>
					</div> Order Request
				</a>

				<div class="sb-sidenav-menu-heading">Inventories</div>
				
				<a class="nav-link <?= $page == 'categories.php' ? 'active':'';?>" href="categories.php">
					<div class="sb-nav-link-icon">
						<i class="far fa-newspaper"></i>
					</div> Categories
				</a>
				
				<a class="nav-link <?= $page == 'inventory.php' ? 'active':'';?>" href="inventory.php">
					<div class="sb-nav-link-icon">
						<i class="fas fa-box-open"></i>
					</div> Inventory
				</a>
				
				<div class="sb-sidenav-menu-heading">Manage Users</div>

				<a class="nav-link <?= $page == 'customer.php' ? 'active':'';?>" href="customer.php">
					<div class="sb-nav-link-icon">
						<i class="far fa-address-card"></i>
					</div> Customer
				</a>

			</div>
		</div>
	</nav>
</div>
