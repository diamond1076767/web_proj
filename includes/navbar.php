<a class="visually-hidden-focusable" href="#main-content">Skip to main content</a>

<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #1a1f3a 0%, #2a3b6e 100%); box-shadow: 0 2px 16px rgba(0,0,0,0.25);" role="navigation" aria-label="Main navigation">
	<div class="container">

		<a class="navbar-brand fw-bold" href="index.php" style="letter-spacing: 0.05em;">
			<i class="fas fa-boxes-stacking me-2" style="opacity:0.85;" aria-hidden="true"></i>TP AMC
		</a>

		<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
			data-bs-target="#navbarSupportedContent"
			aria-controls="navbarSupportedContent" aria-expanded="false"
			aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-1">
				<li class="nav-item">
					<a class="nav-link" href="index.php"><i class="fas fa-home me-1" aria-hidden="true"></i>Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="products.php"><i class="fas fa-box-open me-1" aria-hidden="true"></i>Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="about.php"><i class="fas fa-info-circle me-1" aria-hidden="true"></i>About Us</a>
				</li>
				<?php if(isset($_SESSION['loggedIn'])) : ?>
				<li class="nav-item">
					<span class="nav-link text-white-50">
						<i class="fas fa-user-circle me-1" aria-hidden="true"></i><?= htmlspecialchars($_SESSION['loggedInUser']['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>
					</span>
				</li>
				<li class="nav-item">
					<a class="btn btn-sm btn-danger px-3 ms-1" href="logout.php">
						<i class="fas fa-sign-out-alt me-1" aria-hidden="true"></i>Logout
					</a>
				</li>
				<?php else: ?>
				<li class="nav-item">
					<a class="btn btn-sm btn-primary px-3 ms-1" href="login.php">
						<i class="fas fa-sign-in-alt me-1" aria-hidden="true"></i>Login
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>

<div id="main-content"></div>